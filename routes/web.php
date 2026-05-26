<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// ==================== BAGIAN SISWA ====================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/siswa/dashboard', function () {
        return Inertia::render('Siswa/Dashboard');
    })->name('dashboard');

    Route::get('/siswa/alattersedia', function () {
        return Inertia::render('Siswa/AlatTersedia');
    })->name('alat.tersedia');

    Route::get('/siswa/scanqr', function () {
        return Inertia::render('Siswa/ScanQr');
    })->name('scan.qr');

    Route::get('/siswa/riwayat', function () {
        return Inertia::render('Siswa/Riwayat');
    })->name('siswa.riwayat');

    Route::get('/siswa/pengaturan', function () {
        return Inertia::render('Siswa/Pengaturan'); // Sesuaikan nama letak file komponen Anda
    })->name('pengaturan.edit');

    // NOTE: Route manual /siswa/profil dihapus agar tidak bentrok dengan ProfileController
});

// ==================== BAGIAN JURUSAN ====================
Route::get('/jurusan/dashboardjurusan', function () {
    return Inertia::render('Jurusan/DashboardJurusan');
})->middleware(['auth', 'verified'])->name('dashboardjurusan');

// ==================== BAGIAN SAPRAS ====================
Route::get('/sapras/dashboardsapras', function () {
    return Inertia::render('Sapras/DashboardSapras');
})->middleware(['auth', 'verified'])->name('dashboardsapras');

// =========================================================================
// PROFIL GLOBAL (DIBAGI PER ROLE)
// =========================================================================
Route::middleware('auth')->group(function () {

    // 1. Profil Khusus Siswa
    Route::prefix('siswa')->group(function () {
        Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');
    });

    // 2. Profil Khusus Jurusan
    Route::prefix('jurusan')->group(function () {
        Route::get('/profil', [ProfileController::class, 'editJurusan'])->name('jurusan.profile.edit');
        Route::post('/profil', [ProfileController::class, 'updateJurusan'])->name('jurusan.profile.update');
    });

    // 3. Profil Khusus Sapras
    Route::prefix('sapras')->group(function () {
        Route::get('/profil', [ProfileController::class, 'editSapras'])->name('sapras.profile.edit');
        Route::post('/profil', [ProfileController::class, 'updateSapras'])->name('sapras.profile.update');
    });

    // Route delete akun (jika masih dipakai global)
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
