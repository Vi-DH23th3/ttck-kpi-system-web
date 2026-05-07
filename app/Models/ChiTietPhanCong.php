<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChiTietPhanCong extends Model
{
    use SoftDeletes;
    protected $table = 'chi_tiet_phan_cong';
    protected $fillable = ['phan_cong_id', 'user_id', 'trang_thai','thuc_te_dat_duoc', 'thong_bao'];

    public function baoCaoCongViec() //phân công công việc 1-n báo cáo công việc
    {
        return $this->hasMany(BaoCaoCongViec::class, 'chi_tiet_phan_cong_id');
    }
    public function nguoiDuocGiao() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function baoCaoMoiNhat()
    {
        return $this->hasOne(BaoCaoCongViec::class, 'chi_tiet_phan_cong_id')->latestOfMany();
    }
    public function phanCong()
    {
        return $this->belongsTo(PhanCongCongViec::class, 'phan_cong_id');
    }
}
