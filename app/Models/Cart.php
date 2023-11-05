<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    // use HasFactory;
    protected $table = 'tgiohang';
    public $timestamps = false;

    public function cartDetail() : HasMany {
        return $this->hasMany(CartDetail::class, 'maGioHang', 'maGioHang');
    }
    public function client() : BelongsTo {
        return $this->belongsTo(Client::class, 'maKhachHang', 'maKhachHang');
    }
}
