<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonVi extends Model
{
    use SoftDeletes;
    protected $table = 'don_vi';
    protected $fillable = ['ten_don_vi'];

    public function users()
    {
        return $this->hasMany(User::class, 'don_vi_id');
    }
    public function danhMuc()
    {
        return $this->hasMany(DanhMucCongViec::class, 'don_vi_id');
    }
}
