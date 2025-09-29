<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CutiController extends Controller
{
    /**
     * Menampilkan daftar semua pengajuan cuti dengan filter.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->get('status');
        $search = $request->get('search');

        // Query dasar dengan eager loading untuk efisiensi
        $query = Cuti::with('pegawai.jabatan')->latest();

        // Terapkan filter berdasarkan status
        $query->when($statusFilter, function ($q) use ($statusFilter) {
            $q->where('status', $statusFilter);
        });

        // Terapkan filter pencarian berdasarkan nama pegawai
        $query->when($search, function ($q) use ($search) {
            $q->whereHas('pegawai', function ($subQuery) use ($search) {
                $subQuery->where('nama', 'like', "%{$search}%");
            });
        });

        $cutis = $query->paginate(10);

        return view('admin.cuti.index', compact('cutis'));
    }

    /**
     * Memperbarui status pengajuan cuti (Disetujui / Ditolak).
     */
    public function updateStatus(Request $request, Cuti $cuti)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        $newStatus = $request->input('status');

        // Hanya proses jika statusnya masih 'Diajukan'
        if ($cuti->status !== 'Diajukan') {
            return back()->with('error', 'Status cuti ini sudah diproses sebelumnya.');
        }

        // Jika disetujui, hitung durasi dan kurangi sisa cuti pegawai
        if ($newStatus === 'Disetujui') {
            $tanggalMulai = Carbon::parse($cuti->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($cuti->tanggal_selesai);
            
            // Menghitung selisih hari (inklusif)
            $durasiCuti = $tanggalSelesai->diffInDays($tanggalMulai) + 1;

            $pegawai = $cuti->pegawai;

            // Pastikan sisa cuti mencukupi
            if ($pegawai->sisa_cuti_tahunan < $durasiCuti) {
                return back()->with('error', 'Gagal menyetujui: Sisa cuti pegawai tidak mencukupi.');
            }

            // Kurangi sisa cuti
            $pegawai->sisa_cuti_tahunan -= $durasiCuti;
            $pegawai->save();
        }

        // Update status cuti dan catat siapa yang menyetujui/menolak
        $cuti->status = $newStatus;
        $cuti->disetujui_oleh_id = Auth::id(); // Mengambil ID user admin yang sedang login
        $cuti->save();

        return redirect()->route('admin.cuti.index')
            ->with('success', "Pengajuan cuti berhasil di-{$newStatus}.");
    }
}