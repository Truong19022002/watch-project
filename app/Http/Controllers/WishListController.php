<?php

namespace App\Http\Controllers;

use App\Models\WishlistDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WishListController extends Controller
{
    public function Wishlist(Request $request ,$maSanPham)
{
    $productId = $maSanPham;
    $product = DB::table('view_product')->where('maSanPham', $productId)->first();

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    $wishlistDetail = WishlistDetail::where('maKhachHang', auth('client')->user()->maKhachHang)
        ->where('maSanPham', $productId)
        ->first();

    if (!$wishlistDetail) {
        $wishlistDetail = WishlistDetail::create([
            'id_wishlist' => substr(uniqid(), 0, 8),
            'maKhachHang' => auth('client')->user()->maKhachHang,
            'maSanPham' => $productId,
            'date_add' => Carbon::now(),
        ]);
    } else {
        $wishlistDetail->save();
    }

    return response()->json(['message' => 'Product added to wishlist', 'wishlist_detail' => $wishlistDetail]);}
public function removeFromWishlist($productId)
{
    $wishlistDetail = WishlistDetail::where('maKhachHang', auth('client')->user()->maKhachHang)
        ->where('maSanPham', $productId)
        ->first();

    if (!$wishlistDetail) {
        return response()->json(['message' => 'Product not found in wishlist'], 404);
    }

    $wishlistDetail->delete();

    return response()->json(['message' => 'Product removed from wishlist']);
}
public function getWishlist()
{
    $wishlist = WishlistDetail::where('maKhachHang', auth('client')->user()->maKhachHang)->get();

    return response()->json(['wishlist' => $wishlist]);
}




    
}
