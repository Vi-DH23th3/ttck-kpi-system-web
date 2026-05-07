<?php

namespace App\Services\Tan_suat;

use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class TanSuatKPIService{
    public function ktTanSuatThang($phanCong, $chiTiet)
    {
       
        if (!$phanCong->so_lan_toi_thieu_thang || !$phanCong->chu_ky_thang) {
            return [
                'dat' => true,
                'thieu_thang' => [],
                'chi_tiet' => []
            ];
        }
        $now = Carbon::now();
        $nowMonth = $now->copy()->startOfMonth();
        $baoCaoList = $chiTiet->baoCaoCongViec
                ->where('trangthai_duyet', 'da_duyet');
                    
        //Tạo danh sách tháng
        $startDate = Carbon::parse($phanCong->ngay_bat_dau);
        $start = $startDate->copy()->startOfMonth();
        $end = Carbon::parse($phanCong->ngay_ket_thuc)->startOfMonth();
        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }
        //Chỉ lấy tháng <= hiện tại
        $months = array_filter($months, function ($m) use ($now) {
            return Carbon::createFromFormat('Y-m', $m)->lte($now);
        });
        $months = array_values($months);
        //Group báo cáo theo tháng
        $group = $baoCaoList->groupBy(function ($item) {
            return Carbon::parse($item->ngay_thuc_hien)->format('Y-m');
        });
        //Đếm số báo cáo hợp lệ
        $tongThang = [];
        foreach ($months as $m) {

            if (!isset($group[$m])) {
                $tongThang[$m] = 0;
                continue;
            }

            $soLan = $group[$m]->filter(fn($bc) => $bc->tien_do_thuc >= 100)->count();
            $tongThang[$m] = $soLan;
        }
        $chuKy = $phanCong->chu_ky_thang;
        $soLan = $phanCong->so_lan_toi_thieu_thang;
        $thieuThang = [];
        if (count($months) < $chuKy) {
            return [
                'dat' => false,
                'thieu_thang' => [],
                'chi_tiet' => $tongThang
            ];
        }
        for ($i = 0; $i < count($months); $i += $chuKy){
            $thangHienTai = array_slice($months, $i, $chuKy);
            $lastMonth = Carbon::createFromFormat('Y-m', end($thangHienTai));
            //Chu kỳ tương lai
            if ($lastMonth->gt($nowMonth)) {
                continue;
            }
            //Chu kỳ đang diễn ra
            if ($lastMonth->eq($nowMonth) && $now->lt($phanCong->ngay_ket_thuc)) {
                continue;
            }
            //Chu kỳ đã kết thúc
            $sum = 0;
            foreach ($thangHienTai as $m) {
                $sum += $tongThang[$m];
            }
            if ($sum < $soLan) {
                $thieuThang[] = [
                    'chu_ky' => $thangHienTai,
                    'tong' => $sum,
                    'yeu_cau' => $soLan
                ];
            }
        }
        return [
            'dat' => empty($thieuThang),
            'thieu_thang' => $thieuThang,
            'chi_tiet' => $tongThang
        ];
        
    }
}