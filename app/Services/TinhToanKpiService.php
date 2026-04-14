<?php

namespace App\Services;

use App\Models\BaoCaoCongViec;
use App\Models\DanhMucCongViec;
use App\Models\ThuVienKPI;
use App\Models\PhanCongCongViec;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\NamHoc;
use Illuminate\Http\Request;

class TinhToanKpiService{
    public function locNamHoc(Request $request){
        if ($request->filled('filter_nh')) {
            $nh = NamHoc::with(['thuVienKPI.phanCong'])->find($request->filter_nh);
        } else {
            $nh = NamHoc::with(['thuVienKPI.phanCong'])->where('ngay_bat_dau', '<=', now())
                ->where('ngay_ket_thuc', '>=', now())
                ->first();
        }
        return $nh;
    }
    public function locTheoDieuKien(Request $request){
        $nh = $this->locNamHoc($request);
        $query = PhanCongCongViec::with(['nguoiDuocGiao', 'thuVienKPI']);
        $query->where(function ($q) use ($nh) {
            $q->whereBetween('ngay_bat_dau', [$nh->ngay_bat_dau, $nh->ngay_ket_thuc])
            ->orWhereBetween('ngay_ket_thuc', [$nh->ngay_bat_dau, $nh->ngay_ket_thuc])
            ->orWhere(function ($q2) use ($nh) {
                $q2->where('ngay_bat_dau', '<=', $nh->ngay_bat_dau)
                    ->where('ngay_ket_thuc', '>=', $nh->ngay_ket_thuc);
            });
        });
        if ($request->filled('filter_pb') && $request->filter_pb !== 'all') {
            $query->whereHas('nguoiDuocGiao', function ($q) use ($request) {
                $q->where('don_vi_id', $request->filter_pb);
            });
        }
        if ($request->filled('filter_trangthai')) {
            $query->where('trang_thai', $request->filter_trangthai);
        }
        if ($request->filled('filter_nv') && $request->filter_nv !== 'all') {
            $query->where('user_id', $request->filter_nv);
        }
        return $query->get();
    }
    //vẽ chart ở dashboard
    public function thongKeBCTheoThang($listIdPhanCong)
    {
        $baoCaoThang = BaoCaoCongViec::selectRaw("
                DATE_FORMAT(ngay_thuc_hien, '%Y-%m') as thang,
                COUNT(*) as so_luong
            ")
            ->whereIn('phan_cong_id', $listIdPhanCong) // Quan trọng: Chỉ lấy báo cáo thuộc các phân công đã lọc
            ->where('trangthai_duyet', 'da_duyet')
            ->where('duoc_tinh_kpi', 1)
            ->groupBy('thang')
            ->orderBy('thang')
            ->get();

        return $baoCaoThang;
    }
    public function tinhKpiTheoNam($cv, $nbd_nh, $nkt_nh){
        $nbd_kpi = Carbon::parse($cv->ngay_bat_dau);
        $nkt_kpi = Carbon::parse($cv->ngay_ket_thuc);

        $tu = $nbd_kpi->greaterThan($nbd_nh) ? $nbd_kpi : $nbd_nh;
        $den   = $nkt_kpi->lessThan($nkt_nh) ? $nkt_kpi : $nkt_nh;

        if($tu > $den) return 0;
        $tongngaykpi = $nbd_kpi->diffInDays($nkt_kpi) + 1;
        $tongngaynh = $tu->diffInDays($den) + 1;
        return ($tongngaynh / $tongngaykpi) * $cv->thuVienKPI->chi_tieu;
    }
//cho trang quản lý tiến độ
    public function danhGiaTienDo($cv, $kpiService){
        $now = Carbon::now();
        $kpi = $cv->thuVienKPI;
        $cv->ten_kpi  = $kpi ? $kpi->ten_kpi : 'N/A';
        $cv->hieu_suat = $cv->thuc_te_dat_duoc . '/' . ($kpi ? $kpi->chi_tieu : 0);
        //Tính tiến độ hoàn thành
        $soThangBaoCao = 0;
        $soThangYeuCau = 0;
        //Cột % hoàn thành tiến độ
        if($kpi && $cv->loai_kpi == 'don_gian' && !$cv->so_lan_toi_thieu_thang){
            $cv->tien_do = $kpi->chi_tieu > 0 ? round(($cv->thuc_te_dat_duoc / $kpi->chi_tieu) * 100, 2) : 0;
        }
        else if($kpi && $cv->loai_kpi == 'nang_cao'){
            if($cv->so_lan_toi_thieu_thang){
                $soThangBaoCao = $kpiService->laySoThangBaoCao($cv->id);
                $soThangYeuCau = $kpiService->layThangQuyDinh($cv->ngay_bat_dau, $cv->ngay_ket_thuc);
            
                if($soThangYeuCau > 0){
                $cv->tien_do = $soThangYeuCau > 0 ? round(($soThangBaoCao / $soThangYeuCau) * 100, 2) : 0;
                }
            }else{
                $cv->tien_do = $kpi->chi_tieu > 0 ? round(($cv->thuc_te_dat_duoc / $kpi->chi_tieu) * 100, 2) : 0;
            }
        }
        else{
            $cv->tien_do = 0;
        }
       
        //Tiến độ % thời gian
        $nbd =Carbon::parse($cv->ngay_bat_dau);
        $nkt = Carbon::parse($cv->ngay_ket_thuc);
        $cv->tong_ngay = $nbd->diffInDays($nkt) + 1;
        if($now->lessThan($nbd)){
            $cv->ngay_hien_tai = 0; 
        }else{
            $cv->ngay_hien_tai = $nbd->diffInDays($now) + 1;
            $trangThai = $kpiService->capNhatTrangThai($cv);
            if($trangThai == 'da_hoan_thanh'){
                $ngay_thuc_hien = $cv->baoCaoMoiNhat?->ngay_thuc_hien ?? $now;
                $ngay_bc = Carbon::parse($ngay_thuc_hien);
                $cv->ngay_hien_tai = $nbd->diffInDays($ngay_bc) + 1;
            }
        }
        $cv->lo_ngay = false;
        if($cv->ngay_hien_tai > $cv->tong_ngay){
            $cv->lo_ngay = true;
        }
        $cv->so_ngay_lo = $cv->lo_ngay ? $nkt->diffInDays($now) : 0;
        $cv->tien_do_ngay = $cv->tong_ngay > 0 ? round(($cv->ngay_hien_tai / $cv->tong_ngay) * 100, 2) : 0;
        
        //cột đánh giá tiến độ chậm
        $chenhLech = $cv->tien_do_ngay - $cv->tien_do;
        if ($chenhLech >= 10) {
            $danhGia = 'Chậm tiến độ';
        } elseif ($chenhLech <= -5) {
            $danhGia = 'Vượt tiến độ';
        } else {
            $danhGia = 'Bình thường';
        }
        //cột đánh giá deadline
        $loNgay = $cv->ngay_hien_tai > $cv->tong_ngay;

        if ($cv->trang_thai == 'da_hoan_thanh') {
            $deadline = $loNgay ? 'Hoàn thành muộn' : 'Hoàn thành trước hạn';
        }
        else {
            $deadline = $loNgay ? 'Quá hạn' : 'Đang thực hiện';
        }
         $baoCaoChoDuyet = $cv->baoCaoCongViec
            ->where('trangthai_duyet', 'chua_duyet');

        //tiến độ dự kiến = (thực tế đạt được + tiến độ chờ duyệt) / chỉ tiêu KPI * 100%
        $tienDoChoDuyet = $cv->baoCaoCongViec
            ->where('trangthai_duyet', 'chua_duyet')
            ->sum('tien_do_thuc');

        $tienDoDuKien = ($kpi && $kpi->chi_tieu > 0)
            ? round((($cv->thuc_te_dat_duoc + $tienDoChoDuyet) / $kpi->chi_tieu) * 100, 2)
            : 0;
      
        return [
            'ten_kpi' => $cv->ten_kpi,
            'hieu_suat' => $cv->hieu_suat,
            'tien_do' => $cv->tien_do,
            'tien_do_ngay' => $cv->tien_do_ngay,
            'danh_gia' => $danhGia,
            'deadline' => $deadline,
            'bao_cao_cho_duyet' => $baoCaoChoDuyet,
            'tien_do_du_kien' => $tienDoDuKien,
            'so_thang_bao_cao' => $soThangBaoCao,
            'so_thang_yeu_cau' => $soThangYeuCau,
            'qua_han' => $cv->lo_ngay
        ];
    }
}