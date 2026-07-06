<?php

use App\Http\Controllers\Admin\AnalysisController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\AssessmentResultController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin|staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::redirect('/', '/admin/analysis')->name('dashboard');
        Route::get('/analysis', AnalysisController::class)->name('analysis');
        Route::resource('students', StudentController::class)->except(['show']);
        Route::resource('assessments', AssessmentController::class)->except(['show']);
        Route::resource('results', AssessmentResultController::class)->parameters(['results' => 'result'])->except(['show']);
        Route::resource('staff', StaffController::class)->middleware('can:manage_staff')->except(['show']);
    });
