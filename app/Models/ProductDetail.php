<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    // use HasFactory;
    protected $table = 'tchitietsp';
    public $timestamps = false;

    public function product() : BelongsTo {
        return $this->belongsTo(Product::class, 'maSanPham', 'maSanPham');
    }
    public function size() : BelongsTo {
        return $this->belongsTo(Size::class, 'maKichThuoc', 'maKichThuoc');
    }
    public function cchd() : BelongsTo {
        return $this->belongsTo(CCHD::class, 'maCCHD', 'maCCHD');
    }
    public function watchStrap() : BelongsTo {
        return $this->belongsTo(WatchShape::class, 'maDayDeo', 'maDayDeo');
    }
    public function material() : BelongsTo {
        return $this->belongsTo(Material::class, 'maCL', 'maCL');
    }
    public function watchShape() : BelongsTo {
        return $this->belongsTo(WatchShape::class, 'maHinhDang', 'maHinhDang');
    }
}
