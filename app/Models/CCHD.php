<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CCHD extends Model
{
    // use HasFactory;

    protected $table = 'tcchd';
    public $timestamps = false;

    public function productDetail() : HasMany {
        return $this->hasMany(ProductDetail::class, 'maCCHD', 'maCCHD');
    }
}
