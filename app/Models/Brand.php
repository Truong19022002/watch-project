<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    // use HasFactory;
    protected $table = 'tthuonghieu';
    public $timestamps = false;

    public function product() : HasMany {
        return $this->hasMany(Product::class, 'maThuongHieu', 'maThuongHieu');
    }
}
