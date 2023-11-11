<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\BillSale;
use App\Models\DetailBillSale;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function MultipleYears(Request $request)
    {
        $startYear = $request->input('start_year');
        $endYear = $request->input('end_year');
        $selectedYear = $request->input('selected_year');
    
        $result = [];
    
        // Lấy doanh thu cho một năm cụ thể nếu được chỉ định
        if ($selectedYear) {
            $monthlyRevenues = $this->getMonthlyRevenues($selectedYear);
            $result[$selectedYear] = $monthlyRevenues;
        }
    
        // Lấy doanh thu cho nhiều năm liên tiếp
        for ($year = $startYear; $year <= $endYear; $year++) {
            if ($year != $selectedYear) {
                $monthlyRevenues = $this->getMonthlyRevenues($year);
                $result[$year] = $monthlyRevenues;
            }
        }
    
        return response()->json(['result' => $result]);
    }
    
    protected function getMonthlyRevenues($year)
    {
        return DetailBillSale::join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
            ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
            ->whereYear('thdb.ngayLapHD', $year)
            ->groupBy(DB::raw('MONTH(thdb.ngayLapHD)'), DB::raw('YEAR(thdb.ngayLapHD)'))
            ->selectRaw('MONTH(thdb.ngayLapHD) as thang, YEAR(thdb.ngayLapHD) as nam, SUM(tsanpham.giaSanPham * tchitiethdb.SL) as doanhthu')
            ->get();
    }
    


    public function Quarter(Request $request)
    {
        $year = $request->input('nam');

        // Thống kê theo quý
        $quarterlyQuery = DetailBillSale::join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
        ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
        ->select(
            DB::raw('QUARTER(thdb.ngayLapHD) as quy'),
            DB::raw('YEAR(thdb.ngayLapHD) as nam'),
            DB::raw('SUM(tsanpham.giaSanPham * tchitiethdb.SL) as doanhthu')
        )
        ->whereYear('thdb.ngayLapHD', $year) 
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
    
    public function productsByQuantitySoldLastMonth()
{
    $productsByQuantitySold = DB::table('tchitiethdb')
        ->join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
        ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
        ->select(
            'tsanpham.maSanPham',
            'tsanpham.tenSanPham as productName',
            'tchitiethdb.SL as quantitySold',
            DB::raw('SUM(tsanpham.giaSanPham * tchitiethdb.SL) as totalRevenue')
        )
        ->whereBetween('thdb.ngayLapHD', [now()->subMonth(), now()]) // Lọc theo thời gian trong 1 tháng gần đây
        ->groupBy('tsanpham.maSanPham', 'productName', 'quantitySold')
        ->orderByDesc('quantitySold') // Sắp xếp theo lượng mua giảm dần
        ->get();

    return response()->json(['productsByQuantitySold' => $productsByQuantitySold]);
}

}
