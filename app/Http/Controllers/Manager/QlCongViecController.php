<?php

namespace App\Http\Controllers\Manager;

use App\Exports\KPIExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\BaoCaoCongViec;
use App\Models\DonVi;
use App\Models\NamHoc;
use App\Models\User;
use App\Services\Cot_loi\DanhGiaTienDoService;
use App\Services\Dieu_kien\DieuKienKPIService;
use App\Services\Cot_loi\TrangThaiKPIService;
use App\Notifications\CongViecNotification;
use App\Services\Bao_cao\LocPhanCongService;
use App\Services\Support\TinhChiTieuService;

//use Illuminate\Container\Attributes\Auth;

class QlCongViecController extends Controller
{
    public function index(Request $request, DanhGiaTienDoService $danhGiaTienDoService,
     TrangThaiKPIService $trangThaiKPIService, LocPhanCongService $locPhanCongService) {
       // $nh = $locNamHocService->locNamHoc($request);
        $namhoc = NamHoc::all();
        $queryDV = DonVi::query();
        if(Auth::user()->role === 'manager'){
            $queryDV->where('id', Auth::user()->don_vi_id);
        }
        $phong = $queryDV->get();
        $queryUser = User::query();
        if($request->filled('filter_pb') && $request->filter_pb !== 'all'){
            $queryUser->where('don_vi_id', $request->filter_pb);
        }
        $user = $queryUser->get();
        $duLieu = $locPhanCongService->locTheoDieuKien($request);
        $chiTiet = $duLieu->chiTiet;
        $dsPhanCong = $chiTiet->filter( function ($item)  {
            if (Auth::user()->role == 'manager') {
                return $item->nguoiDuocGiao && $item->nguoiDuocGiao->don_vi_id == Auth::user()->don_vi_id;
            }
            return true;})->map(function ($cv) use ($danhGiaTienDoService) { 
                $dulieu = $danhGiaTienDoService->danhGiaTienDo($cv);
                $cv->ten_nv = $cv->nguoiDuocGiao ? $cv->nguoiDuocGiao->name : 'N/A';
                $cv->ngay_bao_cao = $cv->baoCaoMoiNhat ? Carbon::parse($cv->baoCaoMoiNhat->ngay_thuc_hien)->format('d/m/Y') : 'N/A';
                //Cột hiệu suất tiến độ so với chỉ tiêu
                $cv->ten_kpi = $dulieu['ten_kpi'];
                $cv->hieu_suat = $dulieu['hieu_suat']; //so sánh chỉ tiêu và thực tế đạt được
                $cv->tien_do = $dulieu['tien_do']; 
                $cv->tien_do_ngay = $dulieu['tien_do_ngay']; //cột % thời gian đã trôi qua
                $cv->danh_gia = $dulieu['danh_gia'];
                $cv->deadline = $dulieu['deadline'];
                $cv->bao_cao_chua_duyet = $cv->baoCaoCongViec->where('trangthai_duyet', 'chua_duyet');
                $cv->tien_do_du_kien = $dulieu['tien_do_du_kien'];
                $cv->trang_thai_tinh = $dulieu['trang_thai_tinh'];
                $cv->canh_bao = $dulieu['canh_bao'];
                return $cv;
            });
           
            $dsPhanCong = $dsPhanCong->sortByDesc(function ($cv) {
                return $cv->baoCaoCongViec->where('trangthai_duyet', 'chua_duyet')->count();
            });
            if ($request->trangthai && $request->trangthai != 'tat_ca') {
             //  dd($request->trangthai);
                $dsPhanCong = $trangThaiKPIService->locTheoTrangThai($dsPhanCong, $request->trangthai);
            }
          //  dd($dsPhanCong->pluck('phan_cong_id'));
            $group = $dsPhanCong->groupBy('phan_cong_id')->map(function ($items) {
                return [
                    'ten_kpi' => $items->first()->phanCong->thuVienKPI->ten_kpi ?? 'N/A',
                    'loai_kpi' => $items->first()->phanCong->loai_kpi ?? 'N/A',
                    'chi_tieu' => $items->first()->phanCong->thuVienKPI->chi_tieu ?? 0,
                    'don_vi' => $items->first()->phanCong->thuVienKPI->don_vi ?? 'N/A',
                    'dieu_kien_phu' => $items->first()->phanCong->dieu_kien_phu ?? 'N/A',
                    'so_lan_toi_thieu_thang' => $items->first()->phanCong->so_lan_toi_thieu_thang ?? 0,
                    'chu_ky_thang' => $items->first()->phanCong->chu_ky_thang ?? 0,
                    'chu_ky' => $items->first()->phanCong->thuVienKPI->chu_ky ?? 'N/A',
                    'tien_do_trung_binh' => round($items->avg('tien_do'), 2),
                    'chi_tiet' => $items
                ];
            });
        
        return view('qlcongviec.qltiendo_duyetbc', compact('group',  'user', 'namhoc', 'phong'));
    }
    public function export(Request $request, LocPhanCongService $locPhanCongService, DanhGiaTienDoService $danhGiaTienDoService)
    {
        try{

            $duLieu = $locPhanCongService->locTheoDieuKien($request);
            $chiTiet = $duLieu->chiTiet;

            $tk_kpi = $chiTiet->map(function ($cv) use ($danhGiaTienDoService) {
                $dulieu = $danhGiaTienDoService->danhGiaTienDo($cv);

                $cv->ten_nv = $cv->nguoiDuocGiao->name ?? 'N/A';
                $cv->ten_kpi = $dulieu['ten_kpi'];
                $cv->tien_do = $dulieu['tien_do'];
                $cv->danh_gia = $dulieu['danh_gia'];
                $cv->trang_thai_tinh = $dulieu['trang_thai_tinh'];
                $cv->canh_bao = $dulieu['canh_bao'];

                return $cv;
            });
        
            $groupNV = $tk_kpi->groupBy('user_id')->map(function ($items) {
                return [
                    'ten_nv' => $items->first()->nguoiDuocGiao->name ?? 'N/A',
                    'don_vi' => $items->first()->nguoiDuocGiao->donVi->ten_don_vi ?? 'N/A',
                    'tong_kpi' => $items->count(),
                    'dat' => count($items->where('trang_thai', 'da_hoan_thanh')),
                    'tien_do' => round($items->avg('tien_do'), 2),
                    'qua_han' => count($items->where('trang_thai', 'chua_dat'))
                ];
            });
            $groupDV = $tk_kpi->groupBy(fn ($item) => $item->nguoiDuocGiao->don_vi_id)
                ->map(function ($items) {
                    $tongKPI = $items->count();
                    $dat = $items->where('trang_thai', 'da_hoan_thanh')->count();
                    $quaHan = $items->where('trang_thai', 'chua_dat')->count();
                    $tongNhanVien = $items->pluck('user_id')->unique()->count();
                    $tienDo = $tongKPI > 0 ? round(($dat / $tongKPI) * 100, 2) : 0;
                    return [
                        'ten_don_vi' => $items->first()->nguoiDuocGiao->donVi->ten_don_vi ?? 'N/A',
                        'tong_nhan_vien' => $tongNhanVien,
                        'tong_kpi' => $tongKPI,
                        'dat' => $dat,
                        'qua_han' => $quaHan,
                        'tien_do' => $tienDo,
                    ];
                });
            return Excel::download(new KPIExport([
                                                'chi_tiet' => $tk_kpi,
                                                'nhan_vien' => $groupNV,
                                                'phong_ban' => $groupDV,
                                            ]),
                                            'kpi_report.xlsx');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xuất file thống kê: ' . $e->getMessage());
        }
    }
    public function xemBaoCao($id, DieuKienKPIService $service, TinhChiTieuService $tinhChiTieuService)
    {
        try {
            $baoCao = BaoCaoCongViec::with('chiTietPhanCong.phanCong.thuVienKPI')
                ->where('id', $id)
                ->where('trangthai_duyet', 'chua_duyet')
                ->first();
 
            $chiTiet = $baoCao->chiTietPhanCong;
            $phanCong = $chiTiet->phanCong;
            $chiTieuDG = 0;
            $dieu_kien_phu = $service->ktDieuKienPhuView($chiTiet, $baoCao);
            if($phanCong->loai_kpi === 'don_gian'){
                $chiTieuDG = $tinhChiTieuService->tinhTongChiTieu($phanCong);
            }
            return view('qlcongviec.qltiendo_phe_duyet.cotXemBaoCao', compact('dieu_kien_phu', 'baoCao', 'chiTieuDG'));
        } catch (\Throwable $e) {
            return response("Lỗi: " . $e->getMessage(), 500);
        }
    }
    public function duyetBaoCao(Request $request, TrangThaiKPIService $trangThaiKPIService){
        DB::beginTransaction();
        try {
            $bao_cao_cong_viec_id = $request->bao_cao_cong_viec_id;
            $baoCao = BaoCaoCongViec::find($bao_cao_cong_viec_id);
            $chiTiet = $baoCao->chiTietPhanCong;
            $tiendo = $baoCao->tien_do_thuc;
            $baoCao->update([
                'trangthai_duyet' => 'da_duyet',
                'user_duyet_id' => Auth::id(),
            ]);
            if ($chiTiet->phanCong->loai_kpi === 'nang_cao') {
                if ($tiendo >= 100) {
                    $chiTiet->thuc_te_dat_duoc += 1;
                }
            } else {
                $chiTiet->thuc_te_dat_duoc += $tiendo;
            }
            $chiTiet->save();
            $trangThaiKPIService->luuTrangThai($chiTiet);

            $nhanVien = User::find($baoCao->user_id);
            
            $duongDan = route('profile.index');
            $title = "Báo cáo đã được duyệt";
            $tinNhan = "Báo cáo '" . $baoCao->chiTietPhanCong->phanCong->thuVienKPI->ten_kpi . "' đã được duyệt.";

            $nhanVien->notify(new CongViecNotification($title, $tinNhan, $duongDan));
            DB::commit();
            return redirect()->back()->with('success', 'Báo cáo đã được duyệt thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi duyệt báo cáo: ' . $e->getMessage());
        }
    }
    public function traLaiBaoCao(Request $request){
        DB::beginTransaction();
        try {
           // dd($request->all());
            $bao_cao_cong_viec_id = $request->bao_cao_cong_viec_id;
            $ghichutl = $request->ghi_chu_tl;
            $baoCao = BaoCaoCongViec::find($bao_cao_cong_viec_id);
            $request->validate([
                'ghi_chu_tl' => 'required|min:5',
            ], [
                'ghi_chu_tl.required' => 'Vui lòng nhập lý do trả lại báo cáo!',
            ]);
            $ghichutl = " - Lý do trả lại báo cáo]: " . $request->ghi_chu_tl;
                    
            $baoCao->update([
                'tien_do_thuc' => 0,
                'trangthai_duyet' => 'tra_lai',
                'user_duyet_id' => Auth::id(),
                'ly_do_tra_lai' => $ghichutl,
            ]);
            $nhanVien = User::find($baoCao->user_id);
            $title = "Yêu cầu chỉnh sửa báo cáo";
            $tinNhan = "Báo cáo '" . $baoCao->chiTietPhanCong->phanCong->thuVienKPI->ten_kpi  . "' đã bị trả lại. Để xem lý do: hãy vào lịch sử báo cáo";
            $duongDan = route('profile.index');
            // Truyền vào 2 tham số như đã định nghĩa ở trên
            $nhanVien->notify(new CongViecNotification($title, $tinNhan, $duongDan));
            DB::commit();
            return redirect()->back()->with('success', 'Báo cáo đã được trả lại thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi trả báo cáo: ' . $e->getMessage());
        }
    }
}
