<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\CategoryController;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware(['auth:sanctum'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });
});

Route::prefix('v1')->name('users.')->middleware(['auth:sanctum'])->group(function () {

    Route::prefix('categories')->group(function () {

        Route::prefix('{user}')->group(function () {

            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::post('/', [CategoryController::class, 'store'])->name('store');

            Route::prefix('{category}')->group(function () {
                Route::get('/', [CategoryController::class, 'show'])->name('show');
                Route::put('/', [CategoryController::class, 'update'])->name('update');
                Route::delete('/', [CategoryController::class, 'destroy'])->name('destroy');
            });

        });

    });

});



