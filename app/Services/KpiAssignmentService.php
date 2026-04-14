<?php

namespace App\Services;

use App\Models\DanhMucCongViec;
use App\Models\ThuVienKPI;
use App\Models\PhanCongCongViec;
use Illuminate\Support\Facades\Auth;

class KpiAssignmentService
{
    public function xuLyDanhMuc($request)
    {
        return DanhMucCongViec::firstOrCreate(
            [
                'ten_cong_viec' => $request->ten_kpi,
                'don_vi_id' => $request->donvi_id
            ],
            
        )->id;
    }
    public function xuLyKPI($request, $dmcvId, $namhocId)
    {
       return ThuVienKPI::firstOrCreate(
            [
                'ten_kpi'   => $request->ten_kpi,
                'dm_cv_id'  => $dmcvId,
                'chi_tieu'  => $request->chi_tieu,
                'don_vi'    => $request->don_vi,
                'chu_ky'    => $request->chu_ky,
            ],
            [
                'nam_hoc_id' => $namhocId,
            ]
        )->id;
    }

    public function giaoNhieuUser($userIds, $kpiId, $data)
    {
        $insert = [];
        foreach ($userIds as $user_id) {

            if (($data['loai_kpi'] ?? '') === 'don_gian') {
                $data['so_lan_toi_thieu_thang'] = null;
                $data['chu_ky_thang'] = null;
                $data['dieu_kien_phu'] = null;
                $data['nguong_duoc_bu'] = null;
                $data['cho_phep_bu'] = 0;
            }
            $exists = PhanCongCongViec::where('user_id', $user_id)
                ->where('kpi_id', $kpiId)
                ->where('trang_thai', '!=', 'da_hoan_thanh')
                ->with('nguoiDuocGiao')
                ->first();
            if ($exists) {
                    $ten = $exists->pluck('nguoiDuocGiao.name')->implode(', ');
                    throw new \Exception("Nhân viên ($ten) đang thực hiện KPI này");
            }
            $insert[] = [
                'user_id' => $user_id,
                'kpi_id' => $kpiId,

                'loai_kpi' => $data['loai_kpi'] ?? null,
                'chu_ky_thang' => $data['chu_ky_thang'] ?? null,
                'so_lan_toi_thieu_thang' => $data['so_lan_toi_thieu_thang'] ?? null,
                'dieu_kien_phu' => $data['dieu_kien_phu'] ?? null,
                'cho_phep_bu' => !empty($data['cho_phep_bu']) ? 1 : 0,
                'nguong_duoc_bu' => $data['nguong_duoc_bu'] ?? null,

                'ngay_bat_dau' => $data['ngay_bat_dau'],
                'ngay_ket_thuc' => $data['ngay_ket_thuc'],

                'trang_thai' => 'chua_bat_dau',
                'muc_do_uu_tien' => $data['muc_do'],
                'ghi_chu' => $data['ghi_chu'] ?? null,

                'user_phan_cong_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
                
        }
        PhanCongCongViec::insert($insert);
    }
}