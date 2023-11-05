<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    // use HasFactory;
    protected $table = 'tgiohang';
    public $timestamps = false;

    public function cartDetail() : HasMany {
        return $this->hasMany(CartDetail::class, 'maGioHang', 'maGioHang');
    }
    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'maKhachHang', 'maKhachHang');
    }
}
