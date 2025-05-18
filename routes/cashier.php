<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cashier\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Cashier\ReportController;

use App\Http\Controllers\CashierController;
use App\Http\Controllers\CashierSideBarController;
use App\Http\Controllers\ReportGenerationController;
use App\Http\Controllers\StudentSearchController;

Route::prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', [CashierSideBarController::class, 'dashboard'])->name('dashboard');
    Route::get('/payment/process', [CashierSideBarController::class, 'processPayment'])->name('payment.process');
    Route::get('/reports', [CashierSideBarController::class, 'reportsIndex'])->name('reports.index');

    // routes/web.php

});
// Billing Routes
Route::prefix('billings')->group(function () {
    Route::get('/', [BillingController::class, 'index'])->name('billings.index'); // List all billings
    Route::get('/{id}/details', [BillingController::class, 'details'])->name('billings.details'); // Show billing details
    Route::get('/{id}/edit', [BillingController::class, 'edit'])->name('billings.edit'); // Edit billing
    Route::put('/{id}', [BillingController::class, 'update'])->name('billings.update'); // Update billing
    Route::delete('/{id}', [BillingController::class, 'destroy'])->name('billings.destroy'); // Delete billing
    Route::post('/payment/store', [PaymentController::class, 'store'])->name('payment.store');
});

Route::get('/api/search-students', [StudentSearchController::class, 'search']);



Route::get('/reports', [ReportGenerationController::class, 'index'])->name('reports.index');
Route::post('/reports/generate', [ReportGenerationController::class, 'generate'])->name('reports.generate');