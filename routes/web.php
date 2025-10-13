<?php

use App\Models\NumberTable;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckBlacklist;
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
use App\Http\Controllers\Customer\OrderPaymentController;
use App\Http\Controllers\Admin\BankPaymentMethodController;
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

    Route::prefix('bank-payment-methods')->name('bank-payment-methods.')->group(function() {
        Route::get('/', [BankPaymentMethodController::class, 'index'])->name('index');
        Route::post('/', [BankPaymentMethodController::class, 'store'])->name('store');
        Route::put('/{bankPaymentMethod}', [BankPaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{bankPaymentMethod}', [BankPaymentMethodController::class, 'destroy'])->name('destroy');
        Route::post('/{bankPaymentMethod}/toggle-status', [BankPaymentMethodController::class, 'toggleStatus'])->name('toggle-status');
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

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index'); // /cashier/payments
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show'); // /cashier/payments/{id}
        Route::get('/{payment}/receipt', [PaymentController::class, 'printReceipt'])->name('receipt'); // /cashier/payments/{id}/receipt
        
        Route::post('/{payment}/confirm', [PaymentController::class, 'confirmPayment'])->name('confirm');
        Route::post('/{payment}/reject', [PaymentController::class, 'rejectPayment'])->name('reject');
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
        
        // Payment routes untuk order biasa
        Route::get('/{order}/payment', [OrderPaymentController::class, 'create'])
            ->name('payment.create');
        Route::post('/{order}/payment', [OrderPaymentController::class, 'store'])
            ->name('payment.store');        
        });

    // Customer Review Routes
    Route::prefix('reviews')->name('customer.reviews.')->group(function() {
        // Review untuk order biasa
        Route::get('/order/{order}/menu/{menu}/create', [\App\Http\Controllers\Customer\ReviewController::class, 'create'])
            ->name('create')
            ->whereNumber(['order', 'menu']);
        
        Route::post('/order/{order}/menu/{menu}', [\App\Http\Controllers\Customer\ReviewController::class, 'store'])
            ->name('store')
            ->whereNumber(['order', 'menu']);
        
        // Review untuk reservasi
        Route::get('/reservation/{reservation}/menu/{menu}/create', [\App\Http\Controllers\Customer\ReviewController::class, 'createFromReservation'])
            ->name('create-from-reservation')
            ->whereNumber(['reservation', 'menu']);
        
        Route::post('/reservation/{reservation}/menu/{menu}', [\App\Http\Controllers\Customer\ReviewController::class, 'storeFromReservation'])
            ->name('store-from-reservation')
            ->whereNumber(['reservation', 'menu']);
        
        Route::delete('/{review}', [\App\Http\Controllers\Customer\ReviewController::class, 'destroy'])
            ->name('destroy')
            ->whereNumber('review');
    });

    Route::get('/api/available-tables', function(Request $request) {
        $date = $request->query('date');
        $startTime = $request->query('start_time');
        $endTime = $request->query('end_time');
        
        if (!$date || !$startTime || !$endTime) {
            return response()->json(NumberTable::all());
        }
        
        $occupiedTables = Reservation::where('reservation_date', $date)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('reservation_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function($q) use ($startTime, $endTime) {
                          $q->where('reservation_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                      });
            })
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
            Route::put('/{orderItem}/update', [ReservationController::class, 'updateMenuItem'])->name('update');
            Route::delete('/{orderItem}/remove', [ReservationController::class, 'removeMenuItem'])->name('remove');
            Route::delete('/clear', [ReservationController::class, 'clearMenu'])->name('clear');
            Route::post('/update-items', [ReservationController::class, 'updateMenuItems'])->name('update-items');
        });
        
        
        // Payment routes
        Route::get('/{reservation}/payment/create', [ReservationPaymentController::class, 'create'])
        ->name('payment.create');
        Route::post('/{reservation}/payment', [ReservationPaymentController::class, 'store'])
            ->name('payment.store');
    });
});

require __DIR__.'/auth.php';
