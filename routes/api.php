<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StoresController;

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
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/store/{url}/products',[ProductsController::class, 'getProductsListBystore']);

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('/store', [StoresController::class, 'store']);
    Route::get('/store/{url}', [StoresController::class, 'show']);
    Route::patch('/store/{url}', [StoresController::class, 'update']);

    Route::post('/product', [ProductsController::class, 'store']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/add-to-cart', [CartController::class, 'addToCart']);
    Route::delete('/remove-from-cart/{sku}', [CartController::class, 'removeFromCart']);
});
