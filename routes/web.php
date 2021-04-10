<?php

use App\Http\Controllers\Product\ProductImportController;
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

Route::get('products', [ProductImportController::class, 'index'])->name('products.index');
Route::post('products/import', [ProductImportController::class, 'import'])->name('products.import');
