<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VPAcademicsSideBarController;

// Applying 'auth' middleware to all routes within the group --------------------
Route::middleware('auth')->group(function () {

    // VP ACADEMICS --------------------
    Route::get('/vpacademics_dashboard', [VPAcademicsSideBarController::class, 'vpAdminDashboard'])->name('vp_academic.vpacademic_db');

    // Scheduling Routes --------------------
    Route::get('/scheduling/room-assignment', [VPAcademicsSideBarController::class, 'roomAssignment'])->name('scheduling.room-assignment');
    Route::get('/scheduling/faculty-load', [VPAcademicsSideBarController::class, 'facultyLoad'])->name('scheduling.faculty-load');
    Route::get('/scheduling/schedule-classes', [VPAcademicsSideBarController::class, 'scheduleClasses'])->name('scheduling.schedule-classes');

    // Faculty Evaluation Radar --------------------
    Route::get('/faculty/evaluation-radar', [VPAcademicsSideBarController::class, 'evaluationRadar'])->name('faculty.evaluation-radar');

    // Analytics --------------------
    Route::get('/analytics', [VPAcademicsSideBarController::class, 'analytics'])->name('analytics.index');

    // Course Management --------------------
    Route::get('/programs', [VPAcademicsSideBarController::class, 'programs'])->name('vpacademic.programs');
    Route::get('/courses', [VPAcademicsSideBarController::class, 'courses'])->name('vpacademic.courses');

    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');


    Route::resource('courses', CourseController::class);
    // In your routes/web.php
    Route::post('/courses/{id}/toggle', [CourseController::class, 'toggleActive'])->name('courses.toggleActive');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
});
