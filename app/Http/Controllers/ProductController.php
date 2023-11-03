<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\Product;
use App\Models\ProductDetail;
use Carbon\Carbon;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10);
        $result = DB::table('view_product')->paginate($pageSize);
        return $result;
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
        $request->validate([
            'tenSanPham' => 'required',
            'giaSanPham' => 'required',
            'maLoai' => 'required',
            'maThuongHieu' => 'required',
            'maKichThuoc' => 'required',
            'maHinhDang' => 'required',
            'maChatlieu' => 'required',
            'maDayDeo' => 'required',
            'maCCHD' => 'required'
        ]);

        $product = new Product();
        $product->maSanPham = substr(uniqid(), 0, 8);
        $product->tenSanPham = $request->input('tenSanPham');
        $product->giaSanPham = $request->input('giaSanPham');
        $product->maLoai = $request->input('maLoai');
        $product->maThuongHieu = $request->input('maThuongHieu');
        $product->slTonKho = null;
        $product->anhSP = null;
        $product->moTaSP = null;
        $product->ngayThemSP = Carbon::now();
        $product->maSeri = substr(uniqid(), 0, 12);
        $product->save();

        $productDetail = new ProductDetail();
        $productDetail->maSanPham = $product->maSanPham;
        $productDetail->maChiTietSP = substr(uniqid(), 0, 8);
        $productDetail->maKichThuoc = $request->input('maKichThuoc');
        $productDetail->maHinhDang = $request->input('maHinhDang');
        $productDetail->maChatlieu = $request->input('maChatlieu');
        $productDetail->maDayDeo = $request->input('maDayDeo');
        $productDetail->maCCHD = $request->input('maCCHD');
        $productDetail->save();

        return response()->json(['message' => 'Product created successfully', 'data' => $product]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = DB::table('view_product')->where('maSanPham', $id)->first();
        return $result;
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
            $productDetail = DB::table('tchitietsp')->where('maSanPham', $id)->first();
            if ($productDetail) {
                DB::table('tchitietsp')->where('maSanPham', $id)->delete();
            }

            $product = DB::table('tsanpham')->where('maSanPham', $id)->first();
            if (!$product) {
                throw new ModelNotFoundException('Product not found');
            }
            DB::table('tsanpham')->where('maSanPham', $id)->delete();

            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete product', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}