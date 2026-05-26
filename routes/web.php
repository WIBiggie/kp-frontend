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

// bagian Siswa
Route::get('/siswa/dashboard', function () {
    return Inertia::render('Siswa/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/siswa/alattersedia', function () {
    return Inertia::render('Siswa/AlatTersedia');
})->middleware(['auth', 'verified'])->name('alat.tersedia');

Route::get('/siswa/scanqr', function () {
    return Inertia::render('Siswa/ScanQr');
})->middleware(['auth', 'verified'])->name('scan.qr');


// bagian Jurusan
Route::get('/jurusan/dashboardjurusan', function () {
    return Inertia::render('Jurusan/DashboardJurusan');
})->middleware(['auth', 'verified'])->name('dashboardjurusan');


// bagian Sarana Prasarana
Route::get('/sapras/dashboardsapras', function () {
    return Inertia::render('Sapras/DashboardSapras');
})->middleware(['auth', 'verified'])->name('dashboardsapras');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
