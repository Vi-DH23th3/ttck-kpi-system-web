<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanhMucCongViec extends Model
{
    use SoftDeletes;
     protected $table = 'danhmuc_cong_viec';
     protected $fillable = ['ten_cong_viec', 'don_vi_id'];
     public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id')->withTrashed();
    }
    public function thuVienKPI()
    {
        return $this->hasMany(ThuVienKPI::class, 'dm_cv_id');
    }
    
}
