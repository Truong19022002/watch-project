<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // use HasFactory;
    protected $primaryKey = 'maSanPham';
    public $incrementing = false;
    protected $table = 'tsanpham';
    public $timestamps = false;

    public function productDetail() : HasMany {
        return $this->hasMany(ProductDetail::class, 'maSanPham', 'maSanPham');
    }
    public function detailBillSale() : HasMany {
        return $this->hasMany(DetailBillSale::class, 'maSanPham', 'maSanPham');
    }
    public function type() : BelongsTo {
        return $this->belongsTo(Type::class, 'maLoai', 'maLoai');
    }
    public function brand () : BelongsTo {
        return $this->belongsTo(Brand::class, 'maThuongHieu', 'maThuongHieu');
    }
}
