<?php

use App\Http\Controllers\SchoolYearController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VPAdminSideBarController;
//s

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

// School Year Routes (Cleaned)
Route::get('school-years', [SchoolYearController::class, 'index'])->name('school-years.index');
Route::post('school-years', [SchoolYearController::class, 'store'])->name('school-years.store');
Route::delete('school-years/{id}', [SchoolYearController::class, 'destroy'])->name('school-years.destroy');
Route::patch('school-years/{id}', [SchoolYearController::class, 'update'])->name('school-years.update');  // <-- This one for updating
Route::patch('school-years/{id}/archive', [SchoolYearController::class, 'archive'])->name('school-years.archive');
Route::patch('school-years/{id}/set-active', [SchoolYearController::class, 'setActive'])->name('school-years.set-active');
Route::patch('school-years/{id}/restore', [SchoolYearController::class, 'restore'])->name('school-years.restore');
Route::delete('school-years/{id}/force-delete', [SchoolYearController::class, 'forceDelete'])->name('school-years.forceDelete');
