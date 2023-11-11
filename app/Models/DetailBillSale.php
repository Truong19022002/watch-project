<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBillSale extends Model
{
    protected $table = 'tchitiethdb';
    public $timestamps = false;
    protected $fillable = [
        'maChiTietHDB', 'maHDB', 'maSanPham', 'SL', 'thanhTien'
    ];
    public function hoaDonBan()
    {
        return $this->belongsTo(BillSale::class, 'maHDB', 'maHDB');
    }

    // Khai báo quan hệ với bảng SanPham
    public function sanPham()
    {
        return $this->belongsTo(Product::class, 'maSanPham', 'maSanPham');
    }
}
