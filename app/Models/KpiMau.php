<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiMau extends Model
{
    public function danhMuc()
    {
        return $this->belongsTo(DanhMucKpi::class, 'id_dm_kpi');
    }

    public function namHoc()
    {
        return $this->belongsTo(NamHoc::class, 'nam_hoc_id');
    }

    public function phanCong()
    {
        return $this->hasMany(PhanCongCongViec::class, 'kpi_mau_id');
    }
}
