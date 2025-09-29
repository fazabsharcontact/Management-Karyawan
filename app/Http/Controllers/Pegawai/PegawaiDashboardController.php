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
        $user = Auth::user();

        if (!$user || $user->role !== 'pegawai') {
            abort(403, 'Unauthorized');
        }

        $pegawai = Pegawai::where('user_id', $user->id)->firstOrFail();

        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        // Total Hadir
        $totalHariMasuk = Kehadiran::where('pegawai_id', $pegawai->id_pegawai)
            ->where('status', 'Hadir')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        // Total Izin
        $totalIzin = Kehadiran::where('pegawai_id', $pegawai->id_pegawai)
            ->where('status', 'Izin')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        // Total Sakit
        $totalSakit = Kehadiran::where('pegawai_id', $pegawai->id_pegawai)
            ->where('status', 'Sakit')
            ->whereMonth('tanggal', $bulanSekarang)
            ->whereYear('tanggal', $tahunSekarang)
            ->count();

        // Total Gaji bulan ini
        $totalGaji = Gaji::where('pegawai_id', $pegawai->id_pegawai)
            ->where('bulan', $bulanSekarang)
            ->where('tahun', $tahunSekarang)
            ->sum('gaji_bersih');

        // Grafik Gaji per bulan
        $gajiPerBulan = Gaji::where('pegawai_id', $pegawai->id_pegawai)
            ->where('tahun', $tahunSekarang)
            ->orderBy('bulan')
            ->pluck('gaji_bersih', 'bulan')
            ->toArray();

        $bulanArray = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
        $dataGaji = array_fill(0, 12, 0);

        foreach ($gajiPerBulan as $bulan => $total) {
            $dataGaji[$bulan - 1] = $total;
        }

        return view('pegawai.dashboard', compact(
            'pegawai',
            'totalHariMasuk',
            'totalIzin',
            'totalSakit',
            'totalGaji',
            'bulanArray',
            'dataGaji'
        ));
    }
}