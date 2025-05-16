<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\MiscFeeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SemestersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VPAcademicsSideBarController;
use App\Http\Controllers\ProgramCourseMappingController;
use App\Http\Controllers\YearLevelController;

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

    // Resource route — defines index, store, show, update, destroy, etc.
    Route::resource('courses', CourseController::class)->except(['create', 'edit']);  // Avoid duplicates

    // Custom action not included in resource
    Route::post('/courses/{id}/toggle', [CourseController::class, 'toggleActive'])->name('courses.toggleActive');

    // Program Routes --------------------
    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::put('/programs/{id}', [ProgramController::class, 'update'])->name('programs.update');
    Route::post('programs/{id}/toggleActive', [ProgramController::class, 'toggleActive'])->name('programs.toggleActive');
    Route::delete('/programs/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy');

    // Year Level Routes --------------------
    Route::get('/year', [YearLevelController::class, 'index'])->name('year_levels.index');
    Route::post('/year', [YearLevelController::class, 'store'])->name('year_levels.store');
    Route::put('/year/{id}', [YearLevelController::class, 'update'])->name('year_levels.update');
    Route::delete('/year/{id}', [YearLevelController::class, 'destroy'])->name('year_levels.destroy');

    // Semester Routes --------------------
    Route::get('/semester', [SemestersController::class, 'index'])->name('semesters.index');
    Route::post('/semester', [SemestersController::class, 'store'])->name('semesters.store');
    Route::put('/semesters/{id}', [SemestersController::class, 'update'])->name('semester.update');
    Route::delete('/semester/{id}', [SemestersController::class, 'destroy'])->name('semesters.destroy');

    // Program Mapping --------------------
    Route::get('/program-mapping', [ProgramCourseMappingController::class, 'index'])->name('program.mapping.index');
    Route::delete('/program-mapping/{id}', [ProgramCourseMappingController::class, 'destroy'])->name('program.mapping.destroy');
    Route::post('/program-mapping', [ProgramCourseMappingController::class, 'store'])->name('program.mapping.store');
    // Archive a Program Mapping
    Route::post('/program-mapping/{id}/archive', [ProgramCourseMappingController::class, 'archive'])->name('program.mapping.archive');
    // Restore a Program Mapping
    Route::post('/program-mapping/{id}/restore', [ProgramCourseMappingController::class, 'restore'])->name('program.mapping.restore');
    Route::post('/program-mapping/{id}/toggle-active', [ProgramCourseMappingController::class, 'toggleActive'])->name('program.mapping.toggleActive');
    Route::put('/program-mapping/{id}', [ProgramCourseMappingController::class, 'update'])->name('program.mapping.update');
    Route::delete('/program/{program_id}/course/{course_id}/remove', [ProgramController::class, 'removeCourse'])->name('program.mapping.remove');

    // Misc Fees --------------------
    Route::post('/misc-fees', [MiscFeeController::class, 'store'])->name('misc-fees.store');
    Route::delete('/misc-fees/{id}', [MiscFeeController::class, 'destroy'])->name('misc-fees.destroy');
});
