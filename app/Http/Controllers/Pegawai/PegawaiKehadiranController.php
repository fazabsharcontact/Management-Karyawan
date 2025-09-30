<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Pegawai;

class PegawaiKehadiranController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Pegawai::where('user_id', Auth::id())->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Pegawai tidak ditemukan!');
        }

        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->month);

        $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id) // ✅ pakai id
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderByDesc('tanggal')
            ->get();

        return view('pegawai.kehadiran.index', compact('kehadiran', 'tahun', 'bulan'));
    }

    public function store(Request $request)
    {
        $pegawai = Pegawai::where('user_id', Auth::id())->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Pegawai tidak ditemukan!');
        }

        $tanggal = Carbon::today()->toDateString();

        // Cek sudah absen atau belum
        $sudahAbsen = Kehadiran::where('pegawai_id', $pegawai->id) // ✅ pakai id
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah melakukan kehadiran hari ini!');
        }

        Kehadiran::create([
            'pegawai_id' => $pegawai->id, // ✅ pakai id
            'tanggal' => $tanggal,
            'status' => $request->status
        ]);

        return redirect()->route('pegawai.kehadiran.index')->with('success', 'Kehadiran berhasil dicatat!');
    }
}
