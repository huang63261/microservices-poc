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

Route::resource('orders', \App\Http\Controllers\Api\OrderController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy']);

Route::resource('order-details', \App\Http\Controllers\Api\OrderDetailController::class)
    ->only(['index', 'show']);
