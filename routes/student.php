<?php

use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\MyResultController;
use App\Http\Controllers\Student\PlacementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/my-results', MyResultController::class)->name('student.results');
    Route::get('/my-placement', PlacementController::class)->name('student.placement');
});
