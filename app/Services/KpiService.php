<?php

namespace App\Services;

use App\Models\DanhMucCongViec;
use App\Models\ThuVienKPI;
use App\Models\BaoCaoCongViec;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KpiService
{
// thuật toán này là "Sliding Window" (Cửa sổ trượt):"Thuật toán sử dụng kỹ thuật cửa sổ trượt để kiểm tra tính liên tục của tần suất. 
// Biến $i$ xác định điểm bắt đầu của chu kỳ, và biến $j$ thực hiện quét qua các tháng trong chu kỳ đó để tính tổng số lượng báo cáo. 
// Nếu tại bất kỳ 'cửa sổ' thời gian nào mà tổng số lượng không đạt yêu cầu, hệ thống sẽ ghi nhận vi phạm KPI."
   public function ktTanSuatThang($phanCong)
    {
        $now = Carbon::now()->startOfMonth();
        if (!$phanCong->so_lan_toi_thieu_thang || !$phanCong->chu_ky_thang) {
            return [
                'dat' => true,
                'dang_trong_chu_ky' => false
            ];
        }
        $baoCaoList = BaoCaoCongViec::where('phan_cong_id', $phanCong->id)
            ->where('trangthai_duyet', 'da_duyet')
            ->where('duoc_tinh_kpi', 1)
            ->get();

        // Tạo danh sách tháng
        $startDate = Carbon::parse($phanCong->ngay_bat_dau);
        $start = $startDate->copy()->startOfMonth();

        if ($startDate->day > 1) {
            $start->addMonth();
        }
        $end = Carbon::parse($phanCong->ngay_ket_thuc)->startOfMonth();

        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }
        
        $months = array_filter($months, function ($m) use ($now) {
            return Carbon::createFromFormat('Y-m', $m)->lte($now);
        });
        $months = array_values($months);
        // Group báo cáo theo tháng
        $group = $baoCaoList->groupBy(function ($item) {
            return Carbon::parse($item->ngay_thuc_hien)->format('Y-m');
        });
        // Đếm số lần mỗi tháng
        $tongThang = [];
        foreach ($months as $m) {
            $tongThang[$m] = isset($group[$m]) ? $group[$m]->count() : 0;
        }
        $chuKy = $phanCong->chu_ky_thang;
        $soLan = $phanCong->so_lan_toi_thieu_thang;
        $now = Carbon::now()->startOfMonth();
        $dangTrongChuKy = false;
        //CHỈ check chu kỳ gần nhất
        for ($i = count($months) - $chuKy; $i >= 0; $i--) {
            $lastMonth = Carbon::createFromFormat('Y-m', $months[$i + $chuKy - 1]);
            // Nếu chu kỳ chưa kết thúc → bỏ qua
            if ($lastMonth->gte($now)) {
                $dangTrongChuKy = true;
                continue;
            }
            // Tính tổng trong chu kỳ
            $sum = 0;
            for ($j = 0; $j < $chuKy; $j++) {
                $month = $months[$i + $j];
                $sum += $tongThang[$month];
            }
            // Không đạt
            if ($sum < $soLan) {
                return [
                    'dat' => false,
                    'dang_trong_chu_ky' => false
                ];
            }
            // Đạt → dừng (chỉ check chu kỳ gần nhất)
            break;
        }
        return [
            'dat' => true,
            'dang_trong_chu_ky' => $dangTrongChuKy
        ];
    }
    public function ktDieuKienTheoThang($phanCong)
    {
        if (!$phanCong->dieu_kien_phu) return true;
        $rules = is_array($phanCong->dieu_kien_phu) ? $phanCong->dieu_kien_phu : json_decode($phanCong->dieu_kien_phu, true) ?? [];
        $baoCaoList = BaoCaoCongViec::where('phan_cong_id', $phanCong->id)
            ->where('trangthai_duyet', 'da_duyet')
            ->where('duoc_tinh_kpi', 1)
            ->get();
        if ($baoCaoList->isEmpty()) {
            return null; 
        }
        $group = $baoCaoList->groupBy(function ($item) {
            return Carbon::parse($item->ngay_thuc_hien)->format('Y-m');
        });
        foreach ($group as $month => $list) {
            $datTrongThang = false;
            foreach ($list as $bc) {
                $values = is_array($bc->gia_tri_thuc_te)
                    ? $bc->gia_tri_thuc_te
                    : json_decode($bc->gia_tri_thuc_te, true) ?? [];
                $ok = true;
                foreach ($rules as $key => $required) {
                    if (!isset($values[$key]) || (int)$values[$key] < (int)$required) {
                        $ok = false;
                        break;
                    }
                }
                if ($ok) {
                    $datTrongThang = true;
                    break;
                }
            }
            if (!$datTrongThang) {
                return false;
            }
        }
        return true;
    }
    public function laySoThangBaoCao($phanCongId){
        $baoCaoList = BaoCaoCongViec::where('phan_cong_id', $phanCongId)
            ->where('trangthai_duyet', 'da_duyet')
            ->where('duoc_tinh_kpi', 1)
            ->get();
        $soThangDuyNhat = $baoCaoList->map(function ($item) {
            return Carbon::parse($item->ngay_thuc_hien)->format('Y-m');
        })->unique()->count();

        return $soThangDuyNhat;
    }
    public function layThangQuyDinh($star, $end)
    {
        $start = Carbon::parse($star)->startOfMonth();;
        $end = Carbon::parse($end)->startOfMonth();;
        return $start->diffInMonths($end) + 1;
    }
    //kiểm tra xem có được phép bù KPI không
    // public function ktDuocBu($phanCong)
    // {
    //     if (!$phanCong->cho_phep_bu || !$phanCong->nguong_duoc_bu) {
    //         return false;
    //     }

    //     $phantram = ($phanCong->thuc_te_dat_duoc / $phanCong->thuVienKPI->chi_tieu) * 100;

    //     return $phantram >= $phanCong->nguong_duoc_bu;
    // }
    // public function capNhatTrangThai($cv)
    // {
    //     $now = now();
    //     $thucTe = $cv->thuc_te_dat_duoc;
    //     $chiTieu = $cv->thuVienKPI->chi_tieu;
    //     $coTanSuat = $cv->chu_ky_thang && $cv->so_lan_toi_thieu_thang;
    //     $checkTanSuat = $coTanSuat ? $this->ktTanSuatThang($cv) : null;
    //     $checkDKP = $this->ktDieuKienTheoThang($cv);
    //     $datSoLuong = $thucTe >= $chiTieu;
    //     $datTanSuat = !$coTanSuat || ($checkTanSuat['dat'] ?? true);
    //     $datDKP = $checkDKP !== false;
        
    //     //chưa hết hạn
    //     if ($now < $cv->ngay_ket_thuc) {
    //         return 'dang_thuc_hien';
    //     }

    //     //đã hết hạn và đã đủ đk
    //     if ($datSoLuong && $datTanSuat && $datDKP) {
    //         return 'da_hoan_thanh';
    //     }
    //     //kt kpi đc bù
    //     if ($cv->cho_phep_bu && $cv->nguong_duoc_bu) {
    //         $percent = $chiTieu > 0 ? ($thucTe / $chiTieu) * 100 : 0;
    //         if ($percent >= $cv->nguong_duoc_bu) {
    //             return 'dang_no';
    //         }
    //     }
    //     return 'chua_dat';
    // }
    public function capNhatTrangThai($cv)
    {
        $now = now();
        $thucTe = $cv->thuc_te_dat_duoc;
        $chiTieu = $cv->thuVienKPI->chi_tieu;
        $coTanSuat = $cv->chu_ky_thang && $cv->so_lan_toi_thieu_thang;
        $checkTanSuat = $coTanSuat ? $this->ktTanSuatThang($cv) : null;
        $checkDKP = $this->ktDieuKienTheoThang($cv);
        $datSoLuong = $thucTe >= $chiTieu;
        $datTanSuat = !$coTanSuat || ($checkTanSuat['dat'] ?? true);
        $datDKP = $checkDKP !== false;
        //trường hợp không có tần suất
        if (!$coTanSuat) {
            if ($datSoLuong && $datDKP) {
                return 'da_hoan_thanh'; 
            }
            if ($cv->cho_phep_bu && $cv->nguong_duoc_bu) {
                $percent = $chiTieu > 0 ? ($thucTe / $chiTieu) * 100 : 0;
                if ($percent >= $cv->nguong_duoc_bu) {
                    return 'dang_no';
                }
            }
            return 'dang_thuc_hien';
        }
        //trường hợp có tần suất
        // chưa hết hạn thì luôn đang thực hiện
        if ($now < $cv->ngay_ket_thuc) {
            return 'dang_thuc_hien';
        }
        // hết hạn → check full
        if ($datSoLuong && $datTanSuat && $datDKP) {
            return 'da_hoan_thanh';
        }
        // xét bù
        if ($cv->cho_phep_bu && $cv->nguong_duoc_bu) {
            $percent = $chiTieu > 0 ? ($thucTe / $chiTieu) * 100 : 0;
            if ($percent >= $cv->nguong_duoc_bu) {
                return 'dang_no';
            }
        }
        return 'chua_dat';
    }
    public function locTheoTrangThai($dulieu, $trangThai)
    {
        return $dulieu->filter(function ($cv) use ($trangThai) {
            return $this->capNhatTrangThai($cv) === $trangThai;
        });
    }
    public function taoCanhBao($cv)
    {
        $warnings = [];
        $coTanSuat = $cv->chu_ky_thang && $cv->so_lan_toi_thieu_thang;
        if ($coTanSuat) {
            $checkTanSuat = $this->ktTanSuatThang($cv);
            if ($checkTanSuat['dang_trong_chu_ky']) {
                $warnings[] = "Đang trong chu kỳ";
            } elseif ($checkTanSuat['dat'] === false) { 
                $warnings[] = "Không đủ tần suất";
            }
        }
        $checkDKP = $this->ktDieuKienTheoThang($cv);
        if ($checkDKP === null) {
            $warnings[] = "Chưa có dữ liệu điều kiện phụ";
        } elseif ($checkDKP === false) {
            $warnings[] = "Điều kiện phụ chưa đạt";
        }
        // sắp hết hạn
        if (now()->diffInDays($cv->ngay_ket_thuc, false) <= 7 && now() <= $cv->ngay_ket_thuc && $cv->trang_thai !== 'da_hoan_thanh') {
            $warnings[] = "Sắp hết hạn";
        }
        // đạt sớm (quan trọng)
        if ($cv->thuc_te_dat_duoc >= $cv->thuVienKPI->chi_tieu  && now() < $cv->ngay_ket_thuc) {
            $warnings[] = "Đã đạt chỉ tiêu (sớm)";
        }
        if (empty($warnings)) {
            $warnings[] = "Ổn định";
        }
        return implode(' | ', $warnings);
    }
}