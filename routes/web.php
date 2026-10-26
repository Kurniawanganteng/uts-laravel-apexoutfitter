<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Customer\StoreController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\ReportController;

// Halaman awal: Arahkan ke Toko / Katalog untuk umum & customer, atau Dashboard untuk Admin/Staff
Route::get('/', function () {
    if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isStaff())) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('shop.index');
});

// Panduan Belajar & Cheatsheet Demo UTS Dosen
Route::get('/panduan', function () {
    $path = base_path('PANDUAN_BELAJAR_UTS.html');
    if (file_exists($path)) {
        return response()->file($path);
    }
    return redirect()->route('shop.index');
})->name('panduan');

// ------------------------------------------------------------------------------
// TOKO / KATALOG PRODUK & KERANJANG (Bisa diakses publik atau setelah login)
// ------------------------------------------------------------------------------
Route::get('/shop', [StoreController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [StoreController::class, 'show'])->name('shop.show');

// Keranjang Belanja (Cart berbasis Session - Publik & Pelanggan)
Route::get('/cart', [CartController::class, 'index'])->name('customer.cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('customer.cart.add');
Route::put('/cart/update/{product}', [CartController::class, 'update'])->name('customer.cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('customer.cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('customer.cart.clear');

// ------------------------------------------------------------------------------
// TRANSAKSI CHECKOUT, PAYMENT GATEWAY & PESANAN (Wajib Login Pelanggan)
// ------------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('customer.checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('customer.checkout.process');

    // Payment Gateway Simulator (ApexPay Gateway)
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('customer.payment.show');
    Route::post('/payment/{order}/simulate-pay', [PaymentController::class, 'simulatePay'])->name('customer.payment.simulate');
    Route::post('/payment/{order}/cancel', [PaymentController::class, 'cancel'])->name('customer.payment.cancel');

    // Riwayat Transaksi & Laporan Belanja Pelanggan
    Route::get('/customer/orders', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/customer/orders/{order}', [OrderController::class, 'show'])->name('customer.orders.show');
    Route::get('/customer/reports', [OrderController::class, 'reports'])->name('customer.reports.index');
});

// ------------------------------------------------------------------------------
// BACKOFFICE & INVENTORY MANAGEMENT (Khusus Admin/Owner & Staff)
// ------------------------------------------------------------------------------
Route::middleware(['auth', \App\Http\Middleware\EnsureStaffOrAdmin::class])->group(function () {
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Katalog Produk Internal
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Transaksi Kasir POS (Sales)
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);

    // Pembelian Stok Masuk (Purchases)
    Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'show']);

    // Manajemen Pesanan Customer (Online Orders)
    Route::get('/admin/orders', [OrderManagementController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [OrderManagementController::class, 'show'])->name('admin.orders.show');
    Route::post('/admin/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('admin.orders.status');

    // Pusat Laporan Bisnis & Analitik (Admin & Staff)
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');

    // Khusus Owner / Administrator (Dibatasi Server-Side via Gate 'admin-only')
    Route::middleware(['can:admin-only'])->group(function () {
        // Alur Pembatalan Transaksi Hanya di Owner
        Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');

        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';
