<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WatchShape extends Model
{
    // use HasFactory;
    protected $table = 'thinhdang';
    public $timestamps = false;

    public function productDetail() : HasMany {
        return $this->hasMany(ProductDetail::class, 'maHinhDang', 'maHinhDang');
    }
}
