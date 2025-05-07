<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\RegistrarSideBarController;
use Illuminate\Support\Facades\Route;

Route::prefix('registrar')->group(function () {
    Route::get('dashboard', [RegistrarSideBarController::class, 'dashboard'])->name('registrar.dashboard');

    // Records
    Route::get('records/quick-search', [RegistrarSideBarController::class, 'quickSearch'])->name('registrar.records.quick_search');
    Route::get('records/bulk-upload', [RegistrarSideBarController::class, 'bulkUpload'])->name('registrar.records.bulk_upload');

    // Requests
    Route::get('requests/express-processing', [RegistrarSideBarController::class, 'expressProcessing'])->name('registrar.requests.express_processing');
    Route::get('requests/notify-student', [RegistrarSideBarController::class, 'notifyStudent'])->name('registrar.requests.notify_student');

    // Archive
    Route::get('archive/old-student-records', [RegistrarSideBarController::class, 'oldStudentRecords'])->name('registrar.archive.old_student_records');
    Route::get('archive/disposal-log', [RegistrarSideBarController::class, 'disposalLog'])->name('registrar.archive.disposal_log');

    Route::get('/admissions', [AdmissionController::class, 'index'])->name('admissions.index');
    Route::post('/admissions', [AdmissionController::class, 'store'])->name('admissions.store');
});
