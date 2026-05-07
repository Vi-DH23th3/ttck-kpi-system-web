<?php

namespace App\Services\Cot_loi;

use App\Services\Dieu_kien\DieuKienKPIService;
use App\Services\Support\TinhChiTieuService;
use App\Services\Tan_suat\TanSuatKPIService;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class TrangThaiKPIService
{
    protected $dieuKienKPIService;
    protected $tanSuatKPIService;
    protected $tinhChiTieuService;
    public function __construct(DieuKienKPIService $dieuKienKPIService, TanSuatKPIService $tanSuatKPIService, TinhChiTieuService $tinhChiTieuService)
    {
        $this->dieuKienKPIService = $dieuKienKPIService;
        $this->tanSuatKPIService = $tanSuatKPIService;
        $this->tinhChiTieuService = $tinhChiTieuService;
    }
    public function capNhatTrangThai($chiTiet)
    {
        $phanCong = $chiTiet->phanCong;
        $loai = $phanCong->loai_kpi;
        $now = Carbon::now();
        $start = Carbon::parse($phanCong->ngay_bat_dau);
        $deadline = Carbon::parse($phanCong->ngay_ket_thuc);

        if ($now < $start) {
            return 'chua_bat_dau';
        }

        $quaHan = $now->gt($deadline);
        
        $datDuoc = $chiTiet->thuc_te_dat_duoc;
        if ($loai === 'don_gian') {
            $chiTieu = $this->tinhChiTieuService->tinhTongChiTieu($phanCong);
        } else {
            $chiTieu = $phanCong->thuVienKPI->chi_tieu ?? 0;
        }

        if ($loai === 'don_gian') {
            $dat = $datDuoc >= $chiTieu;
            $phanTram = $chiTieu > 0 ? ($datDuoc / $chiTieu) * 100 : 0;
            if (!$quaHan) {
                return $dat ? 'da_hoan_thanh' : 'dang_thuc_hien';
            }
            if ($dat) return 'da_hoan_thanh';
            if ($phanCong->cho_phep_bu && $phanTram >= $phanCong->nguong_duoc_bu) {
                return 'dang_no';
            }
            return 'chua_dat';
        }

        if ($loai === 'da_chi_tieu') {
             return $this->tinhTrangThaiDaChiTieu($chiTiet);
        }

        if ($loai === 'nang_cao') {
            $datChiTieu = $datDuoc >= $chiTieu;

            $datTanSuat = true;
            if (!is_null($phanCong->so_lan_toi_thieu_thang)) {
                $datTanSuat = $this->tanSuatKPIService
                    ->ktTanSuatThang($phanCong, $chiTiet)['dat'] ?? false;
            }

            $datFull = $datChiTieu && $datTanSuat;

            if (!$quaHan) {
                return 'dang_thuc_hien';
            }
            if ($datFull) {
                return 'da_hoan_thanh';
            }
            $baoCaoSauHan = $chiTiet->baoCaoCongViec->where('trangthai_duyet', 'da_duyet')
                                                    ->where('ngay_thuc_hien', '>', $deadline);

            $tongBaoCao = $chiTiet->thuc_te_dat_duoc + $baoCaoSauHan->count();
            $phanTram = $chiTieu > 0 ? ($tongBaoCao / $chiTieu) * 100 : 0;
            if ($tongBaoCao >= $chiTieu) {
                return 'da_hoan_thanh';
            }
            if ($phanCong->cho_phep_bu && $phanTram >= $phanCong->nguong_duoc_bu) {
                return 'dang_no';
            }
            return 'chua_dat';
        }
        return 'dang_thuc_hien';
    }

    public function tinhTrangThaiDaChiTieu($chiTiet)
    {
        $phanCong = $chiTiet->phanCong;

        $now = Carbon::now();
        $ketThuc = Carbon::parse($phanCong->ngay_ket_thuc);
        $quaHan = $now->gt($ketThuc);

        $baoCaoTruocHan = $chiTiet->baoCaoCongViec->where('trangthai_duyet', 'da_duyet')->where('ngay_thuc_hien', '<=', $ketThuc);

        $baoCaoSauHan = $chiTiet->baoCaoCongViec->where('trangthai_duyet', 'da_duyet')->where('ngay_thuc_hien', '>', $ketThuc);

        $check = $this->dieuKienKPIService->ktDieuKienTong($chiTiet, $baoCaoTruocHan);
        $danhSachDK = $check['details'] ?? [];

        if (empty($danhSachDK)) {
            return 'dang_thuc_hien';
        }

        $tatCaDat = true;
        $coDangNo = false;
        $coFail   = false;

        foreach ($danhSachDK as $dk) {
            if ($dk['pham_vi'] === 'tat_ca') {

                $actual = $dk['actual'] ?? 0;
                $target = $dk['target'] ?? 0;

                $phanTram = $target > 0 ? ($actual / $target) * 100 : 0;
                $phanTram = min($phanTram, 100);

                if (!$quaHan) {
                    if ($phanTram < 100) {
                        $tatCaDat = false;
                    }
                    continue;
                }

                if ($phanTram >= 100) {
                    continue; 
                }

                if ($phanCong->cho_phep_bu && $phanTram >= ($phanCong->nguong_duoc_bu ?? 0)) {
                    $coDangNo = true;
                    continue;
                }
            $coFail = true;
            $tatCaDat = false;
            }

            else {

                $tongBaoCao = count($dk['actual']);
                $soLoi = count($dk['ds_loi']);

                if ($tongBaoCao == 0) {
                    $coFail = true;
                    $tatCaDat = false;
                    continue;
                }

                $soDat = $tongBaoCao - $soLoi;
                if (!$quaHan) {
                    continue;
                }
               
                $soBu = $baoCaoSauHan->count();
                if ($soDat + $soBu >= $tongBaoCao) {
                    continue;
                }

                $phanTram =$tongBaoCao > 0 ? ($soDat/$tongBaoCao) *100 : 0;
                if ( $phanCong->cho_phep_bu && $phanTram >= $phanCong->nguong_duoc_bu) {
                    $coDangNo = true;
                    $tatCaDat = false;
                    continue;
                }
               
                
                $coFail = true;
                $tatCaDat = false;
            }
        }
        if (!$quaHan) {
            return $tatCaDat ? 'da_hoan_thanh' : 'dang_thuc_hien';
        }

        if ($tatCaDat) {
            return 'da_hoan_thanh';
        }
        if ($coFail) {
            return 'chua_dat';
        }
        if ($coDangNo) {
            return 'dang_no';
        }

        return 'chua_dat';
    }

    public function trangThaiPhanCong($phanCong) {
        $now = Carbon::now();
        $ct = $phanCong->chiTietPhanCong;
        if ($ct->every(fn($i) => $i->trang_thai == 'chua_bat_dau')  && $now >= $phanCong->ngay_bat_dau) {
            return 'dang_thuc_hien';
        }
        if($ct->every(fn($i)=> $i->trang_thai == 'da_hoan_thanh')){
            return 'da_hoan_thanh';
        }
        if($ct->contains(fn($i)=> $i->trang_thai == 'dang_no')){
            return 'dang_no';
        }
        if($ct->contains(fn($i)=> $i->trang_thai == 'chua_dat')){
            return 'chua_dat';
        }
        return 'dang_thuc_hien';
    }
    public function luuTrangThai($chiTiet){
        $chiTiet->refresh();
        $trangThai = $this->capNhatTrangThai($chiTiet);
        if ($chiTiet->trang_thai !== $trangThai) {
            $chiTiet->update(['trang_thai' => $trangThai]);
        }

        $phanCong = $chiTiet->phanCong;
        if($phanCong){
            $phanCong->load('chiTietPhanCong');
            $trangThaiPC = $this->trangThaiPhanCong($phanCong);
            if($phanCong->trang_thai !== $trangThaiPC){
                $phanCong->update(['trang_thai' => $trangThaiPC]);
            }    
        }
    }
    public function locTheoTrangThai($dulieu, $trangThai)
    {
        return $dulieu->filter(function ($cv) use ($trangThai) {
            return $this->capNhatTrangThai($cv) === $trangThai;
        });
    }
   
}