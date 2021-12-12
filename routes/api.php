<?php

use App\Http\Controllers\Api\ApiAdditionalCharsController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiBasketsController;
use App\Http\Controllers\Api\ApiCategoriesController;
use App\Http\Controllers\Api\ApiGoodsController;
use App\Http\Controllers\Api\ApiOrdersController;
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
    Route::post('register', [ApiAuthController::class, 'register']);
    Route::post('login', [ApiAuthController::class, 'login']);
});

Route::prefix('categories')->group(function () {
    Route::get('tree', [ApiCategoriesController::class, 'tree']);
    Route::get('/', [ApiCategoriesController::class, 'index']);
    Route::get('{id}', [ApiCategoriesController::class, 'show']);
});

Route::prefix('goods')->group(function () {
    Route::get('/', [ApiGoodsController::class, 'index']);
    Route::get('slug/{slug}', [ApiGoodsController::class, 'slug']);
    Route::get('{id}', [ApiGoodsController::class, 'show']);
});

Route::resource(
    'additionalChars',
    ApiAdditionalCharsController::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);


//Маршруты с авторизацией
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::get('user', [ApiAuthController::class, 'user']);
    Route::get('logout', [ApiAuthController::class, 'logout']);
});

Route::prefix('categories')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [ApiCategoriesController::class, 'store']);
    Route::patch('{id}', [ApiCategoriesController::class, 'update']);
    Route::delete('{id}', [ApiCategoriesController::class, 'destroy']);
});

Route::prefix('goods')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [ApiGoodsController::class, 'store']);
    Route::patch('{id}', [ApiGoodsController::class, 'update']);
    Route::delete('{id}', [ApiGoodsController::class, 'destroy']);
});

Route::prefix('baskets')->middleware('auth:sanctum')->group(function () {
    Route::get('own_basket', [ApiBasketsController::class, 'ownBasket']);
    Route::post('/', [ApiBasketsController::class, 'store']);
    Route::delete('purge', [ApiBasketsController::class, 'purge']);
    Route::delete('{id}', [ApiBasketsController::class, 'destroy']);
});

Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ApiOrdersController::class, 'index']);
    Route::get('own_orders', [ApiOrdersController::class, 'ownOrders']);
    Route::get('{id}', [ApiOrdersController::class, 'show']);
    Route::post('/', [ApiOrdersController::class, 'store']);
    Route::patch('{id}', [ApiOrdersController::class, 'update']);
});
