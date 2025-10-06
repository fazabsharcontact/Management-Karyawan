<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminKehadiranController extends Controller
{
    /**
     * Menampilkan daftar kehadiran dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->month);

        $query = Kehadiran::with('pegawai.user')
            ->when($request->pegawai_id, function ($query) use ($request) {
                $query->where('pegawai_id', $request->pegawai_id);
            })
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            
            // PERBAIKAN: Tambahkan urutan kedua berdasarkan waktu pembuatan
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        // Menggunakan paginate() untuk membatasi 20 data per halaman
        $kehadiran = $query->paginate(20)->withQueryString();

        // Query untuk data rekapitulasi
        $rekap = Kehadiran::selectRaw('pegawai_id,
                SUM(CASE WHEN status="Hadir" THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN status="Absen" THEN 1 ELSE 0 END) as total_absen,
                SUM(CASE WHEN status="Sakit" THEN 1 ELSE 0 END) as total_sakit,
                SUM(CASE WHEN status="Izin" THEN 1 ELSE 0 END) as total_izin,
                SUM(CASE WHEN status="Terlambat" THEN 1 ELSE 0 END) as total_terlambat')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->when($request->pegawai_id, fn($q) => $q->where('pegawai_id', $request->pegawai_id)) // Filter rekap juga
            ->groupBy('pegawai_id')
            ->with('pegawai.user')
            ->get();
        
        // Data pegawai untuk dropdown Select2
        $pegawais = Pegawai::select('id', 'nama')->orderBy('nama')->get();

        return view('admin.kehadiran.index', compact('kehadiran', 'rekap', 'tahun', 'bulan', 'pegawais'));
    }

    /**
     * Menampilkan detail kehadiran per pegawai.
     */
    public function show($pegawaiId, Request $request)
    {
        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->month);
        $pegawai = Pegawai::with('user')->findOrFail($pegawaiId);
        $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc') // Tambahkan juga di sini untuk konsistensi
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
        $absolutePath = Storage::disk('public')->path($filePath);
        if (!file_exists($absolutePath)) {
            return redirect()->back()->with('error', 'File bukti tidak ditemukan di server.');
        }
        $fileName = basename($filePath);
        return response()->download($absolutePath, $fileName);
    }
}