<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuItemController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin|superadmin|kitchen|cashier|manager'])
    ->name('dashboard');

use App\Http\Controllers\MenuController;

// ─── Public QR Menu Routes (no auth required) ───────────────────────────────
Route::get('/menu/{restaurant}/{table}', [MenuController::class, 'index'])->name('menu.index');
Route::post('/menu/{restaurant}/{table}/order', [MenuController::class, 'storeOrder'])->name('menu.store-order');
Route::get('/menu/{restaurant}/{table}/orders', [MenuController::class, 'sessionOrders'])->name('menu.session-orders');

// QR Code proxy (fallback if storage:link fails)
Route::get('/qr-code/{token}', [TableController::class, 'showQrCode'])->name('qr.show');

// Table listing API (for table-switch modal)
Route::get('/api/menu/{restaurant}/tables', [MenuController::class, 'getTables'])->name('api.menu.tables');

// Customer Auth Routes
use App\Http\Controllers\CustomerAuthController;
Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register');
Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login');
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

Route::middleware(['auth', 'verified', 'role:admin|superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('restaurants', RestaurantController::class);
    Route::resource('branches', BranchController::class);
    // Tables
    Route::get('/tables/generate-all', [TableController::class, 'generateAll'])->name('tables.generate-all');
    Route::resource('tables', TableController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('menu-items', MenuItemController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
});

// Staff Specific Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Kitchen Display System
    Route::get('/kitchen', [\App\Http\Controllers\KitchenController::class, 'index'])->middleware('role:kitchen|admin|superadmin')->name('kitchen.index');
    Route::post('/kitchen/item/{item}/status', [\App\Http\Controllers\KitchenController::class, 'updateItemStatus'])->name('kitchen.item.status');
    
    // Cashier
    Route::get('/cashier', [\App\Http\Controllers\CashierController::class, 'index'])->middleware('role:cashier|admin|superadmin')->name('cashier.index');
    Route::post('/cashier/order/{order}/pay', [\App\Http\Controllers\CashierController::class, 'processPayment'])->name('cashier.pay');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
