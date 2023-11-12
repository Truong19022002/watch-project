<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WishlistDetail extends Model
{
    protected $table = 'wishlist';
    public $timestamps = false;
    public $incrementing = false;

    public function client() : BelongsTo {
        return $this->belongsTo(Client::class, 'maKhachHang', 'maKhachHang');
    }
    public function product() : BelongsTo {
        return $this->belongsTo(Product::class, 'maSanPham', 'maSanPham');
    }
    protected $fillable = ['id_wishlist', 'maKhachHang', 'maSanPham', 'date_add'];
}

