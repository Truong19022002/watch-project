<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseImportController extends Controller
{
    public function showWarehouseImport(Request $request)
    {
        $pageSize = 5;
    $showpn = DB::table('tncc as tn')
        ->leftJoin('tphieunhap as pn', 'tn.maNCC', '=', 'pn.maNCC')
        ->join('tchitietpn as cpn', 'pn.maPhieuNhap', '=', 'cpn.maPhieuNhap')
        ->join('tsanpham as sp', 'cpn.maSanPham', '=', 'sp.maSanPham')
        ->select(
            'pn.maPhieuNhap',
            'pn.ngayNhap',
            'tn.maNCC',
            'tn.tenNCC',
            'tn.diaChiNCC',
            'tn.sdtNCC'
        )
        ->selectRaw('SUM(cpn.slNhap * cpn.giaSPNhap) AS tongTienHDN')
        ->groupBy('pn.maPhieuNhap') // Nhóm kết quả theo mã hóa đơn
      

        ->paginate($pageSize);
      


    $detail = DB::table('tncc as tn')
        ->leftJoin('tphieunhap as pn', 'tn.maNCC', '=', 'pn.maNCC')
        ->join('tchitietpn as cpn', 'pn.maPhieuNhap', '=', 'cpn.maPhieuNhap')
        ->join('tsanpham as sp', 'cpn.maSanPham', '=', 'sp.maSanPham')
            ->select(
                'cpn.maPhieuNhap',
                'sp.tenSanPham', 
                DB::raw('cpn.slNhap AS soLuong'),
                'cpn.giaSPNhap',
            )
        ->selectRaw('(cpn.slNhap * cpn.giaSPNhap) AS ThanhTien')
        ->paginate($pageSize);

        foreach ($showpn as $item) {
            $maPhieuNhap = $item->maPhieuNhap;

            // Tạo mảng mới chứa thông tin từ $showhd
            $combinedItem = (array)$item;

            // Tạo mảng chứa chi tiết từ $detail
            $details = [];
            foreach ($detail as $detailItem) {
                if ($detailItem->maPhieuNhap === $maPhieuNhap) {
                    $details[] = [
                        'maPhieuNhap' => $detailItem->maPhieuNhap,
                        'soLuong' => $detailItem->soLuong,
                        'tenSanPham'=>$detailItem->tenSanPham,
                        'giaSPNhap' => $detailItem->giaSPNhap,
                        'ThanhTien' => $detailItem->ThanhTien,
                    ];
                }
            }
    
            $combinedItem['details'] = $details;
    
            $result[] = $combinedItem;
        }
    
        return response()->json(['showhd' => $result]);
}
}
