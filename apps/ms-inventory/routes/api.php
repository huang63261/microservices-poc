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

Route::get('/inventories', [\App\Http\Controllers\Api\InventoryController::class, 'index'])->name('inventories.index');
Route::get('/inventories/{inventory}', [\App\Http\Controllers\Api\InventoryController::class, 'show'])->name('inventories.show');
Route::post('/inventories/batch-loading', [\App\Http\Controllers\Api\InventoryController::class, 'getInventoriesBatch'])->name('inventories.batch-loading');
Route::post('/inventories', [\App\Http\Controllers\Api\InventoryController::class, 'store'])->name('inventories.store');
Route::put('/inventories/{inventory}', [\App\Http\Controllers\Api\InventoryController::class, 'update'])->name('inventories.update');
Route::delete('/inventories/{inventory}', [\App\Http\Controllers\Api\InventoryController::class, 'destroy'])->name('inventories.destroy');
