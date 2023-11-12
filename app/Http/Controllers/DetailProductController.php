<?php

namespace App\Http\Controllers;

use App\Models\ImageSP;
use Illuminate\Http\Request;
use App\Models\ProductDetail;

class DetailProductController extends Controller
{
    public function show($maChiTietSP)
    {
        $productDetail = ProductDetail::with('productImages')->where('maChiTietSP', $maChiTietSP)->first();
        return response()->json($productDetail);
    }

    // public function getImageDetail($maChiTietSP)
    // {
    //     $productDetail = ProductDetail::where('maChiTietSP', $maChiTietSP)->first();

    //     if (!$productDetail) {
    //         return response()->json(['error' => 'Chi tiết sản phẩm không tồn tại'], 404);
    //     }

    //     $images = $productDetail->imagectsp;

    //     // if (!$images || $images->isEmpty()) {
    //     //     return response()->json(['error' => 'Không có ảnh chi tiết sản phẩm'], 404);
    //     // }

    //     $imagePaths = [];

    //     foreach ($images as $image) {
    //         if (isset($image->imageCTSP)) {
    //             $imagePaths[] = public_path("img_product/{$image->imageCTSP}");
    //         }
    //     }

    //     // Kiểm tra xem có ảnh nào không
    //     if (count($imagePaths) === 0) {
    //         return response()->json(['error' => 'Không có ảnh chi tiết sản phẩm'], 404);
    //     }

    //     // Trả về tệp ảnh đầu tiên (hoặc tệp ảnh theo yêu cầu)
    //     return response()->file($imagePaths[0]);
        
    // }
    public function getImageDetail($maSanPham)
    {
        // Tìm chi tiết sản phẩm trong bảng ProductDetail
        $productDetail = ProductDetail::where('maSanPham', $maSanPham)->first();
    
        if (!$productDetail) {
            return response()->json(['error' => 'Chi tiết sản phẩm không tồn tại'], 404);
        }  
        $maChiTietSP = $productDetail->maChiTietSP;
        $images = ImageSP::where('maChiTietSP', $maChiTietSP)->get();
        if ($images->isEmpty()) {
            return response()->json(['error' => 'Không có ảnh chi tiết sản phẩm'], 404);
        }
        $imageNames = $images->pluck('imageCTSP')->all();
    
        // Kiểm tra xem có ảnh nào không
        if (count($imageNames) === 0) {
            return response()->json(['error' => 'Không có ảnh chi tiết sản phẩm'], 404);
        }
    
        // Trả về danh sách tên tệp ảnh
        return response()->json(['imageNames' => $imageNames]);
    }
    
    public function getImageByName($imageCTSP)
    {
        $anh = ImageSP::where('imageCTSP', $imageCTSP)->firstOrFail();

        
        return response()->file(public_path("img_product/{$anh->imageCTSP}"));
        
    }

}
