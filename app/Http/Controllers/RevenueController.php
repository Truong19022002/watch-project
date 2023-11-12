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
        if ($selectedYear) {
            $monthlyRevenues = $this->getMonthlyRevenues($selectedYear);
            $result[$selectedYear] = $monthlyRevenues;
        }
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
    public function QuarterMultipleYears(Request $request)
    {
        $startYear = $request->input('start_year');
        $endYear = $request->input('end_year');
        $selectedYear = $request->input('selected_year');
    
        $result = [];
        if ($selectedYear) {
            $quarterRevenues = $this->getQuarterRevenues($selectedYear);
            $result[$selectedYear] = $quarterRevenues;
        }
        for ($year = $startYear; $year <= $endYear; $year++) {
            if ($year != $selectedYear) {
                $quarterRevenues = $this->getQuarterRevenues($year);
                $result[$year] = $quarterRevenues;
            }
        }   
        return response()->json(['result' => $result]);
    }    

    public function getQuarterRevenues($year){
        return DetailBillSale::join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
        ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
        ->whereYear('thdb.ngayLapHD', $year)
        ->groupBy(DB::raw('QUARTER(thdb.ngayLapHD)'), DB::raw('YEAR(thdb.ngayLapHD)'))
        ->selectRaw('QUARTER(thdb.ngayLapHD) as quy, YEAR(thdb.ngayLapHD) as nam, SUM(tsanpham.giaSanPham * tchitiethdb.SL) as doanhthu')
        ->get();
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
protected function getRevenues($year, $month)
{
    $result = DetailBillSale::join('tsanpham', 'tchitiethdb.maSanPham', '=', 'tsanpham.maSanPham')
        ->join('thdb', 'thdb.maHDB', '=', 'tchitiethdb.maHDB')
        ->whereYear('thdb.ngayLapHD', $year)
        ->whereMonth('thdb.ngayLapHD', $month)
        ->groupBy(DB::raw('MONTH(thdb.ngayLapHD)'), DB::raw('YEAR(thdb.ngayLapHD)'))
        ->selectRaw('MONTH(thdb.ngayLapHD) as thang, YEAR(thdb.ngayLapHD) as nam, SUM(tsanpham.giaSanPham * tchitiethdb.SL) as doanhthu')
        ->get();

    return $result->isEmpty() ? null : $result;
}

public function CompareMonths(Request $request)
{
    $firstYear = $request->input('first_year');
    $secondYear = $request->input('second_year');
    $selectedMonths = $request->input('selected_months');
    
    $result = [];
    
    foreach ($selectedMonths as $month) {
        $firstYearRevenues = $this->getRevenues($firstYear, $month);

        // Kiểm tra loại dữ liệu trước khi sử dụng foreach
        if (is_array($firstYearRevenues) || is_object($firstYearRevenues)) {
            $result[$firstYear][$month] = $firstYearRevenues;
        } else {
            // Xử lý trường hợp khi biến không phải là mảng hoặc đối tượng
            // (có thể in ra thông báo hoặc thực hiện một xử lý khác tùy thuộc vào yêu cầu của bạn)
            $result[$firstYear][$month] = 'Không có dữ liệu hợp lệ';
        }

        $secondYearRevenues = $this->getRevenues($secondYear, $month);

        // Kiểm tra loại dữ liệu trước khi sử dụng foreach
        if (is_array($secondYearRevenues) || is_object($secondYearRevenues)) {
            $result[$secondYear][$month] = $secondYearRevenues;
        } else {
            // Xử lý trường hợp khi biến không phải là mảng hoặc đối tượng
            // (có thể in ra thông báo hoặc thực hiện một xử lý khác tùy thuộc vào yêu cầu của bạn)
            $result[$secondYear][$month] = 'Không có dữ liệu hợp lệ';
        }
    }
    
    return response()->json(['result' => $result]);
}


}
