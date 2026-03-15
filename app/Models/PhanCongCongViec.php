<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanCongCongViec extends Model
{
    protected $table = 'phan_cong_cong_viec';
    protected $fillable = ['user_id', 'kpi_id', 'ngay_bat_dau', 'ngay_ket_thuc', 'trang_thai', 'ghi_chu', 'muc_do_uu_tien', 'user_phan_cong_id', 'thuc_te_dat_duoc', 'so_lan_bao_cao'];
    public function thuVienKPI() //phân công cv n-1 kpi, kpi_id là khóa ngoại 
    {
        return $this->belongsTo(ThuVienKPI::class, 'kpi_id');
    }
    public function baoCaoCongViec() //phân công công việc 1-n báo cáo công việc
    {
        return $this->hasMany(BaoCaoCongViec::class, 'phan_cong_id');
    }
    public function nguoiDuocGiao() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function nguoiPhanCong() {
        return $this->belongsTo(User::class, 'user_phan_cong_id');
    }
}
