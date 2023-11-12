<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\User;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth('client')->user()->maKhachHang;

        $cart = Cart::with('cartDetail')->where('maKhachHang', $userId)->get();

        return $cart;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productId = $request->input('maSanPham');

        $product = DB::table('view_product')->where('maSanPham', $productId)->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $cart = Cart::where('maKhachHang', auth('client')->user()->maKhachHang)->first();

        $cartCode = $cart->maGioHang;
        $cartDetailCode = substr(uniqid(), 0, 8);

        $cartDetail = CartDetail::where('maGioHang', $cartCode)->where('maSanPham', $productId)->first();

        if($cartDetail) {
            $cartDetail->soLuongSP += 1;
            $cartDetail->save();
        } else {
            $cartDetail = CartDetail::create([
                'maChiTietGH' => $cartDetailCode,
                'maGioHang' => $cartCode,
                'maSanPham' => $product->maSanPham,
                'ngayThemSP' => Carbon::now(),
                'soLuongSP' => 1
            ]);
        }

        $cart->tongTienGH = $this->calculateTotalPrice($cart->cartDetail);
        $cart->save();

        return response()->json(['message' => 'Product added to cart', 'cart_detail' => $cartDetail]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $cartDetail = DB::table('tchitietgh')->where('maSanPham', $id)->first();

            if (!$cartDetail) {
                return response()->json(['message' => 'Cart detail not found'], 404);
            }

            $totalPriceBeforeDeletion = $this->calculateTotalPrice($cartDetails);

            DB::table('tchitietgh')->where('maSanPham', $id)->delete();

            $cart->tongTienGH -= $totalPriceBeforeDeletion;
            $cart->save();

            return response()->json(['message' => 'Cart detail deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete product', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function calculateTotalPrice($cartDetails)
    {
        $totalPrice = 0;
        foreach ($cartDetails as $cartDetail) {
            $product = $cartDetail->product;
            $soLuong = $cartDetail->soLuongSP;
            $totalPrice += ($product->giaSanPham * $soLuong);
        }
        return $totalPrice;
    }
}
