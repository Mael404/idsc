<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VPAdminSideBarController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/dashboard', function () {
    // Force logout and redirect with message
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect()->route('login')->with('message', 'Session expired or invalid role. Please log in again.');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
require __DIR__ . '/vpadminsidebar.php';
require __DIR__ . '/registrarsidebar.php';
require __DIR__ . '/vpacademicssidebar.php';
require __DIR__ . '/cashier.php';
require __DIR__ . '/accounting.php';
require __DIR__ . '/president.php';
