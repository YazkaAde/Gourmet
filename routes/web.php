<?php

use App\Models\NumberTable;
use App\Models\Reservation;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckBlacklist;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CrewController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\TableController;
use App\Http\Controllers\Admin\BlacklistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Cashier\PaymentController;
use App\Http\Controllers\Admin\NumberTableController;
use App\Http\Controllers\Customer\ReservationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Customer\ReservationPaymentController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    // Admin Review Routes
    Route::prefix('reviews')->name('reviews.')->group(function() {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
        Route::post('/{review}/reply', [ReviewController::class, 'reply'])->name('reply');
        Route::get('/menu/{menu}/stats', [ReviewController::class, 'menuStats'])->name('menu.stats');
    });
});

// Cashier Routes
Route::middleware(['auth', 'role:cashier', CheckBlacklist::class])->prefix('cashier')->name('cashier.')->group(function() {
    Route::get('/dashboard', function () {
        return view('cashier.dashboard');
    })->name('dashboard');

    // Orders Routes untuk Cashier
    Route::prefix('orders')->name('orders.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Cashier\OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [\App\Http\Controllers\Cashier\OrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [\App\Http\Controllers\Cashier\OrderController::class, 'updateStatus'])->name('update-status');
    });

    // Payment Routes
    Route::prefix('payments')->name('payments.')->group(function() {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::post('/{payment}/confirm', [PaymentController::class, 'confirm'])->name('confirm');
        Route::post('/{payment}/process-cash', [PaymentController::class, 'processCashPayment'])->name('process-cash');
        Route::get('/{payment}/receipt', [PaymentController::class, 'printReceipt'])->name('receipt');
    });

    // Reservation Routes untuk Cashier
    Route::prefix('reservations')->name('reservations.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Cashier\ReservationController::class, 'index'])->name('index');
        Route::get('/{reservation}', [\App\Http\Controllers\Cashier\ReservationController::class, 'show'])->name('show');
        Route::patch('/{reservation}/status', [\App\Http\Controllers\Cashier\ReservationController::class, 'updateStatus'])->name('update-status');
    });
});

// Customer Routes
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

    // Order Routes
    Route::prefix('orders')->name('customer.orders.')->group(function() {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::delete('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('/{order}/payment', [\App\Http\Controllers\Customer\PaymentController::class, 'create'])->name('payment.create');
        Route::post('/{order}/payment', [\App\Http\Controllers\Customer\PaymentController::class, 'store'])->name('payment.store');
    });

    // Customer Review Routes
    Route::prefix('reviews')->name('customer.reviews.')->group(function() {
        Route::get('/order/{order}/menu/{menu}/create', [\App\Http\Controllers\Customer\ReviewController::class, 'create'])
            ->name('create')
            ->whereNumber(['order', 'menu']);
        
        Route::post('/order/{order}/menu/{menu}', [\App\Http\Controllers\Customer\ReviewController::class, 'store'])
            ->name('store')
            ->whereNumber(['order', 'menu']);
        
        Route::delete('/{review}', [\App\Http\Controllers\Customer\ReviewController::class, 'destroy'])
            ->name('destroy')
            ->whereNumber('review');
    });

    Route::get('/api/available-tables', function(Request $request) {
        $date = $request->query('date');
        $time = $request->query('time');
        
        if (!$date || !$time) {
            return response()->json(NumberTable::all());
        }
        
        $occupiedTables = Reservation::where('reservation_date', $date)
            ->where('reservation_time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('table_number');
        
        $availableTables = NumberTable::whereNotIn('table_number', $occupiedTables)->get();
        
        return response()->json($availableTables);
    })->name('api.available-tables');

    // Reservasi
    Route::prefix('reservations')->name('customer.reservations.')->group(function() {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('cancel');
        
        // Menu routes untuk reservasi
        Route::prefix('{reservation}/menu')->name('menu.')->group(function() {
            Route::get('/add', [ReservationController::class, 'addMenu'])->name('add');
            Route::post('/store', [ReservationController::class, 'storeMenu'])->name('store');
            Route::delete('/clear', [ReservationController::class, 'clearMenu'])->name('clear');
        });
        
        // Payment routes
        Route::get('/{reservation}/payment/create', [ReservationPaymentController::class, 'create'])->name('payment.create');
        Route::post('/{reservation}/payment', [ReservationPaymentController::class, 'store'])->name('payment.store');
    });
});

require __DIR__.'/auth.php';