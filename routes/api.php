<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CategoriesApiController;
use App\Http\Controllers\Api\GoodsApiController;
use Illuminate\Http\Request;
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
    Route::post('register', [AuthApiController::class,'register']);
    Route::post('login', [AuthApiController::class,'login']);
});

Route::get('categories', [CategoriesApiController::class, 'index']);
//Route::get('goods', [GoodsApiController::class, 'index']);
Route::prefix('goods')->group(function () {
    Route::get('/', [GoodsApiController::class,'index']);
    Route::get('slug/{slug}', [GoodsApiController::class,'slug']);
});


//Маршруты с обязательной авторизацией
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::post('user', [AuthApiController::class,'user']);
    Route::post('logout', [AuthApiController::class,'logout']);
});
