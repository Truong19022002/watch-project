<?php


namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    // use HasFactory;
    protected $table = 'ttaikhoan';
    public $timestamps = false;
    protected $primaryKey = 'idUser';
    protected $fillable = [
        'idUser', 
        'username', 
        'firstName', 
        'lastName', 
        'lastName', 
        'email', 
        'contact',
        'maChucVu', 
        'password', 
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->attributes['idUser'];
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
