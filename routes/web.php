<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Stock In (Barang Masuk)
    Route::resource('stock-ins', StockInController::class);

    // Stock Out (Barang Keluar/Penjualan)
    Route::resource('stock-outs', StockOutController::class);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        // Main Reports Page
        Route::get('/', [ReportController::class, 'index'])->name('index');

        // Stock Reports
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('/stock/pdf', [ReportController::class, 'stockPdf'])->name('stock.pdf');
        Route::post('/stock/email', [ReportController::class, 'emailStock'])->name('stock.email');

        // Sales Reports
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/sales/pdf', [ReportController::class, 'salesPdf'])->name('sales.pdf');
        Route::post('/sales/email', [ReportController::class, 'emailSales'])->name('sales.email');

        // Purchases Reports
        Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases');
        Route::get('/purchases/pdf', [ReportController::class, 'purchasesPdf'])->name('purchases.pdf');
        Route::post('/purchases/email', [ReportController::class, 'emailPurchases'])->name('purchases.email');

        // Profit Reports
        Route::get('/profit', [ReportController::class, 'profit'])->name('profit');
        Route::get('/profit/pdf', [ReportController::class, 'profitPdf'])->name('profit.pdf');
        Route::post('/profit/email', [ReportController::class, 'emailProfit'])->name('profit.email');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
