<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cashier\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Cashier\ReportController;

use App\Http\Controllers\CashierController;
use App\Http\Controllers\CashierSideBarController;
use App\Http\Controllers\ManualCashierController;
use App\Http\Controllers\ReportGenerationController;
use App\Http\Controllers\StudentSearchController;
use Illuminate\Http\Request;

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
     Route::post('/manualpayment/store', [PaymentController::class, 'manualstore'])->name('manualpayment.store');
    
});

Route::get('/api/search-students', [StudentSearchController::class, 'search']);



Route::get('/reports', [ReportGenerationController::class, 'index'])->name('reports.index');
Route::post('/reports/generate', [ReportGenerationController::class, 'generate'])->name('reports.generate');

Route::get('/cashier/payment/pending', [CashierSideBarController::class, 'pendingEnrollments'])->name('cashier.payment.pending');

Route::post('/cashier/confirm/{id}', [CashierSideBarController::class, 'confirmPending'])->name('cashier.confirm');
Route::post('/cashier/manualconfirm/{id}', [CashierSideBarController::class, 'manualconfirmPending'])->name('manualcashier.confirm');
Route::get('/cashier/payment/other', [CashierSideBarController::class, 'otherPayments'])->name('cashier.payment.other');

Route::post('/payments/input', [PaymentController::class, 'input'])->name('payment.input');
Route::post('/payments/manualinput', [PaymentController::class, 'manualinput'])->name('manualpayment.input');
Route::get('/cashier/reports/other', [CashierSideBarController::class, 'reportOtherPayments'])->name('cashier.reports.other');
Route::get('/check-or-number', function(Request $request) {
    $exists = \App\Models\Payment::where('or_number', $request->or_number)->exists();
    return response()->json(['exists' => $exists]);
});
Route::get('/check-or-number', function (Request $request) {
    $orNumber = $request->query('or_number');
    $exists = \App\Models\Payment::where('or_number', $orNumber)->exists();
    return response()->json(['exists' => $exists]);
});
Route::post('/payments/void', [PaymentController::class, 'voidPayment'])->name('payments.void');

Route::post('/payments/void-other', [PaymentController::class, 'voidOtherPayment'])
    ->name('payments.other-void');

Route::prefix('manual_cashier')->name('manual_cashier.')->group(function () {
    Route::get('/dashboard', [ManualCashierController::class, 'dashboard'])->name('dashboard');
    Route::get('/payment/process', [ManualCashierController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/pending', [ManualCashierController::class, 'pendingEnrollments'])->name('payment.pending');
    Route::post('/payment/pending/confirm/{id}', [ManualCashierController::class, 'confirmPending'])->name('payment.pending.confirm');
    Route::get('/payment/other', [ManualCashierController::class, 'otherPayments'])->name('payment.other');

    Route::get('/reports', [ManualCashierController::class, 'reportsIndex'])->name('reports.index');
    Route::get('/reports/other', [ManualCashierController::class, 'reportOtherPayments'])->name('reports.other');
});