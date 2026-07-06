<?php

use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LokasiKantorController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Karyawan\DashboardController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

// Root: redirect berdasarkan role
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('karyawan.dashboard');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', CheckRole::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Karyawan
        Route::resource('karyawan', KaryawanController::class);

        // Shift
        Route::resource('shift', ShiftController::class)->except(['show']);

        // Jadwal
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
        Route::post('/jadwal/bulk', [JadwalController::class, 'bulkStore'])->name('jadwal.bulk');

        // Laporan Gaji
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate');
        Route::get('/laporan/export', [LaporanController::class, 'exportExcel'])->name('laporan.export');

        // Lokasi Kantor
        Route::resource('lokasi', LokasiKantorController::class)->except(['show']);
        Route::post('/lokasi/{lokasi}/aktif', [LokasiKantorController::class, 'setAktif'])->name('lokasi.aktif');

        // Profile
        Route::view('profile', 'admin.profile')->name('profile');
    });

// ============================================================
// KARYAWAN ROUTES
// ============================================================
Route::middleware(['auth', CheckRole::class . ':karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        // Dashboard (Absensi)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Absensi
        Route::get('/absen', function () {
            return view('karyawan.absen');
        })->name('absen');

        // Riwayat
        Route::get('/riwayat', function () {
            return view('karyawan.riwayat');
        })->name('riwayat');

        // Uang Makan
        Route::get('/uang-makan', function () {
            return view('karyawan.uang-makan');
        })->name('uang-makan');

        // Profil
        Route::get('/profil', function () {
            return view('karyawan.profil');
        })->name('profil');
    });

require __DIR__ . '/auth.php';
