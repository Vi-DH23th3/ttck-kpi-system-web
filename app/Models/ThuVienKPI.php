<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThuVienKPI extends Model
{
    use SoftDeletes;
    protected $table = 'thu_vien_kpi';
    protected $fillable = ['ten_kpi', 'chi_tieu', 'don_vi', 'chu_ky', 'dm_cv_id', 'ghi_chu'];
    public function danhMuc()
    {
        return $this->belongsTo(DanhMucCongViec::class, 'dm_cv_id')->withTrashed();
    }

    public function phanCong()
    {
        return $this->hasMany(PhanCongCongViec::class, 'kpi_id');
    }
}
