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

Route::get('/product-reviews', [\App\Http\Controllers\Api\ProductReviewController::class, 'index'])->name('product-reviews.index');
Route::get('/product-reviews/{productReview}', [\App\Http\Controllers\Api\ProductReviewController::class, 'show'])->name('product-reviews.show');
Route::post('/product-reviews/batch-loading', [\App\Http\Controllers\Api\ProductReviewController::class, 'getReviewsBatch'])->name('product-reviews.batch');
Route::post('/product-reviews', [\App\Http\Controllers\Api\ProductReviewController::class, 'store'])->name('product-reviews.store');
Route::put('/product-reviews/{productReview}', [\App\Http\Controllers\Api\ProductReviewController::class, 'update'])->name('product-reviews.update');
Route::delete('/product-reviews/{productReview}', [\App\Http\Controllers\Api\ProductReviewController::class, 'destroy'])->name('product-reviews.destroy');
