<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use App\Models\Kehadiran;
use App\Models\Gaji;
use Carbon\Carbon;

class PegawaiDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $pegawai = Pegawai::where('user_id', $userId)->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai Anda tidak ditemukan.');
        }

        $pegawaiId = $pegawai->id;
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        // --- STATISTIK KEHADIRAN BULAN INI ---

        // Total Hadir (Sudah benar)
        $totalHariMasuk = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Hadir')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        // Total Izin (Sudah benar)
        $totalIzin = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Izin')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        // Total Sakit (Sudah benar)
        $totalSakit = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Sakit')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        // **BARU:** Total Absen
        $totalAbsen = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Absen') // Asumsi status 'Absen'
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        // **BARU:** Total Terlambat
        // Asumsi: 'Terlambat' adalah status terpisah atau sub-status kehadiran
        $totalTerlambat = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Terlambat') // Ganti 'is_terlambat' dengan kolom yang relevan di tabel Kehadiran Anda
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();


        // --- STATISTIK GAJI TAHUN INI ---

        // **PERUBAHAN:** Total Gaji TAHUN ini (bukan bulan ini)
        $totalGajiTahun = Gaji::where('pegawai_id', $pegawaiId)
            ->where('tahun', $tahunSekarang)
            ->sum('gaji_bersih');

        // --- DATA GRAFIK GAJI PER BULAN (Sudah benar) ---

        $gajiPerBulan = Gaji::where('pegawai_id', $pegawaiId)
            ->where('tahun', $tahunSekarang)
            ->orderBy('bulan')
            ->pluck('gaji_bersih', 'bulan')
            ->toArray();

        $bulanArray = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
        $dataGaji = array_fill(0, 12, 0);

        foreach ($gajiPerBulan as $bulan => $total) {
            $dataGaji[$bulan - 1] = $total;
        }

        // --- DATA GRAFIK KEHADIRAN BULAN INI ---
        $dataKehadiran = [
            'Hadir' => $totalHariMasuk,
            'Izin' => $totalIzin,
            'Sakit' => $totalSakit,
            'Absen' => $totalAbsen,
            'Terlambat' => $totalTerlambat,
        ];

        return view('pegawai.dashboard', compact(
            'pegawai',
            'totalHariMasuk',
            'totalIzin',
            'totalSakit',
            'totalAbsen', // BARU
            'totalTerlambat', // BARU
            'totalGajiTahun', // GANTI: totalGaji menjadi totalGajiTahun
            'bulanArray',
            'dataGaji',
            'dataKehadiran', // BARU untuk chart
            'tahunSekarang', // <--- JANGAN LUPA DIBUNGKUS DENGAN COMPACT!
            'bulanSekarang'  // <--- Tambahkan juga bulanSekarang (jika ingin digunakan)
        ));
    }
}
