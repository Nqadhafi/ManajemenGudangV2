<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        // User Management
        Route::resource('users', UserController::class);
            Route::get('barangs/export', [BarangController::class, 'export'])->name('barangs.export');
    Route::post('barangs/import', [BarangController::class, 'import'])->name('barangs.import');

    // SUPPLIER
    Route::get('suppliers/export', [SupplierController::class, 'export'])->name('suppliers.export');
    Route::post('suppliers/import', [SupplierController::class, 'import'])->name('suppliers.import');

    // KATEGORI
    Route::get('kategoris/export', [KategoriController::class, 'export'])->name('kategoris.export');
    Route::post('kategoris/import', [KategoriController::class, 'import'])->name('kategoris.import');
        // Karyawan Management
        Route::resource('karyawans', KaryawanController::class);
        
        // Divisi Management
        Route::resource('divisis', DivisiController::class);
        
        // Supplier Management
        Route::resource('suppliers', SupplierController::class);
        
        // Kategori Management
        Route::resource('kategoris', KategoriController::class);
                    // BARANG

        // Barang Management
        Route::get('barangs/search', [BarangController::class, 'search'])
            ->name('barangs.search');
        Route::resource('barangs', BarangController::class);
        Route::get('/barangs/{id}/kartu-stok', [BarangController::class, 'kartuStok'])->name('barangs.kartu-stok');
        
        // Transaksi Masuk
        Route::get('/transaksi/masuk', [TransaksiController::class, 'masukIndex'])->name('transaksi.masuk');
        Route::post('/transaksi/masuk', [TransaksiController::class, 'masukStore']);
        
        // Transaksi Keluar (Koreksi Stok)
        Route::get('/transaksi/keluar', [TransaksiController::class, 'keluarIndex'])->name('transaksi.keluar');
        Route::post('/transaksi/keluar', [TransaksiController::class, 'keluarStore']);
        Route::get('transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::put('transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
        
        // Laporan
        Route::get('/laporan/stok', [LaporanController::class, 'stokIndex'])->name('laporan.stok');
        Route::get('/laporan/transaksi', [LaporanController::class, 'transaksiIndex'])->name('laporan.transaksi');
        Route::get('/laporan/stok/print', [LaporanController::class, 'stokPrint'])->name('laporan.stok.print');
        Route::get('/laporan/transaksi/print', [LaporanController::class, 'transaksiPrint'])->name('laporan.transaksi.print');
        Route::get('laporan/stok/search', [LaporanController::class, 'stokSearch'])->name('laporan.stok.search');


    });

    // Operator Routes
    Route::middleware(['role:operator'])->group(function () {
        // Transaksi Keluar
        Route::get('/operator/transaksi/keluar', [TransaksiController::class, 'operatorKeluarIndex'])->name('operator.transaksi.keluar');
        Route::post('/operator/transaksi/keluar', [TransaksiController::class, 'operatorKeluarStore']);
        
        // History Transaksi
        Route::get('/operator/history', [TransaksiController::class, 'operatorHistory'])->name('operator.history');
    });
});

// Middleware Role
Route::middleware('auth')->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        // Admin routes here
    });
    
    Route::middleware(['role:operator'])->group(function () {
        // Operator routes here
    });
});