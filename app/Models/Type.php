<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    // use HasFactory;
    protected $table = 'tloai';
    public $timestamps = false;

    public function product() : HasMany {
        return $this->hasMany(Product::class, 'maLoai', 'maLoai');
    }
}
