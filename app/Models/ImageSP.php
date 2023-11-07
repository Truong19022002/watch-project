<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ImageSP extends Model
{
    protected $table = 'tanhctsp';
    public $timestamps = false;
    public function productDetail(): BelongsTo {
        return $this->belongsTo(ProductDetail::class, 'maChiTietSP', 'maChiTietSP');
    }
}
