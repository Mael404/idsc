<?php

use App\Http\Controllers\AllAccessController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

Route::prefix('all')->name('all.')->group(function () {
    Route::get('/dashboard', [AllAccessController::class, 'dashboard'])->name('dashboard');
    Route::get('/queueing', [AllAccessController::class, 'queueing'])->name('queueing');
    Route::get('/queueing/latest', [QueueController::class, 'getLatestQueue']);
    Route::post('/queueing/save', [QueueController::class, 'saveQueue'])->name('queueing.save');

});
