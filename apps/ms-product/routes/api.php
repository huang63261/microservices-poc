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
Route::resource('/products', \App\Http\Controllers\Api\ProductController::class);
Route::get('/products-browse', [\App\Http\Controllers\Api\BrowseController::class, 'index']);
Route::resource('/product-categories', \App\Http\Controllers\Api\ProductCategoryController::class);
Route::post('/pricing', [\App\Http\Controllers\Api\PricingController::class, 'pricing']);