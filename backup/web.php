<?php

use Illuminate\Support\Facades\Route;

// Dashboard Routes for VP ADMIN
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');



Route::get('/vpadmin_dashboard', function () {
    return view('vp_admin.vpadmin_db');
});

Route::get('/blank_page', function () {
    return view('vp_admin.vpadmin_blank');
});

// Fees Routes
Route::prefix('fees')->group(function () {
    Route::get('/edit-tuition', function () {
        return view('vp_admin.fees.edit_tuition');
    });
    Route::get('/misc-fees', function () {
        return view('vp_admin.fees.misc_fees');
    });
});

// Academic Routes
Route::prefix('academic')->group(function () {
    Route::get('/term-configuration', function () {
        return view('vp_admin.academic.term_configuration');
    });
});

// User Management Routes
Route::prefix('user-management')->group(function () {
    Route::get('/add-new', function () {
        return view('vp_admin.user_management.add_new');
    });
    Route::get('/manage', function () {
        return view('vp_admin.user_management.manage');
    });
    Route::get('/activate', function () {
        return view('vp_admin.user_management.activate');
    });
});
