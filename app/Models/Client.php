<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Tymon\JWTAuth\Contracts\JWTSubject;
class Client extends Authenticatable implements JWTSubject 
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'tkhachhang';
    public $timestamps = false;
    protected $primaryKey = 'maKhachHang';

    // protected $fillable = [
    //     'maKhachHang', 
    //     'tenKhachHang', 
    //     'gioiTinh', 
    //     'diaChi', 
    //     'SDT', 
    //     'email', 
    //     'anhKH',
    //     'ghiChu', 
    //     'password', 
    // ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->attributes['maKhachHang'];
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}