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
use App\Http\Controllers\Pegawai\TugasPegawaiController;
use App\Http\Controllers\Pegawai\TugasPengumpulanController;
use App\Http\Controllers\Pegawai\PegawaiMeetingController;
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
use App\Http\Controllers\Admin\AdminTugasPengumpulanController;
use App\Http\Controllers\Admin\AdminTugasController;
use App\Http\Controllers\Admin\AdminKehadiranController;

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
    //Route::get('laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('laporan-performa', [LaporanPerformaController::class, 'index'])->name('admin.laporan.performa');
    Route::get('laporan-performa/unduh-pdf', [LaporanPerformaController::class, 'unduhPdf'])->name('admin.laporan.performa.pdf');
    Route::get('tugas/pengumpulan', [App\Http\Controllers\Admin\AdminTugasPengumpulanController::class, 'index'])->name('admin.tugas_pengumpulan.index');
    Route::post('tugas/pengumpulan/{id}/status', [App\Http\Controllers\Admin\AdminTugasPengumpulanController::class, 'updateStatus'])->name('admin.tugas_pengumpulan.update');
    Route::get('/tugas', [App\Http\Controllers\Admin\AdminTugasController::class, 'index'])->name('admin.tugas.index');
    Route::post('/tugas', [App\Http\Controllers\Admin\AdminTugasController::class, 'store'])->name('admin.tugas.store');
    Route::get('kehadiran', [App\Http\Controllers\Admin\AdminKehadiranController::class, 'index'])->name('admin.kehadiran.index');
    Route::get('kehadiran/{pegawai}', [App\Http\Controllers\Admin\AdminKehadiranController::class, 'show'])->name('admin.kehadiran.show');
    Route::get('kehadiran/bukti/{id}', [App\Http\Controllers\Admin\AdminKehadiranController::class, 'downloadBukti'])->name('admin.kehadiran.downloadBukti');
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
    Route::get('/tugas', [App\Http\Controllers\Pegawai\TugasPegawaiController::class, 'index'])->name('pegawai.tugas.index');
    Route::get('/tugas/{id}', [App\Http\Controllers\Pegawai\TugasPegawaiController::class, 'show'])->name('pegawai.tugas.show');
    Route::post('/tugas/{id}/status', [App\Http\Controllers\Pegawai\TugasPegawaiController::class, 'updateStatus'])->name('pegawai.tugas.updateStatus');
    Route::post('/tugas/{id}/pengumpulan', [App\Http\Controllers\Pegawai\TugasPengumpulanController::class, 'store'])->name('pegawai.tugas.pengumpulan.store');
    Route::get('meeting', [App\Http\Controllers\Pegawai\PegawaiMeetingController::class, 'index'])->name('pegawai.meeting.index');
    Route::get(uri: 'meeting/{meeting}', action: [App\Http\Controllers\Pegawai\PegawaiMeetingController::class, 'show'])->name('pegawai.meeting.show');
    Route::get('/pegawai/gaji/slip/{gaji}', [PegawaiGajiController::class, 'unduhSlipGaji'])
        ->name('pegawai.gaji.unduh');
});
