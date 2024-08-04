<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobListingController;
use App\Http\Middleware\IsEmployer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// authentication routes
Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::get('/me', 'getMe')->middleware('auth:sanctum');
});

// job listing routes
Route::controller(JobListingController::class)->prefix('jobs')->group(function () {

    // employer specific routes
    Route::middleware(['auth:sanctum', IsEmployer::class])->group(function () {
        Route::post('/', 'store');
        Route::get('/employer/{employedId}', 'getEmployerJobs');
        Route::put('/{jobListing}', 'update');
        Route::delete('/{jobListing}', 'destroy');
    });

    // public routes to search jobs
    Route::get('/', 'index');
});
