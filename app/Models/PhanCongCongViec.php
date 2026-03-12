<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanCongCongViec extends Model
{
    protected $table = 'phan_cong_cong_viec';
    protected $fillable = ['user_id', 'kpi_id', 'ngay_bat_dau', 'ngay_ket_thuc', 'trang_thai', 'ghi_chu', 'muc_do_uu_tien', 'user_phan_cong_id', 'thuc_te_dat_duoc'];
    public function thuVienKPI()
    {
        return $this->belongsTo(ThuVienKPI::class, 'kpi_id');
    }
    public function baoCaoCongViec()
    {
        return $this->hasMany(BaoCaoCongViec::class, 'user_id');
    }
    public function nguoiGiao() {
    return $this->belongsTo(User::class, 'user_id');
}
}
