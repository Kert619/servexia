<?php

use App\Enums\Page;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([])->group(function () {
    Route::get('/', function () {
        return Inertia::render(Page::HomePage);
    });

    Route::prefix('admin')->group(function () {
        Route::get('dashboard', DashboardController::class);

        Route::resource('services', ServiceController::class);

        Route::resource('categories', CategoryController::class);
    });
});

Route::get('/login', function () {
    return Inertia::render(Page::AuthLoginPage);
})->name('login');
