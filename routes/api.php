<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
    Route::post('/product', [ProductsController::class, 'store']);
    // Route::post('/auth/logout', [AuthController::class, 'logout']);
});
