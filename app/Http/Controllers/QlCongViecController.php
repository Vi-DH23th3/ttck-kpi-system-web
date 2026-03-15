<?php

namespace App\Http\Controllers;

use App\Imports\GiaoKPIImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PhanCongCongViec;
use App\Models\BaoCaoCongViec;
use App\Models\DanhMucCongViec;
use App\Models\DanhMucKpi;
use App\Models\DonVi;
use App\Models\NamHoc;
use App\Models\ThuVienKPI;
use App\Models\User;
use App\Notifications\CongViecNotification;
//use Illuminate\Container\Attributes\Auth;

class QlCongViecController extends Controller
{
    public function index(){
        // $now = Carbon::now();
        // $tongviec = PhanCongCongViec::all()->count();
        // $dangthuchien = PhanCongCongViec::where('trang_thai', 'dang_thuc_hien')->count();
        // $choduyet = BaoCaoCongViec::where('trangthai_duyet', 'cho_duyet')->count();
        // $quahan = PhanCongCongViec::where('trang_thai', '!=', 'da_hoan_thanh')
        //                     ->where('ngay_ket_thuc', '<', $now)
        //                     ->count();
        // return view('qlcongviec.index', compact('tongviec','dangthuchien','choduyet','quahan'));
        //$dsPhanCong = PhanCongCongViec::with(['baoCaoCongViec', 'thuVienKPI', 'nguoiDuocGiao'])->get();
        $dsPhanCong = PhanCongCongViec::with(['baoCaoCongViec', 'thuVienKPI'])
            ->withCount(['baoCaoCongViec as chua_duyet_count' => function ($query) {
                $query->where('trangthai_duyet', 'chua_duyet');
            }])
            ->orderBy('chua_duyet_count', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return view('qlcongviec.qltiendo_duyetbc', compact('dsPhanCong'));
    }
    public function thuvienkpi(Request $request) {
        $query = ThuVienKPI::query();
        $dmcv = DanhMucCongViec::all();
        $namhoc = NamHoc::all();
       // $dmcount = 
        if($request->dm_id){
            $dmid = $request->input('dm_id');
            $query->where('dm_cv_id', $dmid);
        }
        if($request->nh_id){
            $nhid = $request->input('nh_id');
            $query->where('nam_hoc_id', $nhid);
        }
        $ds_kpi_mau = $query->get();
        return view('qlcongviec.thuvienkpi', compact('dmcv', 'ds_kpi_mau', 'namhoc'));
    }
    public function themThuVienKPI(Request $request) {
        ThuVienKPI::create([
            'ten_kpi' => $request->name_KPI,
            'chi_tieu' => $request->chi_tieu,
            'don_vi' => $request->don_vi,
            'chu_ky' => $request->chu_ky,
            'dm_cv_id' => $request->dm_id,
            'nam_hoc_id' => $request->nam_hoc_id,
            'ghi_chu' => $request->ghi_chu,
        ]);
        return redirect()->back()->with('success', 'Thêm kpi thành công');
    }
    
    public function duyetBaoCao(Request $request){
        DB::beginTransaction();
        try {
            $bao_cao_cong_viec_id = $request->bao_cao_cong_viec_id;
            $baoCao = BaoCaoCongViec::find($bao_cao_cong_viec_id);
            $tiendo = $baoCao->tien_do_thuc;
            $baoCao->update([
                'tien_do_thuc' => 0,
                'trangthai_duyet' => 'da_duyet',
                'user_duyet_id' => Auth::id(),
            ]);
            $phancongid = $baoCao->phan_cong_id;
            $phancong = PhanCongCongViec::find($phancongid);
            $phancong->thuc_te_dat_duoc += $tiendo;
            if($phancong->thuc_te_dat_duoc >= $phancong->thuVienKPI->chi_tieu){
                $phancong->trang_thai = 'da_hoan_thanh';
            }
            $phancong->save();
            $nhanVien = User::find($baoCao->user_id);
            $tinNhan = "Báo cáo '" . $baoCao->phanCong->thuVienKPI->ten_kpi . "' đã được duyệt.";
            $duongDan = route('profile.index');
            $nhanVien->notify(new CongViecNotification($tinNhan, $duongDan));
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
            if($request->has('ghi_chu_tl')){

                $ghichu = $baoCao->ghi_chu;
                $ghichutl = $ghichu . "\n [ " . Auth::user()->name . " - Lý do trả lại báo cáo]: " . $request->ghi_chu_tl;
            }         
            $baoCao->update([
                'tien_do_thuc' => 0,
                'trangthai_duyet' => 'tra_lai',
                'user_duyet_id' => Auth::id(),
                'ghi_chu' => $ghichutl,
            ]);
            $nhanVien = User::find($baoCao->user_id);
            $tinNhan = "Báo cáo '" . $baoCao->phanCong->thuVienKPI->ten_kpi . "' đã bị trả lại. Để xem lý do: hãy vào lịch sử báo cáo";
            $duongDan = route('profile.index');
            // Truyền vào 2 tham số như đã định nghĩa ở trên
            $nhanVien->notify(new CongViecNotification($tinNhan, $duongDan));
            DB::commit();
            return redirect()->back()->with('success', 'Báo cáo đã được trả lại thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi trả báo cáo: ' . $e->getMessage());
        }
    }
}
