<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout']);

// Protected Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // POS Order Interface
    Route::get('/pos', [OrderController::class, 'pos'])->name('pos');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/add-item', [OrderController::class, 'addItem'])->name('orders.addItem');
    Route::delete('/orders/{order}/items/{item}', [OrderController::class, 'removeItem'])->name('orders.removeItem');
    Route::post('/orders/{order}/discount', [OrderController::class, 'applyDiscount'])->name('orders.discount');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{order}/data', [OrderController::class, 'getOrderData'])->name('orders.data');

    // Billing & Payment
    Route::get('/billing/{order}', [PaymentController::class, 'show'])->name('billing.show');
    Route::post('/billing/{order}/pay', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{order}/receipt', [PaymentController::class, 'receipt'])->name('payment.receipt');
    Route::get('/payment/{order}/print', [PaymentController::class, 'printReceipt'])->name('payment.print');

    // Kitchen Display
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
    Route::post('/kitchen/{order}/status', [KitchenController::class, 'updateOrderStatus'])->name('kitchen.order.status');
    Route::post('/kitchen/item/{item}/status', [KitchenController::class, 'updateItemStatus'])->name('kitchen.item.status');
    Route::get('/kitchen/orders/active', [KitchenController::class, 'getActiveOrders'])->name('kitchen.active');

    // Tables
    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
    Route::put('/tables/{table}', [TableController::class, 'update'])->name('tables.update');
    Route::delete('/tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
    Route::post('/tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.status');

    // Menu - Categories
    Route::get('/menu/categories', [CategoryController::class, 'index'])->name('menu.categories');
    Route::post('/menu/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/menu/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/menu/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::patch('/menu/categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');

    // Menu - Items
    Route::get('/menu/items', [MenuItemController::class, 'index'])->name('menu.items');
    Route::post('/menu/items', [MenuItemController::class, 'store'])->name('menu.items.store');
    Route::put('/menu/items/{menuItem}', [MenuItemController::class, 'update'])->name('menu.items.update');
    Route::delete('/menu/items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu.items.destroy');
    Route::patch('/menu/items/{menuItem}/toggle', [MenuItemController::class, 'toggle'])->name('menu.items.toggle');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Settings (Admin only - using simple auth check in controller)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/users', [SettingController::class, 'storeUser'])->name('settings.users.store');
    Route::put('/settings/users/{user}', [SettingController::class, 'updateUser'])->name('settings.users.update');
    Route::delete('/settings/users/{user}', [SettingController::class, 'destroyUser'])->name('settings.users.destroy');
});
