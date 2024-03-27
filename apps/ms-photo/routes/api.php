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

Route::get('/photos', [\App\Http\Controllers\Api\PhotoController::class, 'index'])->name('photos.index');
Route::get('/photos/{photo}', [\App\Http\Controllers\Api\PhotoController::class, 'show'])->name('photos.show');
Route::post('/photos/batch-loading', [\App\Http\Controllers\Api\PhotoController::class, 'getPhotosBatch'])->name('photos.batch-loading');
Route::post('/photos', [\App\Http\Controllers\Api\PhotoController::class, 'store'])->name('photos.store');
Route::put('/photos/{photo}', [\App\Http\Controllers\Api\PhotoController::class, 'update'])->name('photos.update');
Route::delete('/photos/{photo}', [\App\Http\Controllers\Api\PhotoController::class, 'destroy'])->name('photos.destroy');

