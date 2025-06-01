<?php

use App\Http\Controllers\AccountantDashboardController;
use App\Http\Controllers\AccountantTransactionController;
use App\Http\Controllers\AccountingSideBarController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\SOAController;
use Illuminate\Support\Facades\Route;



Route::prefix('accountant')->name('accountant.')->group(function () {
  Route::get('/accountant_db', [AccountingSideBarController::class, 'dashboard'])->name('accountant_db');
  Route::get('/transactions', [AccountingSideBarController::class, 'transactions'])->name('transactions');
  Route::get('/pending-voids', [AccountingSideBarController::class, 'pendingVoids'])->name('pending_voids');


  Route::get('/soa', [AccountingSideBarController::class, 'soa'])->name('soa');
  Route::get('/student-ledger', [AccountingSideBarController::class, 'studentLedger'])->name('student_ledger');
  Route::get('/promisories', [AccountingSideBarController::class, 'promisories'])->name('promisories');
  Route::get('/accountant/dashboard', [AccountantDashboardController::class, 'index'])
    ->name('accountant.dashboard');

  Route::get('/admissions', [SOAController::class, 'index'])->name('admissions.index');
});

Route::post('/accounting/voids/{payment}/approve', [AccountingSideBarController::class, 'approveVoid'])->name('accounting.voids.approve');
Route::post('/accounting/voids/{payment}/reject', [AccountingSideBarController::class, 'rejectVoid'])->name('accounting.voids.reject');
