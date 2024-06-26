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

Route::post('/mail', [\App\Http\Controllers\Api\MailController::class, 'mail'])->name('mail');
Route::post('/pubsub/push', [\App\Http\Controllers\Api\MailController::class, 'handlePubSubPush'])->name('pubsub.push');
