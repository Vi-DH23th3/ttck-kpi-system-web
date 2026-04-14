<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PhanCongCongViec;
use App\Models\BaoCaoCongViec;
use App\Models\DanhMucCongViec;
use App\Models\DanhMucKpi;
use App\Models\DonVi;
use App\Models\NamHoc;
use App\Models\ThuVienKPI;
use App\Models\User;
use App\Services\KpiService;
use App\Services\TinhToanKpiService;
use App\Exports\CongViecExport;
// use Illuminate\Container\Attributes\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request, KpiService $kpiService, TinhToanKpiService $tinhToanKpiService){
        $now = Carbon::now();
        $namhoc = NamHoc::all();
        $phong = DonVi::all();
        $user = User::all();
        $phanCong = $tinhToanKpiService->locTheoDieuKien($request);
        //chart báo cáo theo tháng
        $listIdPhanCong = $phanCong->pluck('id');
        $baoCaoThang = $tinhToanKpiService->thongKeBCTheoThang($listIdPhanCong);
        $nhan = $baoCaoThang->pluck('thang');
        $giatri = $baoCaoThang->pluck('so_luong');
        //CARD
        $tong = (clone $phanCong)->count();
        $hoanthanh = (clone $phanCong)
            ->where('trang_thai', 'da_hoan_thanh')
            ->count();
        $chuadat = (clone $phanCong)
            ->where('trang_thai', 'chua_dat')
            ->count();
        $saphethan = (clone $phanCong)
            ->where('trang_thai', '!=', 'da_hoan_thanh')
            ->where('ngay_ket_thuc', '>=', $now)
            ->where('ngay_ket_thuc', '<=', $now->copy()->addDays(7))
            ->count();
        $dangthuchien = (clone $phanCong)
            ->where('trang_thai', 'dang_thuc_hien')
            ->count();
        $quahan = (clone $phanCong)
            ->where('ngay_ket_thuc', '<', $now)
            ->where('trang_thai', '!=', 'da_hoan_thanh')
            ->count();
       
        $tk_kpi = $phanCong->map(function ($cv) use ($tinhToanKpiService, $kpiService) { 
            $dulieu = $tinhToanKpiService->danhGiaTienDo($cv, $kpiService);
            $cv->ten_nv = $cv->nguoiDuocGiao ? $cv->nguoiDuocGiao->name : 'N/A';
            $cv->ten_kpi = $dulieu['ten_kpi'];
            $cv->so_thang_bao_cao = $dulieu['so_thang_bao_cao'];
            $cv->so_thang_yeu_cau = $dulieu['so_thang_yeu_cau'];
            $cv->tien_do = $dulieu['tien_do']; //cột % hoàn thành tiến độ: đơn giản vs nâng cao
            //cảnh báo 
            $cv->trang_thai_tinh = $kpiService->capNhatTrangThai($cv);
         
            $cv->canh_bao = $kpiService->taoCanhBao($cv);
            
            return $cv;
        });
        $group = $tk_kpi->groupBy(function ($cv) {
            return $cv->thuVienKPI->danhMuc->ten_cong_viec ?? 'Khác';
        });
        return view('dashboard', compact( 'namhoc', 'phong', 'user','baoCaoThang','nhan', 'giatri', 'tong', 'hoanthanh', 'chuadat','dangthuchien','saphethan', 'quahan', 'group'));
    }
    public function export(Request $request, TinhToanKpiService $tinhToanKpiService, KpiService $kpiService) 
    {
        try{
            $nv_xuat = Auth::user();
            if($request->filter_pb && $request->filter_pb != $nv_xuat->don_vi_id && $nv_xuat->role != 'admin'){
                return redirect()->back()->with('error', 'Bạn không có quyền xuất dữ liệu của phòng ban khác!');
            }
            $now = Carbon::now();
            $nh = $tinhToanKpiService->locNamHoc($request);
            $phanCong = $tinhToanKpiService->locTheoDieuKien($request);
            //CARD
            $tong = (clone $phanCong)->count();
            $hoanthanh = (clone $phanCong)
                ->where('trang_thai', 'da_hoan_thanh')
                ->count();
            $chuadat = (clone $phanCong)
                ->where('trang_thai', 'chua_dat')
                ->count();
            $saphethan = (clone $phanCong)
                ->where('trang_thai', '!=', 'da_hoan_thanh')
                ->where('ngay_ket_thuc', '>=', $now)
                ->where('ngay_ket_thuc', '<=', $now->copy()->addDays(7))
                ->count();
            $dangthuchien = (clone $phanCong)
                ->where('trang_thai', 'dang_thuc_hien')
                ->count();
            $quahan = (clone $phanCong)
                ->where('ngay_ket_thuc', '<', $now)
                ->where('trang_thai', '!=', 'da_hoan_thanh')
                ->count();
        
            $tk_kpi = $phanCong->map(function ($cv) use ($tinhToanKpiService, $kpiService) { 
                $dulieu = $tinhToanKpiService->danhGiaTienDo($cv, $kpiService);
                $cv->ten_nv = $cv->nguoiDuocGiao ? $cv->nguoiDuocGiao->name : 'N/A';
                $cv->ten_kpi = $dulieu['ten_kpi'];
                $cv->so_thang_bao_cao = $dulieu['so_thang_bao_cao'];
                $cv->so_thang_yeu_cau = $dulieu['so_thang_yeu_cau'];
                $cv->tien_do = $dulieu['tien_do']; //cột % hoàn thành tiến độ: đơn giản vs nâng cao
                //cảnh báo 
                $cv->trang_thai_tinh = $kpiService->capNhatTrangThai($cv);
                $cv->canh_bao = $kpiService->taoCanhBao($cv);
                
                return $cv;
            });
            $group = $tk_kpi->groupBy(function ($cv) {
                return $cv->thuVienKPI->danhMuc->ten_cong_viec ?? 'Khác';
            });                  
            return Excel::download(new CongViecExport($group, $tong, $hoanthanh, $chuadat, $dangthuchien,$saphethan, $quahan, $nh),
                 'export_kpi_' . now()->format('d_m_Y') . '.xlsx');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xuất file thống kê: ' . $e->getMessage());
        }
        
    }
}
