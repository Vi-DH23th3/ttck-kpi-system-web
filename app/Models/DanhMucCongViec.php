<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucCongViec extends Model
{
     protected $table = 'danhmuc_cong_viec';
     protected $fillable = ['ten_cong_viec', 'don_vi_id'];
     public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id');
    }
    public function thuVienKPI()
    {
        return $this->hasMany(ThuVienKPI::class, 'dm_cv_id');
    }
    
}
