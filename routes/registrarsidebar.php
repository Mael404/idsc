<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\RegistrarSideBarController;
use Illuminate\Support\Facades\Route;

// Apply 'auth' middleware to the whole 'registrar' prefix group
Route::prefix('registrar')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('dashboard', [RegistrarSideBarController::class, 'dashboard'])->name('registrar.dashboard');

    // Enrollment
    Route::get('enrollment/manage', [RegistrarSideBarController::class, 'quickSearch'])->name('registrar.enrollment.manage');
    Route::get('enrollment/pending', [RegistrarSideBarController::class, 'bulkUpload'])->name('registrar.enrollment.pending');

    // Student Records
    Route::get('records/search', [RegistrarSideBarController::class, 'quickSearch'])->name('registrar.records.search');
    Route::get('records/update', [RegistrarSideBarController::class, 'bulkUpload'])->name('registrar.records.update');

    // Requests
    Route::get('requests/express-processing', [RegistrarSideBarController::class, 'expressProcessing'])->name('registrar.requests.express_processing');
    Route::get('requests/notify-student', [RegistrarSideBarController::class, 'notifyStudent'])->name('registrar.requests.notify_student');

    // Archive
    Route::get('archive/old-student-records', [RegistrarSideBarController::class, 'oldStudentRecords'])->name('registrar.archive.old_student_records');
    Route::get('archive/disposal-log', [RegistrarSideBarController::class, 'disposalLog'])->name('registrar.archive.disposal_log');
});

// Admissions Routes (without the 'registrar' prefix)
Route::get('admissions', [AdmissionController::class, 'index'])->name('admissions.index');
Route::get('admissions/create', [AdmissionController::class, 'create'])->name('admissions.create');
Route::post('admissions', [AdmissionController::class, 'store'])->name('admissions.store');
