<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ImageSP extends Model
{
    protected $table = 'tanhctsp';
    public $timestamps = false;
    public function productDetail() : HasMany {
        return $this->hasMany(ProductDetail::class, 'maChiTietSP', 'maChiTietSP');
    }
}
