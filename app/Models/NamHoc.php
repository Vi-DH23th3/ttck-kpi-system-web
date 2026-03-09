<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamHoc extends Model
{
    protected $table = 'nam_hoc';
    protected $fillable = ['ten_nam_hoc', 'ngay_bat_dau', 'ngay_ket_thuc'];

    public function thuVienKPI()
    {
        return $this->hasMany(ThuVienKPI::class, 'nam_hoc_id');
    }
    public function danhMuc()
    {
        return $this->hasMany(DanhMucCongViec::class, 'don_vi_id');
    }
}
