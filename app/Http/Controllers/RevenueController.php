<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\BillSale;
use App\Models\DetailBillSale;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    // public function index(Request $request)
    // {
    //     $month = $request->query('month');
    //     $year = $request->query('year');

    //     $revenue = DB::table('thdb')
    //         ->join('tchitiethdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
    //         ->join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
    //         ->whereMonth('thdb.ngayLapHoaDon', $month)
    //         ->whereYear('thdb.ngayLapHoaDon', $year)
    //         ->sum(DB::raw('tsanpham.giaSanPham * tchitiethdb.SL'));

    //     return response()->json(['revenue' => $revenue]);
    // }
    // public function index(Request $request)
    // {
    //     $month = $request->query('month');
    //     $year = $request->query('year');

    //     $revenue = DetailBillSale::join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
    //         ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
    //         ->whereMonth('thdb.ngayLapHD', $month)
    //         ->whereYear('thdb.ngayLapHD', $year)
    //         ->select(DB::raw('SUM(tsanpham.giaSanPham * tchitiethdb.SL) as revenue'))
    //         ->first()
    //         ->revenue;

    //     return response()->json(['revenue' => $revenue]);
    // }

    public function revenue()
    {
        $revenues = [];

        $years = DB::table('thdb')
            ->select(DB::raw('YEAR(ngayLapHD) as year'))
            ->distinct()
            ->get();

        foreach ($years as $year) {
            $monthlyRevenues = [];

            for ($month = 1; $month <= 12; $month++) {
                $revenue = DetailBillSale::join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
                    ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
                    ->whereMonth('thdb.ngayLapHD', $month)
                    ->whereYear('thdb.ngayLapHD', $year->year)
                    ->select(DB::raw('SUM(tsanpham.giaSanPham * tchitiethdb.SL) as revenue'))
                    ->first()
                    ->revenue;

                $monthlyRevenues[] = [
                    'month' => $month,
                    'revenue' => $revenue,
                ];
            }

            $revenues[$year->year] = $monthlyRevenues;
        }

        return response()->json(['revenues' => $revenues]);
    }
}
