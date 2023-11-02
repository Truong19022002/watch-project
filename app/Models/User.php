<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens;
    // use HasFactory;
    protected $table = 'tkhachhang';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'Email',
        'Password',
    ];
}
