<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiAuthController;
use App\Http\Controllers\API\ApiBrandController;
use App\Http\Controllers\API\ApiForgetController;
use App\Http\Controllers\API\ApiItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login',[ApiAuthController::class,'login']);
Route::post('/register',[ApiAuthController::class,'register']);

Route::post('/forget',[ApiForgetController::class,'forgot']);
Route::post('/otp',[ApiForgetController::class,'otp']);
Route::post('/reset',[ApiForgetController::class,'reset'])->middleware('Api-otp');
Route::post('/update_size',[ApiAuthController::class,'update_size'])->middleware('Api-auth');


Route::get('/show_brands',[ApiBrandController::class,'index']);
Route::post('/update_profile',[ApiAuthController::class,'update'])->middleware('Api-auth');

Route::get('/show_items',[ApiItemController::class,'index']);
Route::get('/show_items/{id}',[ApiItemController::class,'item']);
Route::get('/add_favor/{id}',[ApiItemController::class,'add_favor'])->middleware('Api-auth');
Route::get('/remove_items/{id}',[ApiItemController::class,'remove_favor'])->middleware('Api-auth');
Route::get('/get_profile', [ApiAuthController::class, 'getProfile'])->middleware('Api-auth');


Route::post('/sellers', [ApiBrandController::class, 'add_seller'])->name('sellers.add');
Route::put('/sellers/{sellerId}', [ApiBrandController::class, 'update_seller'])->name('sellers.update');

Route::post('/add_item',[ApiItemController::class,'add_product']);
Route::post('/remove_item/{id}',[ApiItemController::class,'remove_product']);
Route::put('/edit_item/{id}',[ApiItemController::class,'edit_product']);

