<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\BillSale;
use App\Models\DetailBillSale;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function Month(Request $request)
    {
        // Thống kê theo tháng
        $monthlyQuery = DetailBillSale::join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
            ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
            ->select(
                DB::raw('MONTH(thdb.ngayLapHD) as thang'),
                DB::raw('YEAR(thdb.ngayLapHD) as nam'),
                DB::raw('SUM(tsanpham.giaSanPham * tchitiethdb.SL) as doanhthu')
            )
            ->groupBy('thang', 'nam');
        $monthlyRevenues = $monthlyQuery->get();

        return response()->json(['monthlyRevenues' => $monthlyRevenues]);
    }
    public function Quarter(Request $request){
        // Thống kê theo quý
        $quarterlyQuery = DetailBillSale::join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
        ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
        ->select(
            DB::raw('QUARTER(thdb.ngayLapHD) as quy'),
            DB::raw('YEAR(thdb.ngayLapHD) as nam'),
            DB::raw('SUM(tsanpham.giaSanPham * tchitiethdb.SL) as doanhthu')
        )
        ->groupBy('quy', 'nam');
        $quarterlyRevenues = $quarterlyQuery->get();
        return response()->json(['quarterlyRevenues'=> $quarterlyRevenues]);
    }
    public function revenueByBrand()
    {
        $revenueByBrand = DetailBillSale::join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
            ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
            ->join('tthuonghieu', 'tsanpham.maThuongHieu', '=', 'tthuonghieu.maThuongHieu')
            ->select(
                'tthuonghieu.tenThuongHieu as brand',
                DB::raw('SUM(tsanpham.giaSanPham * tchitiethdb.SL) as totalRevenue')
            )
            ->groupBy('brand')
            ->orderByDesc('totalRevenue')
            ->get();
    
        return response()->json(['revenueByBrand' => $revenueByBrand]);
    }
}
