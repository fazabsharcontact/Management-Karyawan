<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\GajiController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Pegawai\PegawaiDashboardController;
use App\Http\Controllers\Pegawai\PegawaiGajiController;
use App\Http\Controllers\Pegawai\PegawaiKehadiranController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// AdminDashboard
// Menambahkan prefix 'admin' untuk membuat URL unik, contoh: /admin/dashboard
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('pegawai', PegawaiController::class)->names('admin.pegawai')->except(['show']);
    Route::resource('gaji', GajiController::class)->names('admin.gaji');
    Route::resource('jabatan', JabatanController::class)->names('admin.jabatan');
});

// PegawaiDashboard
// Menambahkan prefix 'pegawai' untuk membuat URL unik, contoh: /pegawai/dashboard
Route::prefix('pegawai')->middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    Route::get('/gaji', [PegawaiGajiController::class, 'index'])->name('pegawai.gaji');
    Route::get('/kehadiran', [PegawaiKehadiranController::class, 'index'])->name('pegawai.kehadiran.index');
    Route::post('/kehadiran', [PegawaiKehadiranController::class, 'store'])->name('pegawai.kehadiran.store');
});

