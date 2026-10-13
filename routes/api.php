<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/response', [App\Http\Controllers\PaymentController::class, 'response'])->name('response');
Route::get('/delegate', [App\Http\Controllers\RegistrationController::class, 'getDelegateCount'])->name('delegate');
Route::get('/workshop', [App\Http\Controllers\RegistrationController::class, 'getWorkshopCount'])->name('workshop');

// SBI ePay Key & Connectivity Diagnostic Test API
Route::get('/sbi/check-keys', [App\Http\Controllers\SbiTestController::class, 'checkKeys'])->name('api.sbi.check-keys');

// Direct Gateway Hit POST API - Creates Order and returns Payment Page URL
Route::match(['get', 'post'], '/sbi/create-order', [App\Http\Controllers\SbiTestController::class, 'createTestOrder'])->name('api.sbi.create-order');


