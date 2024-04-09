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

Route::post('/inventory-lock', [App\Http\Controllers\Api\TestController::class, 'inventoryLock']);

Route::post('/checkout', [App\Http\Controllers\Api\CheckOutController::class, 'checkoutProcess']);
Route::post('/payment', [App\Http\Controllers\Api\CheckOutController::class, 'paymentProcess']);