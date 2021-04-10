<?php

use App\Http\Controllers\Product\ProductImportController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('products', [ProductImportController::class, 'index'])->name('products.index');
Route::post('products/import', [ProductImportController::class, 'import'])->name('products.import');
