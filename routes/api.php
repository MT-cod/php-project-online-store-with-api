<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BasketsApiController;
use App\Http\Controllers\Api\CategoriesApiController;
use App\Http\Controllers\Api\GoodsApiController;
use App\Http\Controllers\Api\OrdersApiController;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//Гостевые маршруты
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthApiController::class, 'register']);
    Route::post('login', [AuthApiController::class, 'login']);
});

Route::get('categories', [CategoriesApiController::class, 'index']);

Route::prefix('goods')->group(function () {
    Route::get('/', [GoodsApiController::class, 'index']);
    Route::get('slug/{slug}', [GoodsApiController::class, 'slug']);
});


//Авторизованные маршруты
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::post('user', [AuthApiController::class, 'user']);
    Route::post('logout', [AuthApiController::class, 'logout']);
});

Route::prefix('basket')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [BasketsApiController::class, 'index']);
    Route::post('/', [BasketsApiController::class, 'store']);
    Route::delete('{id}', [BasketsApiController::class, 'destroy']);
    Route::delete('purge', [BasketsApiController::class, 'purge']);
});

Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [OrdersApiController::class, 'index']);
    Route::get('own_orders', [OrdersApiController::class, 'ownOrders']);
    Route::get('{id}', [OrdersApiController::class, 'show']);
    Route::post('/', [OrdersApiController::class, 'store']);
    Route::patch('{id}', [OrdersApiController::class, 'update']);
});
