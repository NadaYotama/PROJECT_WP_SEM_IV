<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DashboardController as MainDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MenuCustomerDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $featuredMenus = \App\Models\Menu::where('is_available', true)
        ->where('is_popular', true)
        ->take(6)
        ->get();

    return view('welcome', compact('featuredMenus'));
})->name('welcome');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/refresh-captcha', function () {
    session()->put('captcha_answer', rand(1,9) + rand(1,9));
    return response()->json(['question' => session('captcha_question')]);
});

// Route untuk menu publik
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/menu', [MenuCustomerDashboardController::class, 'index'])->name('customer.menu.index');
    Route::get('/customer/menu/orders', [MenuCustomerDashboardController::class, 'orders'])->name('customer.menu.orders');
    Route::get('/customer/cart', [CartController::class, 'index'])->name('customer.cart.index');
    Route::get('/customer/orders/create', [OrderController::class, 'create'])->name('customer.orders.create');
    Route::post('/customer/orders', [OrderController::class, 'store'])->name('customer.orders.store');
    Route::get('/customer/orders/{order}', [OrderController::class, 'show'])->name('customer.orders.show');

    Route::get('/customer/orders/current', [OrderController::class, 'currentOrders'])->name('customer.orders.current');
    Route::get('/customer/orders/history', [OrderController::class, 'historyOrders'])->name('customer.orders.history');

    // Reviews routes for customer
    Route::resource('reviews', \App\Http\Controllers\ReviewController::class)->names([
        'index' => 'reviews.index',
        'create' => 'reviews.create',
        'store' => 'reviews.store',
        'show' => 'reviews.show',
        'edit' => 'reviews.edit',
        'update' => 'reviews.update',
        'destroy' => 'reviews.destroy',
    ]);

    Route::get('/customer/payments/methods', [PaymentController::class, 'customerMethods'])->name('customer.payments.methods');
    Route::get('/customer/payments/history', [PaymentController::class, 'customerTransactionHistory'])->name('customer.payments.history');
});
Route::get('/menu/{category}', [MenuController::class, 'showCategory'])->name('menu.category');

// Jika menggunakan resource route, pastikan hanya untuk admin
Route::resource('admin/menus', MenuController::class)->except(['index', 'show']);


// Public Reservation Routes
Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');

    // Orders Management
    Route::resource('orders', OrderController::class);
    Route::get('/orders/{order}/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/{order}/process-payment', [OrderController::class, 'processPayment'])->name('orders.processPayment');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // // Payments Management
    // Route::resource('payments', PaymentController::class)->except(['create', 'store']);
    // Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    // Route::get('/payments/{payment}/proof', [PaymentController::class, 'showProof'])->name('payments.proof');
    // Route::get('/payments/methods', [PaymentController::class, 'methods'])->name('payments.methods');
    // Route::get('/payments/{payment}/receipt', [PaymentController::class, 'downloadReceipt'])->name('payments.receipt');
    // Route::post('/payments/{payment}/resend-notification', [PaymentController::class, 'resendNotification'])->name('payments.resendNotification');
    // Route::delete('/payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');

    // Tables Management (Manager & Admin only)
    Route::middleware(['role:manager'])->group(function () {
        Route::resource('tables', TableController::class);
        Route::patch('/tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.updateStatus');
        Route::get('/tables/available', [TableController::class, 'available'])->name('tables.available');
        Route::post('/tables/bulk-update-status', [TableController::class, 'bulkUpdateStatus'])->name('tables.bulkUpdateStatus');
        Route::get('/tables/{table}/check-availability', [TableController::class, 'checkAvailability'])->name('tables.checkAvailability');
    });

    // Reservations Management (Staff only)
    Route::get('/reservations', [ReservationController::class, 'staffIndex'])->name('reservations.index')->middleware('role:kasir');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create')->middleware('role:kasir');
    Route::get('/reservations/manage', [ReservationController::class, 'manage'])->name('reservations.manage')->middleware('role:kasir');
    Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus')->middleware('role:kasir');
    Route::delete('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel')->middleware('role:kasir');
    Route::get('/tables/available', [TableController::class, 'available'])->name('tables.available')->middleware('role:kasir');

// Reservations (All authenticated users can view their own)
Route::resource('reservations', ReservationController::class)->only(['show']);

Route::middleware(['auth'])->group(function () {
    Route::get('/my-reservations', [\App\Http\Controllers\ReservationController::class, 'myReservations'])->name('customer.reservations.index');
    Route::get('/my-reservations/create', [\App\Http\Controllers\ReservationController::class, 'customerCreate'])->name('customer.reservations.create');
    Route::post('/my-reservations', [\App\Http\Controllers\ReservationController::class, 'store'])->name('customer.reservations.store');
});

    // Route Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::middleware(['auth', 'role:customer'])->prefix('customer/cart')->group(function () {
    Route::post('/add/{menu}', [CartController::class, 'add'])->name('customer.cart.add');
    Route::get('/', [CartController::class, 'index'])->name('customer.cart.index');
    Route::patch('/update/{itemId}', [CartController::class, 'update'])->name('customer.cart.update');
    Route::delete('/remove/{itemId}', [CartController::class, 'remove'])->name('customer.cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('customer.cart.clear');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('customer.cart.checkout');
    Route::post('/checkout', [CartController::class, 'process'])->name('customer.cart.checkout.process');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/orders', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});

    // Dashboard Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('role:admin');
        Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard')->middleware('role:manager');
        Route::get('/manager/settings', [ManagerController::class, 'settings'])->name('manager.settings')->middleware('role:manager');
        Route::patch('/manager/settings', [ManagerController::class, 'updateSettings'])->name('manager.settings.update')->middleware('role:manager');


    // Temporarily remove middleware 'role:kasir' to test access
    Route::prefix('kasir')->group(function () {
        Route::get('/dashboard', [KasirController::class, 'index'])->name('kasir.dashboard');
        Route::get('/orders', [KasirController::class, 'orders'])->name('kasir.orders.index');
        Route::get('/orders/create', [KasirController::class, 'createOrder'])->name('kasir.orders.create');
        Route::post('/orders', [KasirController::class, 'storeOrder'])->name('kasir.orders.store');
        Route::get('/orders/pending', [KasirController::class, 'pendingOrders'])->name('kasir.orders.pending');
        Route::get('/orders/completed', [KasirController::class, 'completedOrders'])->name('kasir.orders.completed');
        Route::get('/orders/{order}', [KasirController::class, 'showOrder'])->name('kasir.orders.show');
        Route::post('/orders/{order}/status', [KasirController::class, 'updateOrderStatus'])->name('kasir.orders.updateStatus');
        Route::get('/settings', [KasirController::class, 'settings'])->name('kasir.settings');
        Route::patch('/settings', [KasirController::class, 'updateSettings'])->name('kasir.settings.update');
        Route::get('/payments', [KasirController::class, 'payments'])->name('kasir.payments.index');
        Route::get('/payment-history', [KasirController::class, 'paymentHistory'])->name('kasir.payments.history');
        Route::get('/payments/refunds', [KasirController::class, 'refunds'])->name('kasir.payments.refunds');
        Route::get('/payments/methods', [KasirController::class, 'paymentMethods'])->name('kasir.payments.methods');

        Route::get('/payments/{payment}', [KasirController::class, 'showPayment'])->name('kasir.payments.show');
        Route::patch('/payments/{payment}/verify', [KasirController::class, 'verifyPayment'])->name('kasir.payments.verify');
        Route::get('/reports/daily-sales', [KasirController::class, 'dailySalesReport'])->name('kasir.reports.dailySales');
        Route::get('/reports/revenue', [KasirController::class, 'revenueReport'])->name('kasir.reports.revenue');
    });
    Route::get('/customer/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard')->middleware('role:customer');
        // Manager Menu Routes
        Route::prefix('manager')->middleware('role:manager')->group(function () {
            Route::get('/menu', [ManagerController::class, 'menu'])->name('manager.menu.index');
            Route::get('/menu/create', [ManagerController::class, 'createMenu'])->name('manager.menu.create');
            Route::post('/menu', [ManagerController::class, 'storeMenu'])->name('manager.menu.store');
            Route::get('/menu/{menu}/edit', [ManagerController::class, 'editMenu'])->name('manager.menu.edit');
            Route::put('/menu/{menu}', [ManagerController::class, 'updateMenu'])->name('manager.menu.update');
            Route::delete('/menu/{menu}', [ManagerController::class, 'destroyMenu'])->name('manager.menu.destroy');
            Route::get('/categories', [ManagerController::class, 'categories'])->name('manager.categories.index');

            // Inventory Routes
            Route::get('/inventory', [ManagerController::class, 'inventory'])->name('manager.inventory.index');
            Route::patch('/inventory/{menu}', [ManagerController::class, 'updateInventory'])->name('manager.inventory.update');
            Route::post('/inventory/bulk-update', [ManagerController::class, 'bulkUpdateInventory'])->name('manager.inventory.bulk-update');

            // Staff Routes
            Route::get('/staff', [ManagerController::class, 'staff'])->name('manager.staff.index');
            Route::get('/staff/create', [ManagerController::class, 'createStaff'])->name('manager.staff.create');
            Route::post('/staff', [ManagerController::class, 'storeStaff'])->name('manager.staff.store');
            Route::get('/staff/{user}', [ManagerController::class, 'showStaff'])->name('manager.staff.show');
            Route::get('/staff/{user}/edit', [ManagerController::class, 'editStaff'])->name('manager.staff.edit');
            Route::put('/staff/{user}', [ManagerController::class, 'updateStaff'])->name('manager.staff.update');
            Route::delete('/staff/{user}', [ManagerController::class, 'destroyStaff'])->name('manager.staff.destroy');

            // Attendance Routes
            Route::resource('attendance', AttendanceController::class)->names([
                'index' => 'manager.attendance.index',
                'create' => 'manager.attendance.create',
                'store' => 'manager.attendance.store',
                'show' => 'manager.attendance.show',
                'edit' => 'manager.attendance.edit',
                'update' => 'manager.attendance.update',
                'destroy' => 'manager.attendance.destroy',
            ]);
            Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('manager.attendance.checkIn');
            Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('manager.attendance.checkOut');
            Route::get('/attendance/stats', [AttendanceController::class, 'stats'])->name('manager.attendance.stats');

            // Performance Routes
            Route::get('/performance', [ManagerController::class, 'performance'])->name('manager.performance.index');

            // Menu Performance Routes
            Route::get('/menu-performance', [ManagerController::class, 'menuPerformance'])->name('manager.menu-performance.index');

            // Reservation Report Routes
            Route::get('/reservation-report', [ManagerController::class, 'reservationReport'])->name('manager.reservation-report.index');

            // Revenue Analytics Routes
            Route::get('/revenue-analytics', [ManagerController::class, 'revenueAnalytics'])->name('manager.revenue-analytics.index');

            // Reports Routes
            Route::get('/reports', [ManagerController::class, 'reports'])->name('manager.reports.index');
        });

    // Admin Routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        // Menu Management
        Route::get('/menu', [AdminController::class, 'menu'])->name('admin.menu.index');
        Route::get('/menu/create', [AdminController::class, 'createMenu'])->name('admin.menu.create');
        Route::post('/menu', [AdminController::class, 'storeMenu'])->name('admin.menu.store');
        Route::get('/menu/{menu}/edit', [AdminController::class, 'editMenu'])->name('admin.menu.edit');
        Route::put('/menu/{menu}', [AdminController::class, 'updateMenu'])->name('admin.menu.update');
        Route::delete('/menu/{menu}', [AdminController::class, 'destroyMenu'])->name('admin.menu.destroy');
        Route::post('/menu/adjust-stock', [AdminController::class, 'adjustStock'])->name('admin.menu.adjust-stock');
        Route::post('/menu/adjust-stock', [AdminController::class, 'adjustStock'])->name('admin.menu.adjust-stock');

        // Order Management
        Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders.index');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
        Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.updateStatus');


        // Table Management
        Route::get('/tables', [AdminController::class, 'tables'])->name('admin.tables.index');
        Route::get('/tables/create', [AdminController::class, 'createTable'])->name('admin.tables.create');
        Route::post('/tables', [AdminController::class, 'storeTable'])->name('admin.tables.store');
        Route::get('/tables/{table}/edit', [AdminController::class, 'editTable'])->name('admin.tables.edit');
        Route::put('/tables/{table}', [AdminController::class, 'updateTable'])->name('admin.tables.update');
        Route::delete('/tables/{table}', [AdminController::class, 'destroyTable'])->name('admin.tables.destroy');

        // Reservation Management
        Route::get('/reservations', [AdminController::class, 'reservations'])->name('admin.reservations.index');
        Route::post('/reservations', [AdminController::class, 'storeReservation'])->name('admin.reservations.store');
        Route::get('/reservations/{reservation}', [AdminController::class, 'showReservation'])->name('admin.reservations.show');
        Route::patch('/reservations/{reservation}/status', [AdminController::class, 'updateReservationStatus'])->name('admin.reservations.updateStatus');
        Route::delete('/reservations/{reservation}', [AdminController::class, 'cancelReservation'])->name('admin.reservations.cancel');

        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports.index');
        Route::get('/user-activity', [AdminController::class, 'userActivity'])->name('admin.user-activity.index')->middleware('role:admin,manager');

        // Settings
        Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');

        // Category Management
        Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories.index');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');

        // Balance Management
        Route::get('/balance', [App\Http\Controllers\Admin\BalanceController::class, 'index'])->name('admin.balance.index');
        Route::get('/balance/{user}', [App\Http\Controllers\Admin\BalanceController::class, 'show'])->name('admin.balance.show');
        Route::post('/balance/{user}/topup', [App\Http\Controllers\Admin\BalanceController::class, 'topUp'])->name('admin.balance.topup');
        Route::post('/balance/{user}/deduct', [App\Http\Controllers\Admin\BalanceController::class, 'deduct'])->name('admin.balance.deduct');
        Route::get('/balance/{user}/history', [App\Http\Controllers\Admin\BalanceController::class, 'history'])->name('admin.balance.history');
    });
});


Auth::routes();

Route::get('/customer/dashboard', [CustomerController::class, 'index'])
    ->middleware('auth')
    ->name('customer.dashboard');


// Authenticated Routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {


Route::post('/get-snap-token', [PaymentController::class, 'getSnapToken']);
Route::get('/pay', function () {
    return view('payment');
});

});

// Various Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/payments/methods', [PaymentController::class, 'methods'])->name('payments.methods');
    Route::post('/payments/initiate', [PaymentController::class, 'initiate'])->name('payments.initiate');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::get('/payments/{payment}/proof', [PaymentController::class, 'showProof'])->name('payments.proof');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'downloadReceipt'])->name('payments.receipt');
    Route::post('/payments/{payment}/resend-notification', [PaymentController::class, 'resendNotification'])->name('payments.resendNotification');
    Route::delete('/payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');

    // Specific Payment Methods
    Route::post('/payments/credit-card', [PaymentController::class, 'creditCard'])->name('payments.credit-card');
    Route::post('/payments/bank-transfer', [PaymentController::class, 'bankTransfer'])->name('payments.bank-transfer');
    Route::post('/payments/e-wallet', [PaymentController::class, 'eWallet'])->name('payments.e-wallet');
    Route::post('/payments/qris', [PaymentController::class, 'qris'])->name('payments.qris');
    Route::post('/payments/cod', [PaymentController::class, 'cod'])->name('payments.cod');

    // Payment Webhooks
    Route::post('/payments/webhook/midtrans', [PaymentController::class, 'midtransWebhook'])->name('payments.webhook.midtrans');
});
