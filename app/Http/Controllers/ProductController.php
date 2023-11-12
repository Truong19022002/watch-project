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
    ]);
    $product = new Product();
    $product->maSanPham = substr(uniqid(), 0, 8);
    $product->tenSanPham = $request->input('tenSanPham');
    $product->giaSanPham = $request->input('giaSanPham');
    $product->maLoai = $request->input('maLoai');
    $product->maThuongHieu = $request->input('maThuongHieu');
    $product->slTonKho = null;
    if ($request->hasFile('anhSP')) {
        $image = $request->file('anhSP');
        $imageName = $image->getClientOriginalName(); 
        $image->move(public_path('img_product'), $imageName); 
        $product->anhSP = $imageName;
    }    
    $product->moTaSP = $request->input('moTaSP');
    $product->ngayThemSP = Carbon::now();
    $product->maSeri = substr(uniqid(), 0, 12); 
    $product->save();
    $maSanPham = $product->maSanPham;
    $productDetail = new ProductDetail();
    $productDetail->maSanPham = $maSanPham;
    $productDetail->maChiTietSP = substr(uniqid(), 0, 8);
    $productDetail->maKichThuoc = $request->input('maKichThuoc');
    $productDetail->maHinhDang = $request->input('maHinhDang');
    $productDetail->maChatlieu = $request->input('maChatlieu');
    $productDetail->maDayDeo = $request->input('maDayDeo');
    $productDetail->maCCHD = $request->input('maCCHD');
    $productDetail->save();
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $imageName = $image->getClientOriginalName(); 
            $image->move(public_path('img_product'), $imageName); 
            do {
                $maAnhCTSP = substr(uniqid(), 0, 8);
            } while (ImageSP::where('maAnhCTSP', $maAnhCTSP)->exists());
            $tanHSP = new ImageSP();
            $tanHSP->maAnhCTSP = $maAnhCTSP;
            $tanHSP->maChiTietSP = $productDetail->maChiTietSP;
            $tanHSP->imageCTSP = $imageName;
            $tanHSP->chuthich = $request->input('chuthich'); 
            $tanHSP->save();
        }
    }

    return response()->json(['message' => 'Product created successfully', 'data' => $product]);
}
    public function show(string $id)
    {
        $product = DB::table('view_product')->where('maSanPham', $id)->first();
        $addToCartUrl = route('cart.store', ['maSanPham' => $product->maSanPham]);
        return response()->json([
            'product' => $product,
            'addToCartUrl' => $addToCartUrl
        ]);
    }
    public function update(Request $request, $maSanPham)
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
        ]);

        $product = Product::where('maSanPham', $maSanPham)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->tenSanPham = $request->input('tenSanPham');
        $product->giaSanPham = $request->input('giaSanPham');
        $product->maLoai = $request->input('maLoai');
        $product->moTaSP = $request->input('moTaSP'); 
        $product->maThuongHieu = $request->input('maThuongHieu');
        if ($request->hasFile('anhSP')) {
            $image = $request->file('anhSP');
            $imageName = $image->getClientOriginalName(); 
            $image->move(public_path('img_product'), $imageName); 
            $product->anhSP = $imageName;
        }  
        $product->save();
        $productDetail = ProductDetail::where('maSanPham', $maSanPham)->first();
        if (!$productDetail) {
            return response()->json(['message' => 'Product detail not found'], 404);
        }
        $productDetail->maKichThuoc = $request->input('maKichThuoc');
        $productDetail->maHinhDang = $request->input('maHinhDang');
        $productDetail->maChatlieu = $request->input('maChatlieu');
        $productDetail->maDayDeo = $request->input('maDayDeo');
        $productDetail->maCCHD = $request->input('maCCHD');
        $productDetail->save();
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $image->getClientOriginalName(); 
                $image->move(public_path('img_product'), $imageName); 
                do {
                    $maAnhCTSP = substr(uniqid(), 0, 8);
                } while (ImageSP::where('maAnhCTSP', $maAnhCTSP)->exists());
                $tanHSP = new ImageSP();
                $tanHSP->maAnhCTSP = $maAnhCTSP;
                $tanHSP->maChiTietSP = $productDetail->maChiTietSP;
                $tanHSP->imageCTSP = $imageName;
                $tanHSP->chuthich = $request->input('chuthich'); 
                $tanHSP->save();
            }
        }
        return response()->json(['message' => 'Product updated successfully', 'data' => $product]);
        }
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
    public function search(Request $request)
    {
    $keyword = $request->get('keyword');
    $pageSize = $request->get('pageSize', 10);
    $query = DB::table('view_product')->where('tenSanPham', 'like', '%'.$keyword.'%');
    $items = $query->paginate($pageSize);
    $items->appends([
        'keyword' => $keyword,       
    ]);
    return response()->json($items);
    }
    public function Filter(Request $request){
        $pageSize = $request->get('pageSize', 12);
        $minPrice = $request->get('minPrice');
        $maxPrice = $request->get('maxPrice');
        $orderBy = $request->get('orderBy','asc'&'desc');
        $gioiTinh = $request->get('gioiTinh');
        $chatLieu = $request->get('chatLieu');
        $hinhDang = $request->get('hinhDang');
        $CCDH = $request->get('CCDH');
        $dayDeo = $request ->get('dayDeo');
        $kichThuoc = $request->get('kichThuoc');
        $query = DB::table('view_product');
        if (!empty($minPrice)) {
            $query->where('giaSanPham', '>=', $minPrice);
        }
    
        if (!empty($maxPrice)) {
            $query->where('giaSanPham', '<=', $maxPrice);
        }
        if (!empty($gioiTinh)) {
            $query->where('tenLoai', 'LIKE', '%'.$gioiTinh.'%');
        }
        if (!empty($chatLieu)) {
            $query->where('tenCL', 'LIKE', '%'.$chatLieu.'%');
        }
        if (!empty($hinhDang)) {
            $query->where('tenHinhDang', 'LIKE', '%'.$hinhDang.'%');
        }
        if (!empty($CCDH)) {
            $query->where('tenCCDH', 'LIKE', '%'.$CCDH.'%');
        }
        if (!empty($dayDeo)) {
            $query->where('loaiDayDeo', 'LIKE', '%'.$dayDeo.'%');
        }
        if (!empty($kichThuoc)) {
            $query->where('kichThuoc', 'LIKE', '%'.$kichThuoc.'%');
        }
        $query->orderBy('giaSanPham', $orderBy);
    
        $items = $query->paginate($pageSize);
        $items->appends([
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'orderBy' => $orderBy,
            'gioiTinh' => $gioiTinh,
            'chatLieu'=> $chatLieu,
            'hinhDang' => $hinhDang,
            'CCDH' => $CCDH,
            'dayDeo'=> $dayDeo,
            'kichThuoc' => $kichThuoc,
        ]);
    
        return response()->json($items);
    }
    public function getImage($maSanPham)
    {
        $product = Product::where('maSanPham', $maSanPham)->firstOrFail();

        
        return response()->file(public_path("img_product/{$product->anhSP}"));
        
    }
    

}
