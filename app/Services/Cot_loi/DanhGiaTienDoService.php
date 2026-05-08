<?php

namespace App\Services\Cot_loi;

use Carbon\Carbon;
use App\Services\Canh_bao\CanhBaoKPIService;
use App\Services\Dieu_kien\DieuKienKPIService;
use App\Services\Support\TinhChiTieuService;
use App\Services\Tan_suat\TanSuatKPIService;

class DanhGiaTienDoService{
    protected $tanSuatKPIService;
    protected $dieuKienKPIService;
    protected $canhBaoService;
    protected $trangThaiKPIService;
    protected $tinhChiTieuService;
    public function __construct(TanSuatKPIService $tanSuatKPIService, CanhBaoKPIService $canhBaoService, 
        TrangThaiKPIService $trangThaiKPIService, DieuKienKPIService $dieuKienKPIService, TinhChiTieuService $tinhChiTieuService)
    {
        $this->tanSuatKPIService = $tanSuatKPIService;
        $this->canhBaoService = $canhBaoService;
        $this->trangThaiKPIService = $trangThaiKPIService;
        $this->dieuKienKPIService = $dieuKienKPIService;
        $this->tinhChiTieuService = $tinhChiTieuService;
    }
    public function danhGiaTienDo($chiTiet)
    {
        $phanCong = $chiTiet->phanCong;

        $now = Carbon::now();
        $kpi = $phanCong->thuVienKPI;
        $chiTiet->ten_kpi = $kpi ? $kpi->ten_kpi : 'N/A';

        $trang_thai_tinh = $chiTiet->trang_thai;
        $nbd = Carbon::parse($phanCong->ngay_bat_dau);
        $nkt = Carbon::parse($phanCong->ngay_ket_thuc);
        $chiTiet->tong_ngay = $nbd->diffInDays($nkt) + 1;
        $thoiDiemHoanThanh = optional(
                            $chiTiet->baoCaoCongViec()
                                ->where('trangthai_duyet', 'da_duyet')
                                ->latest('ngay_thuc_hien')
                                ->first()
                        )->ngay_thuc_hien;
        if ($now->lessThan($nbd)) {
            $chiTiet->ngay_hien_tai = 0;
        } else {

            $endPoint = $now;
            if ($trang_thai_tinh == 'da_hoan_thanh' && $thoiDiemHoanThanh) {
                $endPoint = Carbon::parse($thoiDiemHoanThanh);
            }

            $chiTiet->ngay_hien_tai = $nbd->diffInDays($endPoint) + 1;
        }
        $tienDoDuKien = 0;
       
        $baoCaoChoDuyet = $chiTiet->baoCaoCongViec->where('trangthai_duyet', 'chua_duyet')->first();

        $progress = $this->tinhTienDo($chiTiet);
        if($baoCaoChoDuyet){
            $tienDoDuKien = $this->tinhTienDoDuKien($chiTiet);
        }
       
        $chiTiet->tien_do = $progress['tien_do'];
        $chiTiet->hieu_suat = $progress['hieu_suat'];
       
        $chiTiet->lo_ngay = $chiTiet->ngay_hien_tai > $chiTiet->tong_ngay;
        $chiTiet->so_ngay_lo = $chiTiet->lo_ngay ? $nkt->diffInDays($now) : 0;
        $chiTiet->tien_do_ngay = $chiTiet->tong_ngay > 0 ? round(($chiTiet->ngay_hien_tai / $chiTiet->tong_ngay) * 100, 2) : 0;

        $chenhLech = $chiTiet->tien_do_ngay - $chiTiet->tien_do;
        if ($chenhLech >= 10) {
            $danhGia = 'Chậm tiến độ';
        } elseif ($chenhLech <= -10) {
            $danhGia = 'Vượt tiến độ';
        } else {
            $danhGia = 'Bình thường';
        }

        if ($trang_thai_tinh == 'da_hoan_thanh') {
            if (!$thoiDiemHoanThanh) {
                $deadline = 'Hoàn thành';
            } else {

                $ht = Carbon::parse($thoiDiemHoanThanh)->startOfDay();
                $kt = $nkt->startOfDay();

                if ($ht->lt($kt)) {
                    $deadline = 'Hoàn thành trước hạn';
                } elseif ($ht->eq($kt)) {
                    $deadline = 'Hoàn thành đúng hạn';
                } else {
                    $deadline = 'Hoàn thành muộn';
                }
            }
        } else {

            if ($phanCong->ngay_bat_dau > $now) {
                $deadline = 'Chưa bắt đầu';
            } else {
                $deadline = $chiTiet->lo_ngay ? 'Quá hạn' : 'Đang thực hiện';
            }
        }

        $canhBao = $this->canhBaoService->taoCanhBao($phanCong, $chiTiet);

        return [
            'ten_kpi' => $chiTiet->ten_kpi,
            'hieu_suat' => $chiTiet->hieu_suat,
            'tien_do' => $chiTiet->tien_do,
            'tien_do_ngay' => $chiTiet->tien_do_ngay,
            'danh_gia' => $danhGia,
            'deadline' => $deadline,
            'bao_cao_cho_duyet' => $baoCaoChoDuyet,
            'tien_do_du_kien' => $tienDoDuKien,
            'qua_han' => $chiTiet->lo_ngay,
            'trang_thai_tinh' => $trang_thai_tinh,
            'canh_bao' => $canhBao
        ];
    }
    private function tinhTienDo($chiTiet)
    {
        $phanCong = $chiTiet->phanCong;
        $loai = $phanCong->loai_kpi;
        
        $kpi = $phanCong->thuVienKPI;
        if ($loai === 'da_chi_tieu') {
            $baoCaoTruocHan = $chiTiet->baoCaoCongViec
                    ->where('trangthai_duyet', 'da_duyet')
                    ->where('ngay_thuc_hien', '<=', $phanCong->ngay_ket_thuc);
            $checkDK = $this->dieuKienKPIService->ktDieuKienTong($chiTiet, $baoCaoTruocHan);
            $details = $checkDK['details'] ?? [];
            $tong = count($details);
            $dat = collect($details)->where('dat', true)->count();

            $pvBaoCao = collect($details)->contains(fn($dk) => $dk['pham_vi'] === 'bao_cao');

            $progress = $tong > 0 ? ($dat / $tong) : 0;
            $quaHan = now()->gt($phanCong->ngay_ket_thuc);
            if ($chiTiet->trang_thai === 'da_hoan_thanh') {
                return [
                    'tien_do' => 100,
                    'hieu_suat' => "$tong/$tong tiêu chí"
                ];
            }
            if ($pvBaoCao) {
                if (!$quaHan) {
                    return [
                        'tien_do' => round($progress * 100, 2),
                        'hieu_suat' => "$dat/$tong tiêu chí (tạm)"
                    ];
                }
                return [
                    'tien_do' => round($progress * 100, 2),
                    'hieu_suat' => "$dat/$tong tiêu chí"
                ];
            }
            
            return [
                'tien_do' => round($progress * 100, 2),
                'hieu_suat' => "$dat/$tong tiêu chí"
            ];
        }
        $actual = $chiTiet->thuc_te_dat_duoc;
        if ($loai === 'don_gian') {
            $chi_tieu = $this->tinhChiTieuService->tinhTongChiTieu($phanCong);
        } else {
            $chi_tieu = $phanCong->thuVienKPI->chi_tieu ?? 0;
        }
        return [
            'tien_do' => $chi_tieu > 0 ? round(($actual / $chi_tieu) * 100, 2) : 0,
            'hieu_suat' => "$actual/$chi_tieu"
        ];
    }
    private function tinhTienDoDuKien($chiTiet)
    {
        $phanCong = $chiTiet->phanCong;
        $loai = $phanCong->loai_kpi;
        $bcChuaDuyet = $chiTiet->baoCaoCongViec()->where('trangthai_duyet', 'chua_duyet')->latest()->first();

        if ($loai === 'da_chi_tieu') {
            $allBC = $chiTiet->baoCaoCongViec()->where('trangthai_duyet', '!=', 'tra_lai')->get();

            $kt = $this->dieuKienKPIService->ktDieuKienTong($chiTiet, $allBC);

            $details = $kt['details'] ?? [];
            $tong = count($details);

            if ($tong == 0) return 0;

            $dat = collect($details)->where('dat', true)->count();

            return round(($dat / $tong) * 100, 2);
        }

        if ($loai === 'don_gian') {

            $chiTieu = $this->tinhChiTieuService->tinhTongChiTieu($phanCong);
            $duKien = $bcChuaDuyet->tien_do_thuc ?? 0;
            $tong = $chiTiet->thuc_te_dat_duoc + $duKien;

            return $chiTieu > 0 ? round(($tong / $chiTieu) * 100, 2) : 0;
        }

        if ($loai === 'nang_cao') {

            $chiTieu = $phanCong->thuVienKPI->chi_tieu ?? 0;
            $soLanDat = $chiTiet->thuc_te_dat_duoc;

            $duKien = 0;

            if ($bcChuaDuyet && $bcChuaDuyet->tien_do_thuc >= 100) {
                $duKien = 1;
            }
            return $chiTieu > 0 ? round((($soLanDat + $duKien) / $chiTieu) * 100, 2) : 0;
        }

        return 0;
    }
}