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

// Route Auth untuk Login (harus di luar middleware auth)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard - semua role bisa akses
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // KARYAWAN ROUTES - Hanya bisa diakses jika role supervisor atau pemilik
    Route::prefix('karyawan')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('/{id}/update', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    });

    // ABSENSI ROUTES
    Route::prefix('absensi')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/create', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/store', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/{absensi}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
        Route::put('/{absensi}/update', [AbsensiController::class, 'update'])->name('absensi.update');
        Route::delete('/{absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    });

    // Profile - semua role
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Logout Route
    Route::post('/logout', function (Illuminate\Http\Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// Jika Anda punya file auth.php terpisah
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}