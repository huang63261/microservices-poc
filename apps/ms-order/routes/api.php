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

Route::get('orders', [\App\Http\Controllers\Api\OrderController::class, 'index'])->name('orders.index');
Route::post('orders', [\App\Http\Controllers\Api\OrderController::class, 'store'])->name('orders.store');
Route::get('orders/{order}', [\App\Http\Controllers\Api\OrderController::class, 'show'])->name('orders.show');
Route::put('orders/{order}', [\App\Http\Controllers\Api\OrderController::class, 'update'])->name('orders.update');
Route::delete('orders/{order}', [\App\Http\Controllers\Api\OrderController::class, 'destroy'])->name('orders.destroy');
Route::post('orders/cancel', [\App\Http\Controllers\Api\OrderController::class, 'cancel'])->name('orders.cancel');

Route::resource('order-details', \App\Http\Controllers\Api\OrderDetailController::class)
    ->only(['index', 'show']);
