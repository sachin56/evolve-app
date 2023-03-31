<?php

use App\Http\Controllers\ProductListController;
use App\Http\Controllers\SellerController;
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

Route::get('/product_list',[ProductListController::class,'index']);
Route::get('/product_list/create',[ProductListController::class,'create']);
Route::post('/product_list',[ProductListController::class,'store']);
Route::get('/product_list/{id}',[ProductListController::class,'show']);
Route::put('/product_list/{id}',[ProductListController::class,'update']);
Route::delete('/product_list/{id}',[ProductListController::class,'destroy']);

Route::get('/seller',[SellerController::class,'index']);
Route::get('/seller/create',[SellerController::class,'create']);
Route::post('/seller',[SellerController::class,'store']);
Route::get('/seller/{id}',[SellerController::class,'show']);
Route::put('/seller/{id}',[SellerController::class,'update']);
Route::delete('/seller/{id}',[SellerController::class,'destroy']);
Route::get('/product_list/seller/{id}',[ProductListController::class,'seller_filter']);