<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VPAdminSideBarController;

Route::get('/vp-admin-db', [VPAdminSideBarController::class, 'dashboard'])
    ->middleware('auth')
    ->name('vpadmin.vpadmin_db');

Route::get('/blank_page', [VPAdminSideBarController::class, 'blankPage']);
Route::get('/vpadmin_dashboard', [VPAdminSideBarController::class, 'dashboard']);

Route::prefix('fees')->group(function () {
    Route::get('/edit-tuition', [VPAdminSideBarController::class, 'editTuition']);
    Route::get('/misc-fees', [VPAdminSideBarController::class, 'miscFees']);
});

Route::prefix('academic')->group(function () {
    Route::get('/term-configuration', [VPAdminSideBarController::class, 'termConfiguration']);
});

Route::prefix('user-management')->group(function () {
    Route::get('/add-new', [VPAdminSideBarController::class, 'addNewUser']);
    Route::get('/manage', [VPAdminSideBarController::class, 'manageUsers']);
    Route::get('/activate', [VPAdminSideBarController::class, 'activateUsers']);
});
