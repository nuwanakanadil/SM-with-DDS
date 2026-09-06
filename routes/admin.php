<?php

use App\Http\Controllers\Admin\AnalysisController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\AssessmentResultController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Enums\Permissions;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin|staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardController::class)
            ->middleware('can:'.Permissions::ViewDashboard->value)
            ->name('dashboard');
        Route::get('/analysis', AnalysisController::class)
            ->middleware('can:'.Permissions::ViewAnalysis->value)
            ->name('analysis');

        Route::resource('students', StudentController::class)
            ->except(['show'])
            ->middlewareFor(['index'], 'can:'.Permissions::ViewStudents->value)
            ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'can:'.Permissions::ManageStudents->value);
        Route::post('/students/{student}/resend-login', [StudentController::class, 'resendLogin'])
            ->middleware('can:'.Permissions::ManageStudents->value)
            ->name('students.resend-login');

        Route::resource('assessments', AssessmentController::class)
            ->except(['show'])
            ->middlewareFor(['index'], 'can:'.Permissions::ViewAssessments->value)
            ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'can:'.Permissions::ManageAssessments->value);
        Route::post('/assessments/{assessment}/publish', [AssessmentController::class, 'publish'])
            ->middleware('can:'.Permissions::ManageAssessments->value)
            ->name('assessments.publish');
        Route::post('/assessments/{assessment}/unpublish', [AssessmentController::class, 'unpublish'])
            ->middleware('can:'.Permissions::ManageAssessments->value)
            ->name('assessments.unpublish');

        Route::resource('results', AssessmentResultController::class)
            ->parameters(['results' => 'result'])
            ->except(['show'])
            ->middlewareFor(['index'], 'can:'.Permissions::ViewResults->value)
            ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'can:'.Permissions::ManageResults->value);

        Route::resource('staff', StaffController::class)->middleware('can:manage_staff')->except(['show']);
        Route::post('/staff/{staff}/resend-login', [StaffController::class, 'resendLogin'])->middleware('can:manage_staff')->name('staff.resend-login');
    });
