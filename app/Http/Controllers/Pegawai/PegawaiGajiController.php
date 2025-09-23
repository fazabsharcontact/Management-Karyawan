<?php

// app/Http/Controllers/Pegawai/PegawaiGajiController.php
namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Gaji;
use Carbon\Carbon;

class PegawaiGajiController extends Controller
{
    public function index()
    {
        $pegawaiId = Auth::user()->id; // asumsi user_id sama dengan id_pegawai
        $tahun = request('tahun', Carbon::now()->year);
        $bulan = request('bulan', Carbon::now()->month);

        $riwayat = Gaji::where('id_pegawai', $pegawaiId)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        $detail = Gaji::where('id_pegawai', $pegawaiId)
            ->orderByDesc('updated_at')
            ->first();

        $bulanNama = [
            1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
            5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
            9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
        ];

        return view('pegawai.gaji.index', compact('riwayat', 'detail', 'tahun', 'bulan', 'bulanNama'));
    }
}