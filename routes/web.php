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
use App\Http\Controllers\Admin\TunjanganPotonganController;
use App\Http\Controllers\Admin\MasterTunjanganController; 
use App\Http\Controllers\Admin\MasterPotonganController;
use App\Http\Controllers\Admin\TimDivisiController;
use App\Http\Controllers\Admin\DivisiController;
use App\Http\Controllers\Admin\TimController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\CutiController;
use App\Http\Controllers\Admin\PengumumanController;

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
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('pegawai', PegawaiController::class)->names('admin.pegawai')->except(['show']);
    Route::resource('gaji', GajiController::class)->names('admin.gaji');
    Route::resource('jabatan', JabatanController::class)->names('admin.jabatan');
    Route::get('tunjangan-potongan', [TunjanganPotonganController::class, 'index'])->name('admin.tunjangan-potongan.index');
    Route::resource('master-tunjangan', MasterTunjanganController::class)->names('admin.master-tunjangan')->except(['show']);
    Route::resource('master-potongan', MasterPotonganController::class)->names('admin.master-potongan')->except(['show']);
    Route::get('tim-divisi', [TimDivisiController::class, 'index'])->name('admin.tim-divisi.index');
    Route::resource('divisi', DivisiController::class)->names('admin.divisi')->except(['index', 'show']);
    Route::resource('tim', TimController::class)->names('admin.tim')->except(['index', 'show']);
    Route::resource('meeting', MeetingController::class)->names('admin.meeting');
    Route::get('cuti', [CutiController::class, 'index'])->name('admin.cuti.index');
    Route::patch('cuti/{cuti}/status', [CutiController::class, 'updateStatus'])->name('admin.cuti.updateStatus');
    Route::resource('pengumuman', PengumumanController::class)
        ->names('admin.pengumuman')
        ->only(['index', 'create', 'store', 'destroy']);

});

// PegawaiDashboard
Route::prefix('pegawai')->middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    Route::get('/gaji', [PegawaiGajiController::class, 'index'])->name('pegawai.gaji');
    Route::get('/kehadiran', [PegawaiKehadiranController::class, 'index'])->name('pegawai.kehadiran.index');
    Route::post('/kehadiran', [PegawaiKehadiranController::class, 'store'])->name('pegawai.kehadiran.store');
});

