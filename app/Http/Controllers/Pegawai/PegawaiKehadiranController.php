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
    /**
     * Menampilkan halaman riwayat kehadiran pegawai.
     */
    public function index(Request $request)
    {
        $pegawai = Pegawai::where('user_id', Auth::id())->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Pegawai tidak ditemukan!');
        }

        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->month);

        $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderByDesc('tanggal')
            ->get();

        return view('pegawai.kehadiran.index', compact('kehadiran', 'tahun', 'bulan'));
    }

    /**
     * Memproses absensi (masuk, pulang, izin, atau sakit).
     */
    public function store(Request $request)
    {
        $pegawai = Pegawai::where('user_id', Auth::id())->first();
        if (!$pegawai) {
            return redirect()->back()->with('error', 'Pegawai tidak ditemukan!');
        }

        $tanggal = Carbon::today('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        // --- 1. ABSEN MASUK / IZIN / SAKIT (Jika Belum Ada Record) ---
        if (!$kehadiran) {

            // Batas Waktu Absensi
            $startAbsen = Carbon::createFromTime(8, 50, 0, 'Asia/Jakarta');
            $endHadir = Carbon::createFromTime(9, 10, 0, 'Asia/Jakarta');      // Batas Akhir HADIR: 09:10:00
            $endTerlambat = Carbon::createFromTime(13, 0, 0, 'Asia/Jakarta');  // Batas Akhir TERLAMBAT/IZIN/SAKIT: 13:00:00

            $inputStatus = $request->input('status');

            // PASTIKAN VARIABEL INI DIDEFINISIKAN TEPAT
            $isFormIzinSakit = in_array($inputStatus, ['Izin', 'Sakit']);

            // --- LOGIKA UTAMA ABSEN MASUK/TERLAMBAT (Hadir/Terlambat) ---
            if ($inputStatus == 'Hadir') {

                // ... logika Hadir/Terlambat di sini (sudah benar) ...

                // Tentukan status: Hadir atau Terlambat
                if ($now->lessThanOrEqualTo($endHadir)) {
                    $status = 'Hadir'; // 08:50:00 s/d 09:10:00
                } else {
                    $status = 'Terlambat'; // 09:10:01 s/d 13:00:00
                }
                $jamMasuk = $now->toTimeString();

                // --- LOGIKA UTAMA IZIN/SAKIT (Dari Modal Pop-up) ---
            } elseif ($isFormIzinSakit) { // PASTIKAN MASUK KE BLOK INI DENGAN TEPAT

                // Cek batasan waktu untuk Izin/Sakit (08:50 - 13:00)
                if ($now->lessThan($startAbsen) || $now->greaterThan($endTerlambat)) {
                    return redirect()->back()->with('error', "Pengajuan Izin/Sakit HANYA bisa dilakukan antara 08:50 sampai 13:00.");
                }

                $status = $inputStatus;
                $jamMasuk = null;

                // Validasi Bukti untuk Izin/Sakit (Sudah Benar)
                $request->validate(
                    ['bukti' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'],
                    ['bukti.required' => 'Wajib upload bukti untuk status Izin atau Sakit.']
                );
            } else {
                return redirect()->back()->with('error', 'Status kehadiran tidak valid.');
            }

            // Handle upload file bukti (BLOK INI YANG AKAN MEMBUAT 'bukti' TERISI)
            $buktiPath = null;
            // Pastikan pengecekan status menggunakan variabel $isFormIzinSakit yang sudah diset di atas
            if ($isFormIzinSakit && $request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('bukti_kehadiran', 'public');
            }

            // Simpan record baru
            Kehadiran::create([
                'pegawai_id' => $pegawai->id,
                'tanggal' => $tanggal,
                'status' => $status,
                'jam_masuk' => $jamMasuk,
                'keterangan' => $request->keterangan ?? null, // Menggunakan null coalescing operator
                'bukti' => $buktiPath, // INI YANG DIMASUKKAN KE KOLOM BUKTI
            ]);

            return redirect()->route('pegawai.kehadiran.index')->with('success', 'Absensi masuk berhasil dicatat! Status: ' . $status);
        }

        // --- 2. ABSEN PULANG (Jika Sudah Ada Record Masuk) ---
        if (!$kehadiran->jam_pulang) {
            // Cek apakah statusnya adalah Hadir atau Terlambat (yang harus absen pulang)
            if (!in_array($kehadiran->status, ['Hadir', 'Terlambat'])) {
                return redirect()->back()->with('error', 'Anda tidak perlu absen pulang karena status kehadiran hari ini: ' . $kehadiran->status);
            }

            $pulangMulai = Carbon::createFromTime(17, 0, 0, 'Asia/Jakarta');
            $pulangAkhir = Carbon::createFromTime(19, 0, 0, 'Asia/Jakarta');

            if (!$now->between($pulangMulai, $pulangAkhir)) {
                return redirect()->back()->with('error', 'Waktu absen pulang HANYA antara 17:00 sampai 19:00.');
            }

            // Update record dengan jam pulang
            $kehadiran->update([
                'jam_pulang' => $now->toTimeString(),
            ]);

            return redirect()->route('pegawai.kehadiran.index')->with('success', 'Absensi pulang berhasil dicatat!');
        }

        // --- 3. JIKA SUDAH ABSEN MASUK & PULANG ---
        return redirect()->back()->with('error', 'Anda sudah melakukan absen masuk dan pulang hari ini!');
    }
}
