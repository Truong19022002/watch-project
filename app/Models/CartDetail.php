<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartDetail extends Model
{
    // use HasFactory;
    protected $table = 'tchitietgh';
    protected $primaryKey = 'maChiTietGH';
    public $timestamps = false;
    protected $fillable = [
        'maChiTietGH', 
        'maGioHang', 
        'maSanPham',
        'ngayThemSP',
        'soLuongSP'
    ];

    public function cart() : BelongsTo {
        return $this->belongsTo(Cart::class, 'maGioHang', 'maGioHang');
    }
    public function product() : BelongsTo {
        return $this->belongsTo(Product::class, 'maSanPham', 'maSanPham');
    }
}
