<?php

namespace App\Services\Bao_cao;

use App\Models\BaoCaoCongViec;

class ThongKeChartService{
    public function thongKeBCTheoThang($listIdChiTietPhanCong)
    {
        $baoCaoThang = BaoCaoCongViec::selectRaw("
                YEAR(ngay_thuc_hien) as nam,
                MONTH(ngay_thuc_hien) as thang_so,
                DATE_FORMAT(ngay_thuc_hien, '%m-%Y') as thang,
                COUNT(*) as so_luong
            ")
            ->whereIn('chi_tiet_phan_cong_id', $listIdChiTietPhanCong)
            ->where('trangthai_duyet', 'da_duyet')
            ->groupBy('nam', 'thang_so', 'thang')
            ->orderBy('nam')
            ->orderBy('thang_so')
            ->get();

        return $baoCaoThang;
    }
}