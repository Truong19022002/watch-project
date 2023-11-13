<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailBillSale;
use App\Models\BillSale;
use Illuminate\Support\Facades\DB;

class BillSaleController extends Controller
{
    public function showHdb(Request $request)
    {
        if (auth('client')->check()) {
            $userId = auth('client')->user()->maKhachHang;
            $result = BillSale::with(['chiTietHoaDon.sanPham.productDetail'])->where('maKhachHang', $userId)->get();
            return $result;
        }

        $pageSize = $request->get('pageSize', 10);
        $page = $request->has('page') ? $request->query('page') : 1;

        $showhd = DB::table('thdb as th')
            ->leftJoin('tchitiethdb as ct', 'th.maHDB', '=', 'ct.maHDB')
            ->join('tkhachhang as kh', 'th.maKhachHang', '=', 'kh.maKhachHang')
            ->join('tsanpham as sp', 'ct.maSanPham', '=', 'sp.maSanPham')
            ->select(
                'th.maHDB',
                'th.maKhachHang',
                'kh.tenKhachHang',
                'kh.diaChi',
                'kh.SDT',
                'kh.email',
                'th.ngayLapHD',
                'th.giamGia'
            )
            ->selectRaw('SUM(ct.SL * sp.giaSanPham) as tongTienHDB')
            ->groupBy('th.maHDB') // Nhóm kết quả theo mã hóa đơn
            ->paginate($pageSize, ['*'], 'page', $page);


        $detail = DB::table('thdb as th')
            ->leftJoin('tchitiethdb as ct', 'th.maHDB', '=', 'ct.maHDB')
            ->join('tsanpham as sp', 'ct.maSanPham', '=', 'sp.maSanPham')
            ->select(
                'th.maHDB',
                'sp.tenSanPham', 
                DB::raw('ct.SL AS soLuong'),
                'sp.giaSanPham'
            )
            ->selectRaw('(ct.SL * sp.giaSanPham) AS ThanhTien')
            ->paginate($pageSize, ['*'], 'page', $page);
            

            foreach ($showhd as $item) {
                $maHDB = $item->maHDB;

                // Tạo mảng mới chứa thông tin từ $showhd
                $combinedItem = (array)$item;

                // Tạo mảng chứa chi tiết từ $detail
                $details = [];
                foreach ($detail as $detailItem) {
                    if ($detailItem->maHDB === $maHDB) {
                        $details[] = [
                            'maHDB' => $detailItem->maHDB,
                            'soLuong' => $detailItem->soLuong,
                            'tenSanPham'=>$detailItem->tenSanPham,
                            'giaSanPham' => $detailItem->giaSanPham,
                            'ThanhTien' => $detailItem->ThanhTien,
                        ];
                    }
                }
        
                $combinedItem['details'] = $details;
        
                $result[] = $combinedItem;
            }
    
            return response()->json(['showhd' => $result]);
    }
   
    //
}
