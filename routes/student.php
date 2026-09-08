<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/dashboard', fn () => abort(404))->name('dashboard');
    Route::get('/my-results', fn () => abort(404))->name('student.results');
    Route::get('/my-placement', fn () => abort(404))->name('student.placement');
});
