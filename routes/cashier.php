<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cashier\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Cashier\ReportController;

use App\Http\Controllers\CashierController;
use App\Http\Controllers\CashierSideBarController;
use App\Http\Controllers\ManualCashierController;
use App\Http\Controllers\OldAccountController;
use App\Http\Controllers\OldAccountPaymentController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\ReportGenerationController;
use App\Http\Controllers\ShsPaymentController;
use App\Http\Controllers\StudentSearchController;
use App\Http\Controllers\UniformPaymentController;
use Illuminate\Http\Request;

Route::prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', [CashierSideBarController::class, 'dashboard'])->name('dashboard');
    Route::get('/payment/process', [CashierSideBarController::class, 'processPayment'])->name('payment.process');
    Route::get('/reports', [CashierSideBarController::class, 'reportsIndex'])->name('reports.index');
    Route::post('/queue/update', [QueueController::class, 'updateQueue'])->name('queue.update');
    Route::post('/queue/recall/{sid}', [QueueController::class, 'updateRecall'])->name('queue.recall');
    // routes/web.php
    
    // shs payment route
     Route::get('/payment/shsprocess', [CashierSideBarController::class, 'shsPayment'])->name('payment.shs_pyment');
     Route::get('/reports/shs', [CashierSideBarController::class, 'shsReports'])->name('reports.shs');


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

    // shs payment route
    Route::post('/shs-payment/store', [ShsPaymentController::class, 'store'])->name('shspayment.store');
    Route::post('/payment/shs/mark-as-completed', [ShsPaymentController::class, 'markAsCompleted'])->name('payment.shs.markAsCompleted');

    // college payment route
    Route::post('/payment/college/mark-as-completed', [PaymentController::class, 'markAsCompleted'])->name('payment.college.markAsCompleted');

}); 

// Search Routes
Route::get('/api/search', [StudentSearchController::class, 'searchAllStudents']);
Route::get('/api/search-students', [StudentSearchController::class, 'search']);
Route::get('/api/search-shs-students', [StudentSearchController::class, 'shsSearch']);
Route::get('/api/search-old-students', [StudentSearchController::class, 'oldAccountSearch']);


Route::get('/reports', [ReportGenerationController::class, 'index'])->name('reports.index');
Route::post('/reports/generate', [ReportGenerationController::class, 'generate'])->name('reports.generate');

// college
Route::get('/cashier/payment/pending', [CashierSideBarController::class, 'pendingEnrollments'])->name('cashier.payment.pending');

// shs
Route::get('/cashier/shs-payment/pending', [CashierSideBarController::class, 'pendingShsEnrollments'])->name('cashier.payment.shspending');

Route::post('/cashier/confirm/{id}', [CashierSideBarController::class, 'confirmPending'])->name('cashier.confirm');
Route::post('/cashier/manualconfirm/{id}', [CashierSideBarController::class, 'manualconfirmPending'])->name('manualcashier.confirm');
Route::get('/cashier/payment/other', [CashierSideBarController::class, 'otherPayments'])->name('cashier.payment.other');

// uniform payment routes
Route::get('/cashier/payment/uniform', [CashierSideBarController::class, 'uniformPayments'])->name('cashier.payment.uniform');
Route::post('/payments/uniform-input', [UniformPaymentController::class, 'input'])->name('uniformpayment.input');

// uniform reports route
Route::get('/cashier/reports/uniform', [CashierSideBarController::class, 'reportUniformPayments'])->name('cashier.reports.uniform');

// Old accounts routes
Route::get('/cashier/payment/old-accounts', [CashierSideBarController::class, 'oldAccounts'])->name('cashier.payment.old'); // payments
Route::get('/cashier/reports/old', [CashierSideBarController::class, 'reportOldAccountPayments'])->name('cashier.reports.old'); //  reports
Route::post('/payments/input/old', [OldAccountPaymentController::class, 'input'])->name('oldpayment.input');
Route::post('/payments/void-old', [OldAccountPaymentController::class, 'voidPayment'])->name('payments.old-void');

Route::post('/payments/input', [PaymentController::class, 'input'])->name('payment.input');
Route::post('/payments/manualinput', [PaymentController::class, 'manualinput'])->name('manualpayment.input');
Route::get('/cashier/reports/other', [CashierSideBarController::class, 'reportOtherPayments'])->name('cashier.reports.other');


Route::get('/check-or-number', function (Request $request) {
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

    // reports 
    Route::get('/reports', [ManualCashierController::class, 'reportsIndex'])->name('reports.index');
    Route::get('/reports/other', [ManualCashierController::class, 'reportOtherPayments'])->name('reports.other');
});
