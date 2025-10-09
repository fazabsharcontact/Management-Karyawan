<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use App\Models\Kehadiran;
use App\Models\Gaji;
use App\Models\PengumumanPenerima;
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

        // === STATISTIK KEHADIRAN ===
        $totalHariMasuk = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Hadir')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        $totalIzin = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Izin')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        $totalSakit = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Sakit')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        $totalAbsen = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Absen')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        $totalTerlambat = Kehadiran::where('pegawai_id', $pegawaiId)
            ->where('status', 'Terlambat')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        // === GAJI ===
        $totalGajiTahun = Gaji::where('pegawai_id', $pegawaiId)
            ->where('tahun', $tahunSekarang)
            ->sum('gaji_bersih');

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

        $dataKehadiran = [
            'Hadir' => $totalHariMasuk,
            'Izin' => $totalIzin,
            'Sakit' => $totalSakit,
            'Absen' => $totalAbsen,
            'Terlambat' => $totalTerlambat,
        ];

        // === PENGUMUMAN ===
        $pengumumans = \App\Models\PengumumanPenerima::with('pengumuman')
            ->where(function ($q) use ($pegawai) {
                $q->where(function ($q2) use ($pegawai) {
                    $q2->where('target_type', 'pegawai')
                        ->where('target_id', $pegawai->id);
                })
                ->orWhere(function ($q2) use ($pegawai) {
                    $q2->where('target_type', 'jabatan')
                        ->where('target_id', $pegawai->jabatan_id);
                })
                ->orWhere(function ($q2) use ($pegawai) {
                    $q2->where('target_type', 'divisi')
                        ->where('target_id', $pegawai->divisi_id);
                })
                ->orWhere(function ($q2) use ($pegawai) {
                    $q2->where('target_type', 'tim')
                        ->where('target_id', $pegawai->tim_id);
                })
                ->orWhere('target_type', 'semua');
            })
            ->orderByDesc('created_at')
            ->get();

        // === RETURN VIEW ===
        return view('pegawai.dashboard', compact(
            'pegawai',
            'totalHariMasuk',
            'totalIzin',
            'totalSakit',
            'totalAbsen',
            'totalTerlambat',
            'totalGajiTahun',
            'bulanArray',
            'dataGaji',
            'dataKehadiran',
            'tahunSekarang',
            'bulanSekarang',
            'pengumumans'
        ));
    }
}