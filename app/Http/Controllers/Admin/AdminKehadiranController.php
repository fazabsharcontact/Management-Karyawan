<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // Pastikan ini diimpor

class AdminKehadiranController extends Controller
{
    // ... (metode index dan show tidak diubah, sudah benar) ...
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->month);

        $kehadiran = Kehadiran::with('pegawai.user')
            ->when($request->pegawai_id, function ($query) use ($request) {
                $query->where('pegawai_id', $request->pegawai_id);
            })
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'desc')
            ->get();

        $rekap = Kehadiran::selectRaw('pegawai_id,
            SUM(CASE WHEN status="Hadir" THEN 1 ELSE 0 END) as total_hadir,
            SUM(CASE WHEN status="Absen" THEN 1 ELSE 0 END) as total_absen,
            SUM(CASE WHEN status="Sakit" THEN 1 ELSE 0 END) as total_sakit,
            SUM(CASE WHEN status="Izin" THEN 1 ELSE 0 END) as total_izin,
            SUM(CASE WHEN status="Terlambat" THEN 1 ELSE 0 END) as total_terlambat') // Tambah Terlambat di Rekap
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->groupBy('pegawai_id')
            ->with('pegawai.user')
            ->get();

        $pegawai = Pegawai::orderBy('nama')->get();

        return view('admin.kehadiran.index', compact('kehadiran', 'rekap', 'tahun', 'bulan', 'pegawai'));
    }


    public function show($pegawaiId, Request $request)
    {
        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->month);

        $pegawai = Pegawai::with('user')->findOrFail($pegawaiId);

        $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.kehadiran.show', compact('pegawai', 'kehadiran', 'tahun', 'bulan'));
    }

    /**
     * Mengunduh file bukti kehadiran (Izin/Sakit).
     */
    public function downloadBukti($id)
    {
        $kehadiran = Kehadiran::findOrFail($id);

        if (!$kehadiran->bukti) {
            return redirect()->back()->with('error', 'Tidak ada file bukti untuk absensi ini.');
        }

        $filePath = $kehadiran->bukti;

        // Gunakan Storage::path() untuk mendapatkan path absolut
        $absolutePath = Storage::disk('public')->path($filePath);

        // Cek apakah file ada secara fisik
        if (!file_exists($absolutePath)) {
            return redirect()->back()->with('error', 'File bukti tidak ditemukan di server (Path: ' . $absolutePath . ')');
        }

        // Tentukan nama file yang akan didownload
        // Kita ambil nama asli file yang disimpan (bagian terakhir dari path)
        $fileName = basename($filePath);

        // Menggunakan helper response()->download()
        return response()->download($absolutePath, $fileName);
    }
}
