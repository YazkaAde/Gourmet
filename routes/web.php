<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckBlacklist;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CrewController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Admin\BlacklistController;
use App\Http\Controllers\Admin\NumberTableController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

// Halaman untuk akun yang diblacklist
Route::get('/blacklisted', function () {
    return view('auth.blacklisted');
})->name('blacklisted');

// Routes untuk semua role yang login
Route::middleware(['auth', CheckBlacklist::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Admin Routes
Route::middleware(['auth', 'role:admin', CheckBlacklist::class])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // rute menu
    Route::resource('menus', MenuController::class)->except(['show']);

    // rute categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // rute meja
    Route::resource('tables', NumberTableController::class)->except(['show']);

    // rute karyawan
    Route::resource('crews', CrewController::class)->except(['show']);

    // rute blacklist
    Route::prefix('blacklist')->group(function() {
        Route::get('/', [BlacklistController::class, 'index'])->name('blacklist.index');
        Route::post('/', [BlacklistController::class, 'store'])->name('blacklist.store');
        Route::delete('/{id}', [BlacklistController::class, 'destroy'])->name('blacklist.destroy');
    });
});

// Cashier Routes
Route::middleware(['auth', 'role:cashier', CheckBlacklist::class])->prefix('cashier')->name('cashier.')->group(function() {
    Route::get('/dashboard', function () {
        return view('cashier.dashboard');
    })->name('dashboard');
});

/// Customer Routes
Route::middleware(['auth', 'role:customer', CheckBlacklist::class])->group(function() {
    // Menu Routes
    Route::prefix('menu')->name('customer.menu.')->group(function() {
        Route::get('/', [App\Http\Controllers\Customer\MenuController::class, 'index'])->name('index');
    });

    // Cart Routes
    Route::prefix('cart')->name('customer.cart.')->group(function() {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store');
        Route::patch('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartController::class, 'destroy'])->name('destroy');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    });

    // Order Routes (jika menggunakan OrderController terpisah)
    Route::prefix('orders')->name('customer.orders.')->group(function() {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    });
});

require __DIR__.'/auth.php';
