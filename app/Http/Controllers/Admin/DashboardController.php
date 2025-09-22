<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Pegawai
        $totalPegawai = DB::table('pegawai')->count();

        // Total Gaji bulan ini
        $totalGaji = DB::table('gaji')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('total_gaji');

        // Pegawai per Jabatan
        $jabatanData = DB::table('pegawai as p')
            ->join('jabatan as j', 'p.id_jabatan', '=', 'j.id_jabatan')
            ->select('j.nama_jabatan', DB::raw('COUNT(p.id_pegawai) as jumlah'))
            ->groupBy('j.nama_jabatan')
            ->pluck('jumlah', 'j.nama_jabatan')
            ->toArray();

        // Aktivitas Terbaru
        $aktivitas = collect([]);

        $pegawaiBaru = DB::table('pegawai')
            ->whereDate('updated_at', Carbon::today())
            ->select('updated_at as waktu', DB::raw("CONCAT('👤 Pegawai baru ditambahkan: <b>', nama, '</b>') as keterangan"));

        $kehadiran = DB::table('kehadiran as k')
            ->join('pegawai as p', 'k.id_pegawai', '=', 'p.id_pegawai')
            ->whereDate('k.updated_at', Carbon::today())
            ->select('k.updated_at as waktu', DB::raw("CONCAT('⏳ Kehadiran: <b>', p.nama, '</b> (', k.status, ')') as keterangan"));

        $gaji = DB::table('gaji as g')
            ->join('pegawai as p', 'g.id_pegawai', '=', 'p.id_pegawai')
            ->whereDate('g.updated_at', Carbon::today())
            ->select('g.updated_at as waktu', DB::raw("CONCAT('💰 Gaji diperbarui: <b>', p.nama, '</b> (Rp ', FORMAT(g.total_gaji, 0, 'id_ID'), ',-)') as keterangan"));

        $jabatan = DB::table('pegawai as p')
            ->join('jabatan as j', 'p.id_jabatan', '=', 'j.id_jabatan')
            ->whereDate('p.updated_at', Carbon::today())
            ->select('p.updated_at as waktu', DB::raw("CONCAT('🔄 Ganti Jabatan: <b>', p.nama, '</b> menjadi <b>', j.nama_jabatan, '</b>') as keterangan"));

        $aktivitas = $pegawaiBaru
            ->unionAll($kehadiran)
            ->unionAll($gaji)
            ->unionAll($jabatan)
            ->orderBy('waktu', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('totalPegawai', 'totalGaji', 'jabatanData', 'aktivitas'));
    }
}