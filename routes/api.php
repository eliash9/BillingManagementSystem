<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('services', \App\Http\Controllers\Api\ServiceController::class)->names('api.services');
    Route::apiResource('invoices', \App\Http\Controllers\Api\InvoiceController::class)->names('api.invoices')->except(['store', 'update', 'destroy']);
    Route::post('invoices/{invoice}/pay', [\App\Http\Controllers\Api\InvoiceController::class, 'pay'])->name('api.invoices.pay');
});
