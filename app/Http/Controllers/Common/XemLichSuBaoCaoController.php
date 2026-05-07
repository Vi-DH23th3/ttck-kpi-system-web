<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\BaoCaoCongViec;
use App\Models\ChiTietPhanCong;
use App\Models\PhanCongCongViec;
use App\Services\Cot_loi\DanhGiaTienDoService;
use Carbon\Carbon;

class XemLichSuBaoCaoController extends Controller
{
    public function xemLichSuBaoCao($idpc, DanhGiaTienDoService $danhGiaTienDoService)
    {
        $cv = ChiTietPhanCong::with(['nguoiDuocGiao', 'phanCong.thuVienKPI',  'baoCaoCongViec'])
            ->findOrFail($idpc);
        $user_id = $cv->nguoiDuocGiao->id;
        $dulieu = $danhGiaTienDoService->danhGiaTienDo($cv);
            $cv->ten_nv = $cv->nguoiDuocGiao->name ?? 'N/A';
            $cv->ten_kpi = $dulieu['ten_kpi'];
            $cv->hieu_suat = $dulieu['hieu_suat'];
            $cv->tien_do = $dulieu['tien_do'];
            $cv->danh_gia = $dulieu['danh_gia'];
            $cv->ngay_bat_dau = $cv->ngay_bat_dau;
            $cv->deadline = $dulieu['deadline'];
            $cv->trang_thai_tinh = $dulieu['trang_thai_tinh'];
            $cv->canh_bao = $dulieu['canh_bao'];
        
        $baoCao = BaoCaoCongViec::where('chi_tiet_phan_cong_id', $idpc)
                    // ->where('user_id', $user_id)
                    ->orderBy('ngay_thuc_hien', 'desc')
                    ->get()
                    ->groupBy(function ($item) {
                        return Carbon::parse($item->ngay_thuc_hien)->format('Y-m');
                    });

        return view('qlcongviec.xem_lich_su_bao_cao', compact('cv', 'baoCao'));
    }
}
