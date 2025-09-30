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
use App\Http\Controllers\Pegawai\PegawaiPengumumanController;
use App\Http\Controllers\Pegawai\PegawaiCutiController;
use App\Http\Controllers\Admin\TunjanganPotonganController;
use App\Http\Controllers\Admin\MasterTunjanganController;
use App\Http\Controllers\Admin\MasterPotonganController;
use App\Http\Controllers\Admin\TimDivisiController;
use App\Http\Controllers\Admin\DivisiController;
use App\Http\Controllers\Admin\TimController;
use App\Http\Controllers\Admin\MeetingController;
use App\Http\Controllers\Admin\CutiController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\LaporanPerformaController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

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
    Route::get('gaji/{gaji}/slip', [GajiController::class, 'unduhSlipGaji'])->name('admin.gaji.slip');
    Route::resource('jabatan', JabatanController::class)->names('admin.jabatan');
    Route::get('tunjangan-potongan', [TunjanganPotonganController::class, 'index'])->name('admin.tunjangan-potongan.index');
    Route::resource('master-tunjangan', MasterTunjanganController::class)->names('admin.master-tunjangan')->except(['index', 'show']);
    Route::resource('master-potongan', MasterPotonganController::class)->names('admin.master-potongan')->except(['index', 'show']);
    Route::get('tim-divisi', [TimDivisiController::class, 'index'])->name('admin.tim-divisi.index');
    Route::resource('divisi', DivisiController::class)->names('admin.divisi')->except(['index', 'show']);
    Route::resource('tim', TimController::class)->names('admin.tim')->except(['index', 'show']);
    Route::resource('meeting', MeetingController::class)->names('admin.meeting');
    
    // --- Rute untuk Manajemen Cuti ---
    Route::get('cuti', [CutiController::class, 'index'])->name('admin.cuti.index');
    Route::patch('cuti/{cuti}/status', [CutiController::class, 'updateStatus'])->name('admin.cuti.updateStatus');
    // --- RUTE BARU YANG HILANG ---
    Route::post('cuti/reset-tahunan', [CutiController::class, 'resetCutiTahunan'])->name('admin.cuti.resetTahunan');

    Route::resource('pengumuman', PengumumanController::class)
        ->names('admin.pengumuman')
        ->only(['index', 'create', 'store', 'destroy']);
        
    Route::get('laporan', [LaporanPerformaController::class, 'index'])->name('admin.laporan.performa'); // Mengarahkan laporan ke performa
    Route::get('laporan/performa', [LaporanPerformaController::class, 'index'])->name('admin.laporan.performa.index'); // Alias untuk konsistensi
    Route::get('laporan/performa/unduh-pdf', [LaporanPerformaController::class, 'unduhPdf'])->name('admin.laporan.performa.pdf');
});
// PegawaiDashboard
Route::prefix('pegawai')->middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    Route::get('/gaji', [PegawaiGajiController::class, 'index'])->name('pegawai.gaji');
    Route::get('/kehadiran', [PegawaiKehadiranController::class, 'index'])->name('pegawai.kehadiran.index');
    Route::post('/kehadiran', [PegawaiKehadiranController::class, 'store'])->name('pegawai.kehadiran.store');
    Route::get('/pengumuman', [PegawaiPengumumanController::class, 'index'])->name('pegawai.pengumuman.index');
    Route::get('/pegawai/cuti', [App\Http\Controllers\Pegawai\PegawaiCutiController::class, 'index'])->name('pegawai.cuti.index');
    Route::get('/pegawai/cuti/create', [App\Http\Controllers\Pegawai\PegawaiCutiController::class, 'create'])->name('pegawai.cuti.create');
    Route::post('/pegawai/cuti', [App\Http\Controllers\Pegawai\PegawaiCutiController::class, 'store'])->name('pegawai.cuti.store');
});

