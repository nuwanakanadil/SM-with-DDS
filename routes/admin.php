<?php

use App\Http\Controllers\Admin\AnalysisController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\AssessmentResultController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin|staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/analysis', AnalysisController::class)->name('analysis');
        Route::resource('students', StudentController::class)->except(['show']);
        Route::post('/students/{student}/resend-login', [StudentController::class, 'resendLogin'])->name('students.resend-login');
        Route::resource('assessments', AssessmentController::class)->except(['show']);
        Route::post('/assessments/{assessment}/publish', [AssessmentController::class, 'publish'])->name('assessments.publish');
        Route::post('/assessments/{assessment}/unpublish', [AssessmentController::class, 'unpublish'])->name('assessments.unpublish');
        Route::resource('results', AssessmentResultController::class)->parameters(['results' => 'result'])->except(['show']);
        Route::resource('staff', StaffController::class)->middleware('can:manage_staff')->except(['show']);
        Route::post('/staff/{staff}/resend-login', [StaffController::class, 'resendLogin'])->middleware('can:manage_staff')->name('staff.resend-login');
    });
