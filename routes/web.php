<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Minimal Admin Interfaces
    Route::get('/services', [\App\Http\Controllers\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [\App\Http\Controllers\ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [\App\Http\Controllers\ServiceController::class, 'store'])->name('services.store');
    Route::post('/services/{service}/generate-invoice', [\App\Http\Controllers\ServiceController::class, 'generateInvoice'])->name('services.generate-invoice');

    // Customers
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);

    Route::get('/invoices', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{invoice}/pay', [\App\Http\Controllers\InvoiceController::class, 'pay'])->name('invoices.pay');
});

require __DIR__ . '/auth.php';
