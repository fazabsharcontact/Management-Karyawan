<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Pegawai
        $totalPegawai = Pegawai::count();

        // Total Gaji Periode Terakhir
        $periodeGajiTerbaru = Gaji::select('tahun', 'bulan')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->first();

        $totalGaji = 0;
        if ($periodeGajiTerbaru) {
            $totalGaji = Gaji::where('tahun', $periodeGajiTerbaru->tahun)
                ->where('bulan', $periodeGajiTerbaru->bulan)
                ->sum('gaji_bersih');
        }

        // Pegawai per Jabatan
        $jabatanData = Jabatan::withCount('pegawais')
            ->pluck('pegawais_count', 'nama_jabatan')
            ->toArray();

        // Jangkauan waktu diperluas untuk debugging
        $jangkauanWaktu = Carbon::now()->subDays(365); 

        // Query untuk aktivitas
        $pegawaiBaru = DB::table('pegawais')
            ->where('created_at', '>=', $jangkauanWaktu)
            ->select('created_at as waktu', DB::raw("CONCAT('👤 Pegawai baru ditambahkan: <b>', nama, '</b>') as keterangan"));

        $kehadiran = DB::table('kehadirans as k')
            ->join('pegawais as p', 'k.pegawai_id', '=', 'p.id')
            ->where('k.created_at', '>=', $jangkauanWaktu)
            ->select('k.created_at as waktu', DB::raw("CONCAT('⏳ Kehadiran: <b>', p.nama, '</b> (', k.status, ')') as keterangan"));

        $gaji = DB::table('gajis as g')
            ->join('pegawais as p', 'g.pegawai_id', '=', 'p.id')
            ->where('g.created_at', '>=', $jangkauanWaktu)
            ->select('g.created_at as waktu', DB::raw("CONCAT('💰 Gaji dibuat: <b>', p.nama, '</b> (Rp ', FORMAT(g.gaji_bersih, 0, 'id_ID'), ',-)') as keterangan"));

        $pegawaiUpdate = DB::table('pegawais as p')
            ->join('jabatans as j', 'p.jabatan_id', '=', 'j.id')
            ->where('p.updated_at', '>=', $jangkauanWaktu)
            ->whereColumn('p.updated_at', '!=', 'p.created_at')
            ->select('p.updated_at as waktu', DB::raw("CONCAT('🔄 Data Pegawai diperbarui: <b>', p.nama, '</b> (Jabatan: <b>', j.nama_jabatan, '</b>)') as keterangan"));

        $aktivitas = $pegawaiBaru
            ->unionAll($kehadiran)
            ->unionAll($gaji)
            ->unionAll($pegawaiUpdate)
            ->orderBy('waktu', 'desc')
            ->limit(10)
            ->get();
        
        // dd() sudah dihapus, sekarang data akan dikirim ke view
        return view('admin.dashboard', compact('totalPegawai', 'totalGaji', 'jabatanData', 'aktivitas'));
    }
}

