<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NCC extends Model
{
    use HasFactory;
    protected $table = 'tncc';

    public function phieuNhap() : HasMany {
        return $this->hasMany(WarehouseImport::class, 'maNCC', 'maNCC');
    }

}
