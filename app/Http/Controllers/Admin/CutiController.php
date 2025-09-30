<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use App\Models\Pegawai; // <-- 1. Import model Pegawai
use App\Models\SisaCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan cuti dengan filter.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->get('status');
        $search = $request->get('search');
        
        // Query untuk daftar pengajuan cuti (sudah benar)
        $queryCuti = Cuti::with('pegawai.jabatan', 'pegawai.sisaCuti')->latest();
        $queryCuti->when($statusFilter, fn($q) => $q->where('status', $statusFilter));
        $queryCuti->when($search, fn($q) => $q->whereHas('pegawai', fn($sq) => $sq->where('nama', 'like', "%{$search}%")));
        $cutis = $queryCuti->paginate(10, ['*'], 'cuti_page');

        // --- 2. LOGIKA BARU: Ambil data semua pegawai untuk tabel rekapitulasi ---
        $pegawais = Pegawai::with(['jabatan', 'sisaCuti'])
            ->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%"))
            ->orderBy('nama')
            ->paginate(10, ['*'], 'pegawai_page');

        // 3. Kirim kedua data ke view
        return view('admin.cuti.index', compact('cutis', 'pegawais'));
    }

    /**
     * Memperbarui status pengajuan cuti (Disetujui / Ditolak).
     */
    public function updateStatus(Request $request, Cuti $cuti)
    {
        $request->validate(['status' => 'required|in:Disetujui,Ditolak']);
        $newStatus = $request->input('status');

        if ($cuti->status !== 'Diajukan') {
            return back()->with('error', 'Status cuti ini sudah diproses sebelumnya.');
        }

        if ($newStatus === 'Disetujui') {
            $durasiCuti = $cuti->durasi_hari_kerja;
            
            $sisaCutiPegawai = SisaCuti::firstOrCreate(
                ['pegawai_id' => $cuti->pegawai_id]
            );

            if ($sisaCutiPegawai->sisa_cuti < $durasiCuti) {
                return back()->with('error', 'Gagal menyetujui: Sisa cuti pegawai tidak mencukupi.');
            }

            $sisaCutiPegawai->decrement('sisa_cuti', $durasiCuti);
        }

        $cuti->status = $newStatus;
        $cuti->disetujui_oleh_id = Auth::id();
        $cuti->save();

        return redirect()->route('admin.cuti.index')->with('success', "Pengajuan cuti berhasil di-{$newStatus}.");
    }

    /**
     * Mereset sisa cuti tahunan semua pegawai secara manual.
     */
    public function resetCutiTahunan()
    {
        SisaCuti::query()->update(['sisa_cuti' => 12]);
        return redirect()->route('admin.cuti.index')->with('success', 'Sisa cuti tahunan semua pegawai berhasil direset menjadi 12.');
    }
}

