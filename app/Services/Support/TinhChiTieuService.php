<?php

namespace App\Services\Support;

use Carbon\Carbon;
use Illuminate\Support\Str;

class TinhChiTieuService
{
    public function tinhTongChiTieu($phanCong)
    {
        $kpi = $phanCong->thuVienKPI;
        $loai = $phanCong->loai_kpi;
        $chuKy = Str::slug($kpi->chu_ky);
        $chiTieu = $kpi->chi_tieu ?? 0;

        if ($loai !== 'don_gian') {
            return $chiTieu; 
        }

        $start = Carbon::parse($phanCong->ngay_bat_dau);
        $end = Carbon::parse($phanCong->ngay_ket_thuc);

        switch ($chuKy) {

            case 'ngay':
                $soNgay = $this->demNgayLamViec($start, $end);
                return $chiTieu * $soNgay;

            case 'thang':
                $soThang = $this->demSoThang($start, $end);
                return $chiTieu * $soThang;

            case 'quy':
                $soThang = $this->demSoThang($start, $end);
                $soQuy = ceil($soThang / 3);
                return $chiTieu * $soQuy;

            case 'nam':
                return $chiTieu;

            default:
                return $chiTieu;
        }
    }

    private function demSoThang($start, $end)
    {
        return $start->copy()->startOfMonth()
            ->diffInMonths($end->copy()->startOfMonth()) + 1;
    }

    private function demNgayLamViec($start, $end)
    {
        $count = 0;
        while ($start <= $end) {
            if (!$start->isWeekend()) {
                $count++;
            }
            $start->addDay();
        }
        return $count;
    }
}