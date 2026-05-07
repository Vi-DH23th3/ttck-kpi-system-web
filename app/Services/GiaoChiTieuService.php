<?php

namespace App\Services;

use App\Models\DanhMucCongViec;
use App\Models\ThuVienKPI;
use App\Models\PhanCongCongViec;
use App\Models\ChiTietPhanCong;
use Illuminate\Support\Facades\Auth;

class GiaoChiTieuService
{
    public function xuLyDanhMuc($request)
    {
        return DanhMucCongViec::firstOrCreate(
            [
                'ten_cong_viec' => $request->ten_dmcv,
                'don_vi_id' => Auth::user()->don_vi_id
            ],
            
        )->id;
    }
    public function xuLyKPI($request, $dmcvId)
    {
        $chiTieu = $request->chi_tieu;
        $donViTinh = $request->don_vi;
        $chuKy = $request->chu_ky;
        if($request->loai_kpi === 'da_chi_tieu'){
            $chiTieu = null;
            $donViTinh = 'multi';
            $chuKy = 'multi';
        }
        return ThuVienKPI::firstOrCreate(

            [
                'ten_kpi'   => $request->ten_kpi,
                'dm_cv_id'  => $dmcvId,
                'chi_tieu'  => $chiTieu,
                'don_vi'    => $donViTinh,
                'chu_ky'    => $chuKy,
            ]
        )->id;
    }
    public function luuPhanCong($kpiId, $data){
        return PhanCongCongViec::create([
            'kpi_id' => $kpiId,
                'loai_kpi' => $data['loai_kpi'] ?? null,
                'chu_ky_thang' => $data['chu_ky_thang'] ?? null,
                'so_lan_toi_thieu_thang' => $data['so_lan_toi_thieu_thang'] ?? null,
                'dieu_kien_phu' => $data['dieu_kien_phu'] ?? null,
                'cho_phep_bu' => !empty($data['cho_phep_bu']) ? 1 : 0,
                'nguong_duoc_bu' => $data['nguong_duoc_bu'] ?? null,
                'ngay_bat_dau' => $data['ngay_bat_dau'],
                'ngay_ket_thuc' => $data['ngay_ket_thuc'],
                'nam_hoc_id' => $data['nam_hoc_id'],
                'trang_thai' => $data['trang_thai'],
                'muc_do_uu_tien' => $data['muc_do'],
                'ghi_chu' => $data['ghi_chu'] ?? null,
                'user_phan_cong_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
        ])->id;
    }
    public function giaoNhieuUser($phanCongId, $userIds, $trang_thai)
    {
        foreach ($userIds as $user_id) {
            $exists = ChiTietPhanCong::where('user_id', $user_id)
                ->where('phan_cong_id', $phanCongId)
                ->first();
            if ($exists) {
                $ten = $exists->nguoiDuocGiao->name ?? '';
                throw new \Exception("Nhân viên ($ten) đã được giao KPI này");
            }
            ChiTietPhanCong::create([
                'phan_cong_id' => $phanCongId,
                'user_id' => $user_id,
                'trang_thai' => $trang_thai,
                'thuc_te_dat_duoc' => 0,
                
            ]);
        }
    }

}