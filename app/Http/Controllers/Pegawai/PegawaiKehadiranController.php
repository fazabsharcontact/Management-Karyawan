<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PegawaiKehadiranController extends Controller
{
    public function index(Request $request)
    {
        $pegawaiId = Auth::id(); // diasumsikan pegawai login via auth

        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->month);

        $kehadiran = Kehadiran::where('id_pegawai', $pegawaiId)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderByDesc('tanggal')
            ->get();

        return view('pegawai.kehadiran.index', compact('kehadiran', 'tahun', 'bulan'));
    }

    public function store(Request $request)
    {
        $pegawaiId = Auth::id();
        $tanggal = Carbon::today()->toDateString();

        // Cek sudah absen atau belum
        $sudahAbsen = Kehadiran::where('id_pegawai', $pegawaiId)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah melakukan kehadiran hari ini!');
        }

        Kehadiran::create([
            'id_pegawai' => $pegawaiId,
            'tanggal' => $tanggal,
            'status' => $request->status
        ]);

        return redirect()->route('pegawai.kehadiran.index')->with('success', 'Kehadiran berhasil dicatat!');
    }
}