<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public Billing Widget & Portal Routes
Route::get('/widget/v1/billing.js', [\App\Http\Controllers\WidgetController::class, 'script'])->name('widget.script');
Route::get('/portal/service/{token}', [\App\Http\Controllers\WidgetController::class, 'portal'])->name('widget.portal');
Route::get('/portal/service/{token}/invoice/{invoice}', [\App\Http\Controllers\WidgetController::class, 'invoice'])->name('widget.invoice');

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

    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__ . '/auth.php';
