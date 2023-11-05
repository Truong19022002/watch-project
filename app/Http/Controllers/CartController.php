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
        $cart = Cart::with('cartDetail')->get();

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

        $cartDetailCode = 'CD' . Carbon::now()->timestamp;

        $cartDetail = CartDetail::create([
            'maChiTietGH' => $cartDetailCode,
            'maGioHang' => $cartCode,
            'maSanPham' => $product->maSanPham,
            'ngayThemSP' => Carbon::now()
        ]);

        $cart->tongTienGH = $this->calculateTotalPrice($cart);
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
            $cartDetail = DB::table('tchitietgh')->where('maChiTietGH', $id)->first();

            if (!$cartDetail) {
                return response()->json(['message' => 'Cart detail not found'], 404);
            }

            DB::table('tchitietgh')->where('maChiTietGH', $id)->delete();

            return response()->json(['message' => 'Cart detail deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete product', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function calculateTotalPrice($cart)
    {
        $cartDetails = $cart->cartDetail;
        $totalPrice = 0;

        foreach ($cartDetails as $cartDetail) {
            $product = $cartDetail->product;
            $totalPrice += $product->giaSanPham;
        }

        return $totalPrice;
    }
}
