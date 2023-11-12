<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillSaleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\WatchStrapController;
use App\Http\Controllers\WatchShapeController;
use App\Http\Controllers\CCHDController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DetailProductController;
use App\Http\Controllers\ForgotController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\WarehouseImportController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\WishlistDetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//      return $request->user();
//      });
Route::apiResource('product', ProductController::class);

Route::get('/product-ids', [ProductController::class, 'getListId']);
Route::delete('/product-ids', [ProductController::class, 'deleteMany']);

Route::get('/brand', [BrandController::class, 'get']);
Route::get('/size', [SizeController::class, 'get']);
Route::get('/strap', [WatchStrapController::class, 'get']);
Route::get('/shape', [WatchShapeController::class, 'get']);
Route::get('/cchd', [CCHDController::class, 'get']);
Route::get('/material', [MaterialController::class, 'get']);
Route::get('/type', [TypeController::class, 'get']);

Route::apiResource('cart', CartController::class);

Route::get('/vnpay_payment', [CheckoutController::class, 'vnpay_payment']);
Route::get('/bill', [CheckoutController::class, 'updateBill']);

Route::middleware(['user'])->prefix('/auth/admin')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/profile', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update/{idUser}', [AuthController::class,'update']);

});

Route::middleware(['client'])->prefix('/auth/user')->group(function() {
    Route::post('/login', [ClientController::class, 'login']);
    Route::post('/register', [ClientController::class, 'register']);
    Route::get('/profile', [ClientController::class, 'me']);
    Route::post('/logout', [ClientController::class, 'logout']);
    Route::post('/updatePassword/{maKhachHang}', [ClientController::class, 'updatePassword']);
    Route::post('/update/{maKhachHang}', [ClientController::class,'update']);
    Route::post('/changePassword/{maKhachHang}', [ClientController::class,'editPassword']);
});
// Route::get('search/{key}',[ProductController::class,'search']);
Route::get('search',[ProductController::class, 'search']);
Route::get('filter',[ProductController::class, 'filter']);

Route::post('/media-file',[ProductController::class,'uploadImages']);

Route::get('/monthlyRevenues', [RevenueController::class,'MultipleYears']);
Route::get('/quarterlyRevenues', [RevenueController::class,'QuarterMultipleYears']);
Route::get('/revenueByBrand', [RevenueController::class,'revenueByBrand']);
Route::get('/CompareMonths', [RevenueController::class,'CompareMonths']);

Route::get('revenue', [RevenueController::class,'revenue']);
Route::get('/productsByQuantitySoldLastMonth', [RevenueController::class,'productsByQuantitySoldLastMonth']);

Route::get('products/{maSanPham}/image', [ProductController::class,'getImage']);

Route::get('image_detail/{imageCTSP}', [DetailProductController::class, 'getImageByName']);

Route::get('products_detail/{maChiTietSP}/image', [DetailProductController::class,'getImageDetail']);

Route::post('update/{maSanPham}', [ProductController::class, 'update']);

Route::get('/showUser', [UserController::class,'showUser']);
Route::get('/showHdb', [BillSaleController::class, 'showHdb']);
Route::get('/showPN', [WarehouseImportController::class,'showWarehouseImport']);

Route::post('/ForgotByEmail', [ForgotController::class,'ForgotByEmail']);
Route::post('/wishlist/add', [WishListController::class,'Wishlist']);
Route::delete('/wishlist/remove/{productId}', [WishListController::class,'removeFromWishlist']);
Route::get('/wishlist', [WishListController::class,'getWishlist']);

