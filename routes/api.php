<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\WatchStrapController;
use App\Http\Controllers\WatchShapeController;
use App\Http\Controllers\CCHDController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RevenueController;
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
Route::get('/type', [MaterialController::class, 'get']);

Route::apiResource('cart', CartController::class);

Route::get('/momo_payment', [CheckoutController::class, 'momo_payment']);

Route::middleware(['user'])->prefix('/auth/admin')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/profile', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['client'])->prefix('/auth/user')->group(function() {
    Route::post('/login', [ClientController::class, 'login']);
    Route::post('/register', [ClientController::class, 'register']);
    Route::get('/profile', [ClientController::class, 'me']);
    Route::post('/logout', [ClientController::class, 'logout']);
});

// Route::get('search/{key}',[ProductController::class,'search']);
Route::get('search',[ProductController::class, 'search']);

Route::post('/media-file',[ProductController::class,'uploadImages']);

Route::get('revenue', [RevenueController::class,'index']);