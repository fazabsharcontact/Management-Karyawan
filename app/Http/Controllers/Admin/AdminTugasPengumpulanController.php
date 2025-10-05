<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Tim;
use App\Models\TugasPengumpulan;
use App\Models\Tugas;
use Illuminate\Http\Request;

class AdminTugasPengumpulanController extends Controller
{
    /**
     * Menampilkan daftar semua pengumpulan tugas dengan filter.
     */
    public function index(Request $request)
    {
        $query = TugasPengumpulan::with(['tugas', 'pegawai.user', 'pegawai.jabatan', 'pegawai.tim.divisi'])
                    ->orderBy('created_at', 'desc');

        // Logika Pencarian berdasarkan Judul Tugas atau Nama Pegawai
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->whereHas('tugas', fn($sq) => $sq->where('judul_tugas', 'like', "%{$search}%"))
              ->orWhereHas('pegawai', fn($sq) => $sq->where('nama', 'like', "%{$search}%"));
        });

        // Logika Filter
        $query->when($request->filled('bulan'), fn($q) => $q->whereMonth('created_at', $request->bulan));
        $query->when($request->filled('tahun'), fn($q) => $q->whereYear('created_at', $request->tahun));
        $query->when($request->filled('status'), fn($q) => $q->where('status', $request->status));
        $query->when($request->filled('jabatan_id'), fn($q) => $q->whereHas('pegawai.jabatan', fn($sq) => $sq->where('id', $request->jabatan_id)));
        $query->when($request->filled('tim_id'), fn($q) => $q->whereHas('pegawai.tim', fn($sq) => $sq->where('id', $request->tim_id)));
        $query->when($request->filled('divisi_id'), fn($q) => $q->whereHas('pegawai.tim.divisi', fn($sq) => $sq->where('id', $request->divisi_id)));

        $pengumpulan = $query->paginate(15)->withQueryString();

        // Data untuk dropdown filter
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $tims = Tim::orderBy('nama_tim')->get();
        $divisis = Divisi::orderBy('nama_divisi')->get();
        $statuses = ['pending', 'diterima', 'revisi'];

        return view('admin.tugas_pengumpulan.index', compact(
            'pengumpulan', 'jabatans', 'tims', 'divisis', 'statuses'
        ));
    }

    /**
     * Memperbarui status pengumpulan (Terima/Revisi).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,revisi',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pengumpulan = TugasPengumpulan::findOrFail($id);
        $pengumpulan->status = $request->status;

        if ($request->status === 'revisi') {
            $pengumpulan->catatan = $request->catatan;
        } else {
            $pengumpulan->catatan = null;
        }

        $pengumpulan->save();

        $tugas = $pengumpulan->tugas;
        if ($tugas) { // Pastikan tugas masih ada
            if ($request->status === 'diterima') {
                $tugas->status = 'Selesai';
            } elseif ($request->status === 'revisi') {
                $tugas->status = 'Dikerjakan';
            }
            $tugas->save();
        }

        return redirect()->back()->with('success', 'Status pengumpulan berhasil diperbarui.');
    }
}