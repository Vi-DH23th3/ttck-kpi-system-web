<?php

namespace App\Services\Canh_bao;

use App\Notifications\CongViecNotification;
use App\Services\Dieu_kien\DieuKienKPIService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ThongBaoKPIService
{
    protected $dieuKienKPIService;

    public function __construct(DieuKienKPIService $dieuKienKPIService)
    {
        $this->dieuKienKPIService = $dieuKienKPIService;
    }

    public function thongBao($dscongviec)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        foreach ($dscongviec as $dscv) {

            $phanCong = $dscv->phanCong ?? null; 
            $kpi = $phanCong?->thuVienKPI;
            
            $ngayKetThuc = Carbon::parse($phanCong->ngay_ket_thuc);
            $soNgayConLai = $now->diffInDays($ngayKetThuc, false);
            if (!$phanCong || !$kpi) continue;
            if($phanCong->loai_kpi === 'nang_cao'){
                $thangHienTai = now()->format('Y-m');
                $thangUpdate = $dscv->updated_at  ? Carbon::parse($dscv->updated_at)->format('Y-m')  : null;

                if ($thangUpdate != $thangHienTai) {
                    $dscv->thong_bao = 0;
                }
            }
            if ($dscv->thong_bao == 1 || $dscv->trang_thai == 'da_hoan_thanh') {
                continue;
            }
            if ($soNgayConLai > 5) {
                DB::table('chi_tiet_phan_cong')->where('id', $dscv->id)->update(['thong_bao' => 0]);
            }
            $tinnhan = null;
            $title = null;

            if ($phanCong->loai_kpi == 'nang_cao' && $phanCong->so_lan_toi_thieu_thang) {

                $soLanThangNay = $dscv->baoCaoCongViec
                    ->where('ngay_thuc_hien', '>=', $startOfMonth)
                    ->count();

                if ($soLanThangNay < $phanCong->so_lan_toi_thieu_thang && $now->diffInDays($endOfMonth) <= 5) {
                    $title = "Cảnh báo thời hạn tần suất";
                    $tinnhan = "KPI '{$kpi->ten_kpi}' tháng này chưa đạt tần suất tối thiểu.";
                }
            }
            
            if (!$tinnhan && $dscv->trang_thai != 'da_hoan_thanh') {
                if ($soNgayConLai >= 0 && $soNgayConLai <= 5) {
                    $title = "Cảnh báo thời hạn";
                    $tinnhan = "Công việc '{$kpi->ten_kpi}' sắp đến hạn (còn $soNgayConLai ngày).";
                }
            }
            if ($tinnhan) {
                $dscv->nguoiDuocGiao?->notify(
                    new CongViecNotification( $title, $tinnhan, route('profile.index'))
                );
                DB::table('chi_tiet_phan_cong')->where('id', $dscv->id)->update(['thong_bao' => 1]);
                $dscv->thong_bao = 1;
            }
        }
    }
}