<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\PresidentSidebarController;
use Illuminate\Support\Facades\Route;

Route::get('/president/dashboard', [PresidentSidebarController::class, 'dashboard'])->name('president.dashboard');
Route::get('/president/revenue-trends', [PresidentSidebarController::class, 'revenueTrends'])->name('president.revenue-trends');
Route::get('/president/scholarships-discounts', [PresidentSidebarController::class, 'scholarshipsDiscounts'])->name('president.scholarships-discounts');
Route::get('/president/enrollment-heatmap', [PresidentSidebarController::class, 'enrollmentHeatmap'])->name('president.enrollment-heatmap');
Route::get('/president/financial-alerts', [PresidentSidebarController::class, 'financialAlerts'])->name('president.financial-alerts');
Route::get('/api/revenue-trends', [BillingController::class, 'getRevenueTrends']);
Route::get('/api/balance-due', [BillingController::class, 'getBalanceDue']);
