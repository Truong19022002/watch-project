<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WatchStrap extends Model
{
    // use HasFactory;
    protected $table = 'tdaydeo';
    public $timestamps = false;

    public function productDetail() : HasMany {
        return $this->hasMany(ProductDetail::class, 'maDayDeo', 'maDayDeo');
    }

    public function watchStrapColor() : BelongsTo {
        return $this->belongsTo(WatchStrapColor::class, "maMauDD", "maMauDD");
    }
}
