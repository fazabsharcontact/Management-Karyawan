<?php

// app/Http/Controllers/Pegawai/PegawaiGajiController.php
namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Gaji;
use App\Models\Pegawai; // <-- TAMBAHKAN INI
use Carbon\Carbon;

class PegawaiGajiController extends Controller
{
    public function index()
    {
        // 1. Ambil User ID yang sedang login
        $userId = Auth::id();

        // 2. Cari Pegawai ID dari tabel 'pegawai' berdasarkan user_id
        // Asumsi: Ada kolom 'user_id' di tabel 'pegawai'
        $pegawai = Pegawai::where('user_id', $userId)->first();

        // Cek jika data pegawai tidak ditemukan (Penting!)
        if (!$pegawai) {
            // Beri respons jika user yang login tidak punya data pegawai yang terasosiasi
            // Atau redirect ke halaman error
            return redirect()->back()->with('error', 'Data pegawai Anda tidak ditemukan.');
        }

        // 3. Gunakan ID Pegawai yang benar dari hasil query
        $pegawaiId = $pegawai->id; // <-- PENGGANTIAN UTAMA DI SINI

        $tahun = request('tahun', Carbon::now()->year);
        $bulan = request('bulan', Carbon::now()->month);

        $riwayat = Gaji::where('pegawai_id', $pegawaiId) // Sudah menggunakan $pegawaiId yang benar
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        $detail = Gaji::where('pegawai_id', $pegawaiId)
            ->orderByDesc('updated_at')
            ->first();

        $bulanNama = [
            1 => "Januari",
            2 => "Februari",
            3 => "Maret",
            4 => "April",
            5 => "Mei",
            6 => "Juni",
            7 => "Juli",
            8 => "Agustus",
            9 => "September",
            10 => "Oktober",
            11 => "November",
            12 => "Desember"
        ];

        return view('pegawai.gaji.index', compact('riwayat', 'detail', 'tahun', 'bulan', 'bulanNama'));
    }
}
