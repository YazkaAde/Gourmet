<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\NumberTableController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

// Routes untuk semua role yang login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // rute menu
    Route::resource('menus', MenuController::class)->except(['show']);

        // rute categories
        Route::resource('categories', CategoryController::class)->except(['show']);

        // rute meja
        Route::resource('tables', NumberTableController::class)->except(['show']);
    });

// Cashier Routes
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->name('cashier.')->group(function() {
    Route::get('/dashboard', function () {
        return view('cashier.dashboard');
    })->name('dashboard');
    
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function() {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
});

require __DIR__.'/auth.php';