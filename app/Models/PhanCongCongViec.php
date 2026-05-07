<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhanCongCongViec extends Model
{
    use SoftDeletes;
    protected $table = 'phan_cong_cong_viec';
    protected $fillable = ['kpi_id', 'loai_kpi','so_lan_toi_thieu_thang','chu_ky_thang','dieu_kien_phu','cho_phep_bu','nguong_duoc_bu',  'ngay_bat_dau', 'ngay_ket_thuc', 'nam_hoc_id', 'ghi_chu', 'muc_do_uu_tien', 'user_phan_cong_id', 'trang_thai'];
    protected $casts = [
        'dieu_kien_phu' => 'array',
    ];
    public function thuVienKPI() //phân công cv n-1 kpi, kpi_id là khóa ngoại 
    {
        return $this->belongsTo(ThuVienKPI::class, 'kpi_id');
    }
    public function chiTietPhanCong()
    {
        return $this->hasMany(ChiTietPhanCong::class, 'phan_cong_id');
    }

    public function nguoiPhanCong() {
        return $this->belongsTo(User::class, 'user_phan_cong_id');
    }

    public function namHoc()
    {
        return $this->belongsTo(NamHoc::class, 'nam_hoc_id');
    }
}
