<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $policies = [
    //     \App\Models\User::class => \App\Policies\UserPolicy::class,
    // ];
    protected $fillable = [
        'name',
        'email',
        'password',
        'chuc_vu_id',
        'don_vi_id',
        'role',
        'avatar',
        'trang_thai',
        'must_change_password',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function donVi()
    {
        return $this->belongsTo(DonVi::class, 'don_vi_id')->withTrashed();
    }
    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }
    public function baoCaoCongViec()
    {
        return $this->hasMany(BaoCaoCongViec::class, 'user_id');
    }
     public function nguoiDuocGiao() {
        return $this->hasMany(ChiTietPhanCong::class, 'user_id');
    }
    public function nguoiPhanCong() {
        return $this->hasMany(PhanCongCongViec::class, 'user_phan_cong_id');
    }
    // public function phanCong()
    // {
    //     return $this->hasMany(PhanCongCongViec::class, 'user_id');
    // }
}
