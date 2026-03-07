<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChucVu extends Model
{
    protected $table = 'chuc_vu';
    protected $fillable = ['ten_chuc_vu'];
    public function users()
    {
        return $this->hasMany(User::class, 'chuc_vu_id');
    }
}
