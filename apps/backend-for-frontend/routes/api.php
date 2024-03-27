<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show'])->name('products.show');
Route::post('/products', [\App\Http\Controllers\Api\ProductController::class, 'store'])->name('products.store');
Route::put('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'destroy'])->name('products.destroy');
