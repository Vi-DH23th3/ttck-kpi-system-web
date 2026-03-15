<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaoCaoCongViec extends Model
{
    protected $table = 'bao_cao_cong_viec';
    protected $fillable = ['user_id', 'phan_cong_id', 'kpi_mau_id', 'ngay_thuc_hien', 'trang_thai_bao_cao', 'file_minh_chung', 'ghi_chu', 'user_duyet_id', 'trangthai_duyet', 'tien_do_thuc', 'so_lan_bao_cao'];
    public function phanCong() {
        return $this->belongsTo(PhanCongCongViec::class, 'phan_cong_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function userDuyet() {
        return $this->belongsTo(User::class, 'user_duyet_id');
    }
    public function thuVienKPI(){
        return $this->belongsTo(ThuVienKPI::class, 'kpi_mau_id');
    }
}
