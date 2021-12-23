<?php

use App\Http\Controllers\AdditionalCharsController;
use App\Http\Controllers\BasketsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ShopController;
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

Route::get('/', [ShopController::class, 'index']);

Route::get('goods/regenerateDb', [GoodsController::class, 'regenerateDb'])->name('goods.regenerateDb');

Route::resource('categories', CategoriesController::class);
Route::resource('goods', GoodsController::class);
Route::resource('additionalChars', AdditionalCharsController::class);
Route::resource('basket', BasketsController::class, ['only' => ['index', 'store', 'update', 'destroy']]);
Route::resource('orders', OrdersController::class);

Auth::routes();
