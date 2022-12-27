<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FileController;
use App\Models\Administrator;
use App\Models\Basket;
use App\Models\Media;
use App\Models\Order;
use App\Models\Product;
use App\Models\SizeAndPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', function () {
    return view('upload');
});
Route::get('/upload', [FileController::class, 'product_view']);//главная страница продуктов, откуда показываются продукты и редактируются
Route::get('/upload/product', function () {
    if(!isset(Auth::user()->name)){
        return view('unauth');
    }
    else if((Administrator::where('email',Auth::user()->email)->value('email')===null)){
        return view('unauth');
    }
    $status=null;
        return view('/product/upload_product', compact('status'));
})->name('add.product');

Route::get('upload/product/edit/{productId}', [FileController::class,'edit_product'])->name('edit.product');

Route::get('/orders', function () {
    return redirect('/orders/view_orders');
});
Route::get('/upload/view_orders/detail/{order}', [FileController::class, 'detail']);

Route::post('/orders/update_status/{order}', [FileController::class, 'update_status']);


Route::get('/view_orders', [FileController::class, 'view_orders']);
Route::post('/upload_data', [FileController::class, 'upload']);

Route::post("product/add", [ProductController::class, 'store']);
Route::post("product/update/{product}", [ProductController::class, 'update']);
Route::get("product/delete/{product}", [ProductController::class, 'destroy'])->name('destroy.product');

Route::get("banners",[BannerController::class,'index']);
Route::get("banners_view/add",function(){
    return view('banners/upload_banner');
} )->name('add.banner');
Route::post("banners/add",[BannerController::class,'store']);
Route::get("banners/delete/{idBanner}",[BannerController::class,'destroy']);