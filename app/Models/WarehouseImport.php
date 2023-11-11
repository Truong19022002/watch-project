<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WarehouseImport extends Model
{
    use HasFactory;
    protected $tabel = 'tphieunhap';
    public function chiTietPhieuNhap(): HasMany
    {
        return $this->hasMany(DetailBillSale::class, 'maHDB', 'maHDB');
    }
    

}

