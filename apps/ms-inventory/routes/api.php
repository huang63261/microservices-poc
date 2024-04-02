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

Route::prefix('/inventories')->group(function() {
    Route::get('/', [\App\Http\Controllers\Api\InventoryController::class, 'index'])->name('inventories.index');
    Route::get('/{inventory}', [\App\Http\Controllers\Api\InventoryController::class, 'show'])->name('inventories.show');
    Route::post('/batch-loading', [\App\Http\Controllers\Api\InventoryController::class, 'getInventoriesBatch'])->name('inventories.batch-loading');
    Route::post('/', [\App\Http\Controllers\Api\InventoryController::class, 'store'])->name('inventories.store');
    Route::put('/{inventory}', [\App\Http\Controllers\Api\InventoryController::class, 'update'])->name('inventories.update');
    Route::delete('/{inventory}', [\App\Http\Controllers\Api\InventoryController::class, 'destroy'])->name('inventories.destroy');
});

Route::post('inventories/check-availability', [\App\Http\Controllers\Api\InventoryCheckController::class, 'checkAvailability'])
    ->name('inventories.check-availability');

Route::get('/test', [\App\Http\Controllers\Api\TestController::class, 'index']);
