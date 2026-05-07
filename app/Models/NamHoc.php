<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NamHoc extends Model
{
    use SoftDeletes;
    protected $table = 'nam_hoc';
    protected $fillable = ['ten_nam_hoc', 'ngay_bat_dau', 'ngay_ket_thuc'];

    public function phanCong()
    {
        return $this->hasMany(PhanCongCongViec::class, 'nam_hoc_id');
    }
}
