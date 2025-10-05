<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\TugasPengumpulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasPengumpulanController extends Controller
{
    public function store(Request $request, $tugasId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,xlsx,zip|max:2048',
            'catatan' => 'nullable|string',
        ]);

        $pegawai = Auth::user()->pegawai;

        // Ambil objek file
        $file = $request->file('file');

        // **PERUBAHAN 1: Ambil nama file asli**
        $originalFilename = $file->getClientOriginalName();

        // Tentukan folder penyimpanan
        $storagePath = 'tugas_pengumpulan';

        // **PERUBAHAN 2: Upload file menggunakan storeAs untuk mempertahankan nama asli**
        // CATATAN: Ini akan MENIMPA file yang sudah ada dengan nama yang sama di folder ini.
        $filePath = $file->storeAs($storagePath, $originalFilename, 'public');

        // Simpan data pengumpulan
        TugasPengumpulan::create([
            'tugas_id' => $tugasId,
            'pegawai_id' => $pegawai->id,
            'file' => $storagePath . '/' . $originalFilename, // Pastikan format path sesuai
            'catatan' => $request->catatan,
            'status' => 'pending',
        ]);

        // Update status tugas langsung ke Ditinjau
        $tugas = Tugas::findOrFail($tugasId);
        $tugas->status = 'Ditinjau';
        $tugas->save();

        return redirect()->route('pegawai.tugas.show', $tugasId)
            ->with('success', 'Tugas berhasil dikumpulkan, menunggu review.');
    }
}