<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\BaoCaoCongViec;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DonVi;
use App\Models\NamHoc;
use App\Models\User;
use App\Services\Bao_cao\LocPhanCongService;
use App\Services\Cot_loi\DanhGiaTienDoService;
use App\Services\Cot_loi\TrangThaiKPIService;
use App\Services\Bao_cao\ThongKeChartService;

class DashboardController extends Controller
{
    
    public function index(Request $request, LocPhanCongService $locPhanCongService, ThongKeChartService $thongKeChartService, DanhGiaTienDoService $danhGiaTienDoService, TrangThaiKPIService $trangThaiKPIService){
        $now = Carbon::now();
        $namhoc = NamHoc::all();
        $phong = DonVi::get();
        $query = User::query();
        if($request->filled('filter_pb') && $request->filter_pb !== 'all'){
            $query->where('don_vi_id', $request->filter_pb);
        }
        $user = $query->get();
        $duLieu = $locPhanCongService->locTheoDieuKien($request);
        $chiTiet = $duLieu->chiTiet;
        $phanCong = $duLieu->phanCong;
        $tong = $phanCong->count();
        $hoanthanh = $phanCong->where('trang_thai', 'da_hoan_thanh')->count();
        $chuadat = $phanCong->where('trang_thai', 'chua_dat')->count();
        $dangthuchien = $phanCong->where('trang_thai', 'dang_thuc_hien')->count();
        $dangno = $phanCong->where('trang_thai', 'dang_no')->count();
        $quahan = $phanCong->filter(function ($cv) use ($now){
            return $cv->trang_thai != 'da_hoan_thanh' && $cv->ngay_ket_thuc < $now ;
        })->count();
        $saphethan = $phanCong->filter(function ($cv) use ($now) {
            return $cv->trang_thai != 'da_hoan_thanh' 
                && $cv->ngay_ket_thuc >= $now 
                && $cv->ngay_ket_thuc <= $now->copy()->addDays(7);
        })->count();
    //THỐNG KÊ THEO TỪNG NV
        //Vẽ chart BÁO CÁO THEO THÁNG
        $listIdPhanCongCT = $chiTiet->pluck('id');
        $baoCaoThang = $thongKeChartService->thongKeBCTheoThang($listIdPhanCongCT);
        $nhan = $baoCaoThang->pluck('thang');
        $giatri = $baoCaoThang->pluck('so_luong');
        
        $tk_kpi = $chiTiet->map(function ($ct) use ($danhGiaTienDoService) {

            $dulieu = $danhGiaTienDoService->danhGiaTienDo($ct);

            $ct->ten_nv = $ct->nguoiDuocGiao?->name ?? 'N/A';
            $ct->ten_kpi = $dulieu['ten_kpi'];
            $ct->tien_do = $dulieu['tien_do'];
            $ct->trang_thai_tinh = $dulieu['trang_thai_tinh'];
            $ct->canh_bao = $dulieu['canh_bao'];
            $ct->ngay_bat_dau = $ct->phanCong->ngay_bat_dau;
            $ct->ngay_ket_thuc = $ct->phanCong->ngay_ket_thuc;
            return $ct;
        });

        //lấy top nhân viên
        $topNhanVien = $tk_kpi->where('trang_thai_tinh', 'da_hoan_thanh')->groupBy('user_id')->map(function ($group){
            return[
                'avatar' =>$group->first()->nguoiDuocGiao->avatar ?? '',
                'ten_nv' => $group->first()->ten_nv,
                'so_luong' => $group->count()
            ];
        })->sortByDesc('so_luong')->take(5);
        
        $hoatDongGanDay = BaoCaoCongViec::with([
                            'chiTietPhanCong.phanCong.thuVienKPI',
                            'user'
                        ])
                        ->where('trangthai_duyet', 'da_duyet')
                        ->latest('updated_at')
                        ->take(5)
                        ->get();
        $group = $tk_kpi->filter(function ($cv) {
            $cb = mb_strtolower(trim($cv['canh_bao']));

            return str_contains($cb, 'quá hạn')
                || str_contains($cb, 'thiếu')
                || str_contains($cb, 'chưa đạt')
                || str_contains($cb, 'sắp hết hạn')
                || str_contains($cb, 'ngưỡng bù') || str_contains($cb, 'chưa hoàn thành');
        });
       
        return view('dashboard', compact( 'namhoc', 'phong', 'user','baoCaoThang','nhan', 'giatri', 'tong', 'hoanthanh', 
        'chuadat','dangthuchien','saphethan', 'quahan','dangno', 'topNhanVien', 'hoatDongGanDay', 'group'));
    }
    
}
