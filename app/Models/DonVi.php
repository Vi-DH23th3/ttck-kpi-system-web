<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonVi extends Model
{
    public function cha()
    {
        return $this->belongsTo(DonVi::class, 'id_cha');
    }

    public function con()
    {
        return $this->hasMany(DonVi::class, 'id_cha');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'don_vi_id');
    }
}
