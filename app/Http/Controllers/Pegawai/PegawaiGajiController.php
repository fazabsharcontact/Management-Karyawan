<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Tambahkan Request
use Illuminate\Support\Facades\Auth;
use App\Models\Gaji;
use App\Models\Pegawai;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan ini di-import

class PegawaiGajiController extends Controller
{
    /**
     * Menampilkan riwayat gaji pegawai yang sedang login.
     */
    public function index(Request $request) // Gunakan Request untuk mengambil input
    {
        // 1. Ambil User ID yang sedang login
        $userId = Auth::id();

        // 2. Cari Pegawai ID dari tabel 'pegawai' berdasarkan user_id
        $pegawai = Pegawai::where('user_id', $userId)->first();

        // Cek jika data pegawai tidak ditemukan (Penting!)
        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai Anda tidak ditemukan.');
        }

        $pegawaiId = $pegawai->id;

        // Ambil filter dari request, default bulan/tahun sekarang
        $tahun = $request->get('tahun', Carbon::now()->year);
        $bulan = $request->get('bulan', Carbon::now()->month);

        // Ambil riwayat gaji
        $riwayat = Gaji::where('pegawai_id', $pegawaiId)
            // Tambahkan filter bulan dan tahun untuk riwayat jika ingin ditampilkan per bulan/tahun
            // ->where('tahun', $tahun) // Jika ingin memfilter riwayat
            // ->where('bulan', $bulan) // Jika ingin memfilter riwayat
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        // Ambil detail gaji terbaru untuk ditampilkan di atas (jika ada)
        $detail = Gaji::where('pegawai_id', $pegawaiId)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->with(['tunjanganDetails.masterTunjangan', 'potonganDetails.masterPotongan', 'pegawai.jabatan'])
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

    /**
     * Mengunduh slip gaji dalam format PDF.
     * @param \App\Models\Gaji $gaji
     */
    public function unduhSlipGaji(Gaji $gaji)
    {
        // 1. Cek User ID yang sedang login dan ambil data pegawai
        $userId = Auth::id();
        $pegawai = Pegawai::where('user_id', $userId)->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai Anda tidak ditemukan.');
        }

        // 2. Lakukan Authorization: Pastikan ID Gaji yang diminta adalah milik pegawai yang sedang login
        if ($gaji->pegawai_id !== $pegawai->id) {
            // Jika bukan, tolak akses!
            abort(403, 'Akses ditolak. Slip gaji ini bukan milik Anda.');
        }

        // 3. Load relasi yang dibutuhkan
        $gaji->load(['pegawai.jabatan', 'tunjanganDetails.masterTunjangan', 'potonganDetails.masterPotongan']);

        // 4. Proses generate PDF
        $data = ['gaji' => $gaji];
        // Asumsi: View untuk slip gaji berada di 'pegawai.gaji.slip-gaji-pdf'
        $pdf = Pdf::loadView('pegawai.gaji.slip-gaji-pdf', $data);

        // 5. Nama file
        $bulanNama = [1 => "Januari", 2 => "Februari", /* ... */ 12 => "Desember"];
        $bulanStr = $bulanNama[$gaji->bulan] ?? 'Bulan';

        $namaFile = 'slip-gaji-' . $gaji->pegawai->nama . '-' . $bulanStr . '-' . $gaji->tahun . '.pdf';

        return $pdf->download($namaFile);
    }
}
