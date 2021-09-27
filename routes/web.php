<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GoodsController;
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
    return view('index');
});

Route::get('goods/regenerate_db', [GoodsController::class, 'regenerate_db'])->name('goods.regenerate_db');

Route::resource('categories', CategoriesController::class);
Route::resource('goods', GoodsController::class);

Auth::routes();
