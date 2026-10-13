<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/response', [App\Http\Controllers\PaymentController::class, 'response'])->name('response');
Route::get('/delegate', [App\Http\Controllers\RegistrationController::class, 'getDelegateCount'])->name('delegate');
Route::get('/workshop', [App\Http\Controllers\RegistrationController::class, 'getWorkshopCount'])->name('workshop');
