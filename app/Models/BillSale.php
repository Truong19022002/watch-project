<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillSale extends Model
{
    protected $table = 'thdb';
    public function chiTietHoaDon()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'maHDB', 'maHDB');
    }
}
