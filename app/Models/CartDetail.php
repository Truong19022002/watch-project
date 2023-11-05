<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    // use HasFactory;
    protected $table = 'tchitietgh';
    public $timestamps = false;

    public function cart() : BelongsTo {
        return $this->belongsTo(Cart::class, 'maGioHang', 'maGioHang');
    }
    public function product() : BelongsTo {
        return $this->belongsTo(Product::class, 'maSanPham', 'maSanPham');
    }
}
