<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaoCaoCongViec extends Model
{
    protected $table = 'bao_cao_cong_viec';
    protected $fillable = ['user_id', 'kpi_mau_id', 'ngay_thuc_hien', 'trang_thai_bao_cao', 'file_minh_chung', 'ghi_chu', 'user_duyet_id', 'trangthai_duyet'];

}
