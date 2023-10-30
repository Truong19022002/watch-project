<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCHD extends Model
{
    // use HasFactory;

    protected $table = 'tcchd';
    public $timestamps = false;

    public function productDetail() : HasMany {
        return $this->hasMany(ProductDetail::class, 'maCCHD', 'maCCHD');
    }
}
