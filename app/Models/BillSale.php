<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillSale extends Model
{
    protected $table = 'thdb';
    public $timestamps = false;
    protected $fillable = [
        'maHDB', 'maKhachHang', 'ngayLapHD', 'giamGia', 'PTTT', 'tongTienHDB'
    ];
    public function chiTietHoaDon()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'maHDB', 'maHDB');
    }
}
