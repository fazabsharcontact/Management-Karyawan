<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\TugasPengumpulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TugasPengumpulanController extends Controller
{
    public function store(Request $request, $tugasId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,xlsx,zip|max:2048', // Maks 2MB
            'catatan' => 'nullable|string|max:1000',
        ]);

        $pegawai = Auth::user()->pegawai;
        $tugas = Tugas::findOrFail($tugasId);

        // --- 1. OTORISASI: Pastikan hanya penerima tugas yang bisa submit ---
        if ($tugas->penerima_id !== $pegawai->id) {
            abort(403, 'Anda tidak berhak mengumpulkan tugas ini.');
        }

        try {
            // --- 2. KONSISTENSI DATA: Gunakan transaksi database ---
            DB::transaction(function () use ($request, $tugas, $pegawai) {
                
                $file = $request->file('file');
                
                // --- 3. KEAMANAN FILE: Simpan dengan nama unik ---
                // 'tugas_pengumpulan' adalah nama folder di dalam storage/app/public
                $filePath = $file->store('tugas_pengumpulan', 'public');

                // Buat atau perbarui data pengumpulan. updateOrCreate lebih fleksibel.
                TugasPengumpulan::updateOrCreate(
                    [
                        'tugas_id' => $tugas->id,
                        'pegawai_id' => $pegawai->id,
                    ],
                    [
                        'file' => $filePath,
                        'catatan' => $request->catatan,
                        'status' => 'pending', // Set status selalu pending saat submit baru
                    ]
                );

                // Update status tugas menjadi 'Ditinjau'
                $tugas->status = 'Ditinjau';
                $tugas->save();
            });

            return redirect()->route('pegawai.tugas.show', $tugas->id)
                ->with('success', 'Tugas berhasil dikumpulkan dan sedang menunggu review.');

        } catch (\Exception $e) {
            // Jika terjadi error, catat di log dan beri tahu pengguna
            Log::error("Gagal mengumpulkan tugas ID {$tugas->id}: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengumpulkan tugas. Silakan coba lagi.');
        }
    }
}
