<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PegawaiKehadiranController extends Controller
{
    /**
     * Menampilkan halaman riwayat kehadiran pegawai.
     */
    public function index(Request $request)
    {
        $pegawai = Auth::user()->pegawai;
        if (!$pegawai) {
            abort(403, 'Profil pegawai tidak ditemukan.');
        }

        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->month);

        $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderByDesc('tanggal')
            ->paginate(10) // Paginasi 10 data per halaman
            ->withQueryString();

        return view('pegawai.kehadiran.index', compact('kehadiran', 'tahun', 'bulan'));
    }

    /**
     * Memproses presensi (masuk, pulang, izin, atau sakit).
     */
    public function store(Request $request)
    {
        $pegawai = Auth::user()->pegawai;
        if (!$pegawai) {
            return back()->with('error', 'Profil pegawai tidak ditemukan.');
        }

        $tanggal = Carbon::today('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');

        $kehadiranHariIni = Kehadiran::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', $tanggal->toDateString())
            ->first();

        // --- 1. JIKA BELUM ADA PRESENSI SAMA SEKALI HARI INI ---
        if (!$kehadiranHariIni) {
            $status = $request->input('status');

            if ($status === 'Hadir') {
                $startAbsen = $tanggal->copy()->addHours(8)->addMinutes(50);
                $endHadir = $tanggal->copy()->addHours(9)->addMinutes(15);
                $endTerlambat = $tanggal->copy()->addHours(13);

                if (!$now->between($startAbsen, $endTerlambat)) {
                    return back()->with('error', "Waktu presensi masuk hanya antara 08:50 - 13:00 WIB.");
                }
                $statusKehadiran = $now->lte($endHadir) ? 'Hadir' : 'Terlambat';
                
                Kehadiran::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $tanggal->toDateString(),
                    'status' => $statusKehadiran,
                    'jam_masuk' => $now->toTimeString(),
                ]);
                return back()->with('success', 'Presensi masuk berhasil dicatat! Status: ' . $statusKehadiran);
            }
            
            if (in_array($status, ['Izin', 'Sakit'])) {
                 $request->validate(['bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048']);
                
                $buktiPath = null;
                if ($request->hasFile('bukti')) {
                    $buktiPath = $request->file('bukti')->store('bukti_kehadiran', 'public');
                }

                Kehadiran::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $tanggal->toDateString(),
                    'status' => $status,
                    'keterangan' => $request->keterangan,
                    'bukti' => $buktiPath,
                ]);
                return back()->with('success', 'Pengajuan ' . $status . ' berhasil dikirim.');
            }
            return back()->with('error', 'Aksi tidak valid.');
        }

        // --- 2. JIKA SUDAH PRESENSI MASUK, PROSES PRESENSI PULANG ---
        if (in_array($kehadiranHariIni->status, ['Hadir', 'Terlambat']) && !$kehadiranHariIni->jam_pulang) {
            $kehadiranHariIni->update(['jam_pulang' => $now->toTimeString()]);
            return back()->with('success', 'Presensi pulang berhasil dicatat!');
        }

        return back()->with('error', 'Anda sudah melakukan presensi lengkap atau status Anda hari ini adalah ' . $kehadiranHariIni->status . '.');
    }
}