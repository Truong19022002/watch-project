<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends Model
{
    // use HasFactory;
    protected $table = 'tkichthuoc';
    public $timestamps = false;

    public function productDetail() : HasMany {
        return $this->hasMany(ProductDetail::class, 'maKichThuoc', 'maKichThuoc');
    }
}
