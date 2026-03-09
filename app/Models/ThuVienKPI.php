<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThuVienKPI extends Model
{
    protected $table = 'thu_vien_kpi';
    protected $fillable = ['ten_kpi', 'chi_tieu', 'don_vi', 'chu_ky', 'dm_cv_id', 'nam_hoc_id', 'ghi_chu'];
    public function danhMuc()
    {
        return $this->belongsTo(DanhMucCongViec::class, 'dm_cv_id');
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
