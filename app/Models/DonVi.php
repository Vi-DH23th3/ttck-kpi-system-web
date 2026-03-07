<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonVi extends Model
{
    protected $table = 'don_vi';
    protected $fillable = ['ten_don_vi', 'id_cha'];

    public function users()
    {
        return $this->hasMany(User::class, 'don_vi_id');
    }
}
