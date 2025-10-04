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
use App\Http\Controllers\Admin\GajiMassalController;
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
    Route::post('gaji/cek-pegawai', [GajiController::class, 'cekGajiPegawai'])->name('admin.gaji.cek');
    Route::get('gaji/{gaji}/slip', [GajiController::class, 'unduhSlipGaji'])->name('admin.gaji.slip');
    Route::resource('jabatan', JabatanController::class)->names('admin.jabatan');
    Route::get('tunjangan-potongan', [TunjanganPotonganController::class, 'index'])->name('admin.tunjangan-potongan.index');
    Route::resource('master-tunjangan', MasterTunjanganController::class)->names('admin.master-tunjangan')->except(['index', 'show']);
    Route::resource('master-potongan', MasterPotonganController::class)->names('admin.master-potongan')->except(['index', 'show']);
    Route::get('tim-divisi', [TimDivisiController::class, 'index'])->name('admin.tim-divisi.index');
    Route::resource('divisi', DivisiController::class)->names('admin.divisi')->except(['index', 'show']);
    Route::resource('tim', TimController::class)->names('admin.tim')->except(['index', 'show']);
    Route::resource('meeting', MeetingController::class)->names('admin.meeting');
    Route::get('cuti', [CutiController::class, 'index'])->name('admin.cuti.index');
    Route::patch('cuti/{cuti}/status', [CutiController::class, 'updateStatus'])->name('admin.cuti.updateStatus');
    Route::post('cuti/reset-tahunan', [CutiController::class, 'resetCutiTahunan'])->name('admin.cuti.resetTahunan');
    Route::resource('pengumuman', PengumumanController::class)
        ->names('admin.pengumuman')
        ->only(['index', 'create', 'store', 'destroy']);
    Route::get('laporan-performa', [LaporanPerformaController::class, 'index'])->name('admin.laporan.performa');
    Route::get('laporan-performa/unduh-pdf', [LaporanPerformaController::class, 'unduhPdf'])->name('admin.laporan.performa.pdf');
    Route::get('tugas/pengumpulan', [AdminTugasPengumpulanController::class, 'index'])->name('admin.tugas_pengumpulan.index');
    Route::post('tugas/pengumpulan/{id}/status', [AdminTugasPengumpulanController::class, 'updateStatus'])->name('admin.tugas_pengumpulan.update');
    Route::get('/tugas', [AdminTugasController::class, 'index'])->name('admin.tugas.index');
    Route::post('/tugas', [AdminTugasController::class, 'store'])->name('admin.tugas.store');
    Route::get('kehadiran', [AdminKehadiranController::class, 'index'])->name('admin.kehadiran.index');
    Route::get('kehadiran/{pegawai}', [AdminKehadiranController::class, 'show'])->name('admin.kehadiran.show');
    Route::get('kehadiran/bukti/{id}', [AdminKehadiranController::class, 'downloadBukti'])->name('admin.kehadiran.downloadBukti');
    Route::get('gaji-massal/langkah-1', [GajiMassalController::class, 'langkahSatu'])->name('admin.gaji-massal.langkah1');
    Route::post('gaji-massal/langkah-2', [GajiMassalController::class, 'langkahDua'])->name('admin.gaji-massal.langkah2');
    Route::post('gaji-massal/simpan', [GajiMassalController::class, 'simpan'])->name('admin.gaji-massal.simpan');
    Route::post('gaji-massal/cek-gaji', [GajiMassalController::class, 'cekGajiSudahAda'])->name('admin.gaji-massal.cek');
});
// PegawaiDashboard
Route::prefix('pegawai')->middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
    Route::get('/gaji', [PegawaiGajiController::class, 'index'])->name('pegawai.gaji');
    Route::get('/kehadiran', [PegawaiKehadiranController::class, 'index'])->name('pegawai.kehadiran.index');
    Route::post('/kehadiran', [PegawaiKehadiranController::class, 'store'])->name('pegawai.kehadiran.store');
    Route::get('/pengumuman', [PegawaiPengumumanController::class, 'index'])->name('pegawai.pengumuman.index');
    Route::get('/pegawai/cuti', [PegawaiCutiController::class, 'index'])->name('pegawai.cuti.index');
    Route::get('/pegawai/cuti/create', [PegawaiCutiController::class, 'create'])->name('pegawai.cuti.create');
    Route::post('/pegawai/cuti', [PegawaiCutiController::class, 'store'])->name('pegawai.cuti.store');
    Route::get('/tugas', [TugasPegawaiController::class, 'index'])->name('pegawai.tugas.index');
    Route::get('/tugas/{id}', [TugasPegawaiController::class, 'show'])->name('pegawai.tugas.show');
    Route::post('/tugas/{id}/status', [TugasPegawaiController::class, 'updateStatus'])->name('pegawai.tugas.updateStatus');
    Route::post('/tugas/{id}/pengumpulan', [TugasPengumpulanController::class, 'store'])->name('pegawai.tugas.pengumpulan.store');
    Route::get('meeting', [PegawaiMeetingController::class, 'index'])->name('pegawai.meeting.index');
    Route::get(uri: 'meeting/{meeting}', action: [PegawaiMeetingController::class, 'show'])->name('pegawai.meeting.show');
    Route::get('/pegawai/gaji/slip/{gaji}', [PegawaiGajiController::class, 'unduhSlipGaji'])
        ->name('pegawai.gaji.unduh');
});
