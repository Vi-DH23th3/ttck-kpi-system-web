<?php

namespace App\Services\Canh_bao;

use App\Services\Cot_loi\TrangThaiKPIService;
use App\Services\Dieu_kien\DieuKienKPIService;
use App\Services\Support\TinhChiTieuService;
use App\Services\Tan_suat\TanSuatKPIService;
use Carbon\Carbon;

class CanhBaoKPIService
{
    protected $dieuKienKPIService;
    protected $tanSuatKPIService;
    protected $tinhChiTieuService;
    protected $trangThaiKPIService;
    public function __construct(DieuKienKPIService $dieuKienKPIService, TanSuatKPIService $tanSuatKPIService, TinhChiTieuService $tinhChiTieuService, TrangThaiKPIService $trangThaiKPIService)
    {
        $this->dieuKienKPIService = $dieuKienKPIService;
        $this->tanSuatKPIService = $tanSuatKPIService;
        $this->tinhChiTieuService = $tinhChiTieuService;
        $this->trangThaiKPIService = $trangThaiKPIService;
    }
    public function taoCanhBao($phanCong, $chiTiet)
    {
        $now = Carbon::now();
        $batDau = Carbon::parse($phanCong->ngay_bat_dau);
        $ketThuc = Carbon::parse($phanCong->ngay_ket_thuc);

        if ($now < $batDau) {
            return '';
        }

        $trangThai = $chiTiet->trang_thai;
        if (!$trangThai) {
            $trangThai = $this->trangThaiKPIService->tinhTrangThaiDaChiTieu($chiTiet);
        }
        if ($trangThai === 'da_hoan_thanh') {
            return "Đã hoàn thành";
        }

        $warnings = [];
        $quaHan = $now->gt($ketThuc);

        if (!$quaHan) {
            $soNgay = $now->diffInDays($ketThuc, false);
            if ($soNgay <= 7) {
                $warnings[] = "Sắp hết hạn ({$soNgay} ngày)";
            }
        }

        if ($phanCong->loai_kpi === 'don_gian') {

            if ($trangThai === 'dang_no') {
                return "Đạt ngưỡng bù (chưa hoàn thành)";
            }

            if ($trangThai === 'chua_dat') {
                return "Chưa đạt chỉ tiêu";
            }

            return implode("\n", $warnings);
        }

        if ($phanCong->loai_kpi === 'da_chi_tieu') {
            $baoCao = $chiTiet->baoCaoCongViec->where('trangthai_duyet', 'da_duyet')
                        ->where('ngay_thuc_hien', '<=', $ketThuc);
            $check = $this->dieuKienKPIService->ktDieuKienTong($chiTiet, $baoCao);
            $details = $check['details'] ?? [];

            $loi = [];

            foreach ($details as $dk) {

                if ($dk['dat']) continue;

                if ($dk['pham_vi'] === 'bao_cao') {
                    $ngay = collect($dk['ds_loi'])->unique()->implode(', ');
                    $loi[] = "[{$dk['ten']}] tại: {$ngay}";
                } else {
                    $loi[] = "[{$dk['ten']}] {$dk['actual']}/{$dk['target']}";
                }
            }

            if (!empty($loi)) {
                $warnings[] = "Chưa đạt đa chỉ tiêu:";
                $warnings[] = implode("\n", $loi);
            }

            if ($trangThai === 'dang_no') {
                $warnings[] = "Đạt ngưỡng bù";
            }

            return implode("\n", $warnings);
        }

        if ($phanCong->loai_kpi === 'nang_cao') {

            $check = $this->tanSuatKPIService->ktTanSuatThang($phanCong, $chiTiet);

            if (!$check['dat']) {

                $thangThieu = [];

                foreach ($check['thieu_thang'] as $item) {
                    $thangThieu[] = implode(', ', array_map(function ($m) {
                        return Carbon::parse($m)->format('m/Y');
                    }, $item['chu_ky']));
                }

                if ($trangThai === 'dang_no') {
                    $warnings[] = "Đạt ngưỡng bù (thiếu chu kỳ: " . implode(' | ', $thangThieu) . ")";
                } else {
                    $warnings[] = "Chưa đạt chu kỳ: " . implode(' | ', $thangThieu);
                }

            } else {
                if (!$quaHan) {
                    $warnings[] = "Đang đúng tiến độ";
                }
            }

            return implode("\n", $warnings);
        }

        return implode("\n", $warnings);
    }
   
}