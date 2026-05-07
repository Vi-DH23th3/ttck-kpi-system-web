<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaoCaoCongViec extends Model
{
    use SoftDeletes;
    protected $table = 'bao_cao_cong_viec';
    protected $fillable = ['user_id', 'chi_tiet_phan_cong_id', 'ngay_thuc_hien', 'file_minh_chung', 'ghi_chu', 'ly_do_tra_lai', 'user_duyet_id', 'trangthai_duyet', 'tien_do_thuc', 'gia_tri_thuc_te'];
    protected $casts = [
        'gia_tri_thuc_te' => 'array',
    ];
    public function chiTietPhanCong() {
        return $this->belongsTo(ChiTietPhanCong::class, 'chi_tiet_phan_cong_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function userDuyet() {
        return $this->belongsTo(User::class, 'user_duyet_id');
    }
}
