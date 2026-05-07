<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\BaoCaoCongViec;
use App\Models\ChiTietPhanCong;
use App\Models\NamHoc;
use App\Models\PhanCongCongViec;
use App\Models\User;
use App\Services\GiaoChiTieuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PhanCongController extends Controller
{
    public function index(Request $request){
        $namhoc = NamHoc::all();
        if ($request->filled('filter_nh')) {
                $nh = NamHoc::with(['phanCong'])->find($request->filter_nh);
            } else {
                $nh = NamHoc::with(['phanCong'])
                    ->where('ngay_bat_dau', '<=', now())
                    ->where('ngay_ket_thuc', '>=', now())
                    ->first();
            }
        $query = PhanCongCongViec::with(['thuVienKPI','chiTietPhanCong.nguoiDuocGiao', 'chiTietPhanCong.baoCaoCongViec']);
        $query->where(function ($q) use ($nh) {
            $q->whereBetween('ngay_bat_dau', [$nh->ngay_bat_dau, $nh->ngay_ket_thuc])
                ->orWhereBetween('ngay_ket_thuc', [$nh->ngay_bat_dau, $nh->ngay_ket_thuc])
                ->orWhere(function ($q2) use ($nh) {
                    $q2->where('ngay_bat_dau', '<=', $nh->ngay_bat_dau)
                        ->where('ngay_ket_thuc', '>=', $nh->ngay_ket_thuc);
                });
        });
        $query->whereHas('nguoiPhanCong', function ($q) use ($request) {
                $q->where('don_vi_id', Auth::user()->don_vi_id);
            });
        if ($request->filled('search')) {
            $query->whereHas('thuVienKPI', function ($q) use ($request) {
                $q->where('ten_kpi','like', "%$request->search%");
            });
        }
        $phanCong = $query->get();
        return view('phancong.index', compact('phanCong', 'namhoc'));
    }
    public function edit($id) {
        $phanCong = PhanCongCongViec::findOrFail($id);
        $phanCong->load(['chiTietPhanCong.nguoiDuocGiao', 'thuVienKPI']);
        $users = User::where('don_vi_id', Auth::user()->don_vi_id)->where('role', 'staff')->get();
        return view('phancong.edit', compact('phanCong', 'users'));
    }
    public function update(Request $request, $id, GiaoChiTieuService $service)
    {
        DB::beginTransaction();
        try {
            
            $phanCong = PhanCongCongViec::with('chiTietPhanCong.baoCaoCongViec')
                ->findOrFail($id);
           
            if ($phanCong->trang_thai === 'da_hoan_thanh') {
                DB::rollBack();
                return back()->with('error', 'KPI đã có hoàn thành, không thể thay đổi KPI');
            }
            $hasBaoCao = ChiTietPhanCong::where('phan_cong_id', $phanCong->id)
                ->whereHas('baoCaoCongViec')
                ->exists();
            if(!$request->ngay_ket_thuc || !$request->ghi_chu || !$request->muc_do){
                DB::rollBack();
                return back()->with('info', 'KPI đang thực hiện, chỉ có thể sửa: ngày kết thúc, ghi chú và mức độ ưu tiên');
            }
            if ($phanCong->trang_thai === 'dang_thuc_hien' && $hasBaoCao) {
                $phanCong->update([
                    'ngay_ket_thuc' => $request->ngay_ket_thuc,
                    'ghi_chu' => $request->ghi_chu,
                    'muc_do_uu_tien' => $request->muc_do,
                ]);

                DB::commit();
                return redirect()->route('manager.phancong')->with('success', 'Cập nhật thành công (chỉ cho phép sửa thông tin phụ)');
            }
            $dmcvId = $phanCong->thuVienKPI->danhMuc->id;
            $kpiId = $service->xuLyKPI($request, $dmcvId);
            $phanCong->update([
                'kpi_id' => $kpiId,
                'loai_kpi' => $request->loai_kpi,
                'so_lan_toi_thieu_thang' => $request->so_lan_toi_thieu_thang,
                'chu_ky_thang' => $request->chu_ky_thang,
                'cho_phep_bu' => $request->has('cho_phep_bu'),
                'nguong_duoc_bu' => $request->nguong_duoc_bu,
                'ngay_bat_dau' => $request->ngay_bat_dau,
                'ngay_ket_thuc' => $request->ngay_ket_thuc,
                'ghi_chu' => $request->ghi_chu,
                'muc_do_uu_tien' => $request->muc_do,
                'trang_thai' => $request->trang_thai ?? $phanCong->trang_thai,
            ]);

            if ($request->loai_kpi === 'da_chi_tieu') {
                $phanCong->dieu_kien_phu = $request->dieu_kien ?? [];
                $phanCong->save();
            }

            if ($request->has('user_ids')) {
                $phanCong->chiTietPhanCong()->forceDelete();

                foreach ($request->user_ids as $userId) {
                    $phanCong->chiTietPhanCong()->create([
                        'user_id' => $userId,
                        'thuc_te_dat_duoc' => 0,
                        'trang_thai' => 'chua_bat_dau'
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('manager.phancong')->with('success', 'Cập nhật phân công thành công');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $phanCong = PhanCongCongViec::with('chiTietPhanCong.baoCaoCongViec')
                ->findOrFail($id);

            if ($phanCong->trang_thai === 'da_hoan_thanh') {
                return back()->with('error', 'KPI đã hoàn thành, không thể xóa KPI');
            }

            $hasBaoCao = ChiTietPhanCong::where('phan_cong_id', $phanCong->id)
                ->whereHas('baoCaoCongViec')
                ->exists();
            if ($hasBaoCao) {
                return back()->with('error', 'Không thể xóa KPI khi đã có báo cáo');
            }
            $phanCong->chiTietPhanCong()->delete();
            $phanCong->delete();

            DB::commit();
            return back()->with('success', 'Xóa phân công thành công');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
