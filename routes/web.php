<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard - semua role bisa akses
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PRODUK ROUTES
    Route::prefix('produk')->group(function () {
        Route::get('/', [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/store', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    });

    // KARYAWAN ROUTES - Hanya bisa diakses jika role supervisor atau pemilik
    // TAMBAHKAN AUTHORIZATION DI CONTROLLER
    Route::prefix('karyawan')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('/{id}/update', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    });

    // Profile - semua role
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
