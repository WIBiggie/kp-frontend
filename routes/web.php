<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// =========================================================================
// Halaman Utama & Auth (Bisa diakses langsung)
// =========================================================================

// Mengarahkan halaman utama (/) langsung ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// Memanggil file Login.vue
Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');


// =========================================================================
// Halaman Dashboard & Fitur Vue
// =========================================================================

// 1. Kelompok Halaman Siswa
Route::prefix('siswa')->group(function () {
    // PERBAIKAN: Ubah '/siswa/dashboard' menjadi '/dashboard' saja agar URL-nya tetap localhost:8080/siswa/dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Siswa/Dashboard');
    })->name('dashboard');

    Route::get('/alattersedia', function () {
        return Inertia::render('Siswa/AlatTersedia');
    })->name('alat.total');

    Route::get('/scanqr', function () {
        return Inertia::render('Siswa/ScanQr');
    })->name('scan.qr');

    Route::get('/riwayat', function () {
        return Inertia::render('Siswa/Riwayat');
    })->name('siswa.riwayat');

    Route::get('/pengaturan', function () {
        return Inertia::render('Siswa/Pengaturan');
    })->name('pengaturan.edit');

    Route::get('/profil', function () {
        return Inertia::render('Profile/Edit');
    })->name('profile.edit');
});

// 2. Kelompok Halaman Jurusan
Route::prefix('jurusan')->group(function () {
    Route::get('/dashboardjurusan', function () {
        return Inertia::render('Jurusan/DashboardJurusan');
    })->name('dashboardjurusan');

    Route::get('/profil', function () {
        return Inertia::render('Jurusan/ProfileEdit');
    })->name('jurusan.profile.edit');
});

// 3. Kelompok Halaman Sarpras
Route::prefix('sarpras')->group(function () {
    Route::get('/dashboardsarpras', function () {
        return Inertia::render('Sarpras/DashboardSarpras');
    })->name('dashboardsarpras');

    Route::get('/profil', function () {
        return Inertia::render('Sarpras/ProfileEdit');
    })->name('sarpras.profile.edit');
});

// Rute untuk Dashboard Utama Sarpras
Route::get('/sarpras/dashboard', function () {
    return Inertia::render('Sarpras/DashboardSarpras');
});

// Rute untuk Halaman Jenis Komoditas
Route::get('/sarpras/komoditas', function () {
    return Inertia::render('Sarpras/SarprasKomoditas');
});

// Rute untuk Halaman Total Volume Aset
Route::get('/sarpras/volume', function () {
    return Inertia::render('Sarpras/SarprasVolume');
});

// Rute untuk Halaman Layak Pakai
Route::get('/sarpras/layak-pakai', function () {
    return Inertia::render('Sarpras/SarprasLayakPakai');
});
