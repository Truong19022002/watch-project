<?php

namespace App\Http\Controllers;

use App\Models\ImageSP;
use Faker\Provider\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            'maCCHD' => 'required',
            // 'maAnhCTSP' => 'required',
        ]);

        $product = new Product();
        $product->maSanPham = substr(uniqid(), 0, 8);
        $product->tenSanPham = $request->input('tenSanPham');
        $product->giaSanPham = $request->input('giaSanPham');
        $product->maLoai = $request->input('maLoai');
        $product->maThuongHieu = $request->input('maThuongHieu');
        $product->slTonKho = null;
        $product->anhSP = null;
        // $product->anhSP=$request -> file('anhSP')->store('tsanpham');

        $product->moTaSP = $request->input('moTaSP');
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
        $product = DB::table('view_product')->where('maSanPham', $id)->first();
        $addToCartUrl = route('cart.store', ['maSanPham' => $product->maSanPham]);
        return response()->json([
            'product' => $product,
            'addToCartUrl' => $addToCartUrl
        ]);
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

    public function getListId(Request $request)
    {
        $ids = $request->input('ids');

        $foundIds = [];
        $notFoundIds = [];

        foreach ($ids as $id) {
            $product = DB::table('tsanpham')->where('maSanPham', $id)->first();

            if ($product) {
                $foundIds[] = $id;
            } else {
                $notFoundIds[] = $id;
            }
        }

        return [
            'foundIds' => $foundIds,
            'notFoundIds' => $notFoundIds
        ];
    }

    public function deleteMany(Request $request)
    {
        try {
            $ids = $request->input('ids');

            $listId = $this->getListId($request);
            $foundIds = $listId['foundIds'];
            $notFoundIds = $listId['notFoundIds'];

            DB::table('tchitietsp')->whereIn('maSanPham', $foundIds)->delete();
            DB::table('tsanpham')->whereIn('maSanPham', $foundIds)->delete();

            return response()->json(['message' => 'Products deleted successfully', 'notFoundIds' => $notFoundIds]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete products', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // function search($key) {
    //     return Product::where('tenSanPham', 'like', "%$key%")
    //         ->orWhereHas('brand', function ($query) use ($key) {
    //             $query->where('tenThuongHieu', 'like', "%$key%");
    //         })
    //         ->orWhereHas('type', function ($query) use ($key) {
    //             $query->where('tenLoai', 'like',"%$key%");
    //         })
    //         ->orWhereHas('productDetail.material', function ($query) use ($key) {
    //             $query->where('tenCL', 'like', "%$key%");
    //         })
    //         ->orWhereHas('productDetail.size', function ($query) use ($key) {
    //             $query->where('kichThuoc', 'like', "%$key%");
    //         })
    //         ->orWhereHas('productDetail.cchd', function ($query) use ($key) {
    //             $query->where('tenCCHD', 'like', "%$key%");
    //         })
    //         ->orWhereHas('productDetail.watchShape', function ($query) use ($key) {
    //             $query->where('tenHinhDang', 'like', "%$key%");
    //         })
    //         ->orWhereHas('productDetail.watchStrap', function ($query) use ($key) {
    //             $query->where('loaiDayDeo', 'like', "%$key%");
    //         })
    //         ->get();
    // }

    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        $pageSize = $request->get('pageSize', 10);

        $query = DB::table('view_product')->where('tenSanPham', 'like', '%'.$keyword.'%');

        $items = $query->paginate($pageSize);
        $items->appends(['keyword' => $keyword]);

        return response()->json($items);
    }
}
