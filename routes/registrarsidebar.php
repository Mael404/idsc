<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\ProgramCourseMappingController;
use App\Http\Controllers\RegistrarSideBarController;
use Illuminate\Support\Facades\Route;

// Apply 'auth' middleware to the whole 'registrar' prefix group
Route::prefix('registrar')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('dashboard', [RegistrarSideBarController::class, 'dashboard'])->name('registrar.dashboard');

    // Enrollment
    Route::get('enrollment/manage', [RegistrarSideBarController::class, 'quickSearch'])->name('registrar.enrollment.manage');
    Route::get('enrollment/pending', [RegistrarSideBarController::class, 'bulkUpload'])->name('registrar.enrollment.pending');

    // ✅ New Enrollment Pages
    Route::get('enrollment/new', [RegistrarSideBarController::class, 'newEnrollment'])->name('registrar.enrollment.new');
    Route::get('enrollment/transferee', [RegistrarSideBarController::class, 'transfereeEnrollment'])->name('registrar.enrollment.transferee');
    Route::get('enrollment/re-enroll/regular', [RegistrarSideBarController::class, 'reEnrollRegular'])->name('registrar.enrollment.reenroll.regular');
    Route::get('enrollment/re-enroll/irregular', [RegistrarSideBarController::class, 'reEnrollIrregular'])->name('registrar.enrollment.reenroll.irregular');

    Route::get('enrollment/records', [RegistrarSideBarController::class, 'enrollmentRecords'])->name('registrar.enrollment.records');

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

Route::get('/admissions/{student_id}', [AdmissionController::class, 'show'])->name('admissions.show');

Route::get('/admissions/{student_id}/print-cor', [AdmissionController::class, 'printCOR'])->name('admissions.printCOR');
Route::post('/calculate-tuition-fee', [AdmissionController::class, 'calculateTuitionFee'])->name('calculate.tuition.fee');


Route::get('/program-course-mapping/{id}/total-units', [ProgramCourseMappingController::class, 'getTotalUnits']);

Route::get('/get-total-units', [AdmissionController::class, 'getTotalUnits'])->name('get.total.units');
Route::post('/get-mapping-units', [AdmissionController::class, 'getMappingUnits'])->name('getMappingUnits');
Route::put('/billing/{studentId}/initial-payment', [AdmissionController::class, 'updateInitialPayment'])
    ->name('billing.updateInitialPayment');

    