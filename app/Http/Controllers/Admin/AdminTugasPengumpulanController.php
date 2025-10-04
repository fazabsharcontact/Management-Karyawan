<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TugasPengumpulan;
use App\Models\Tugas;
use Illuminate\Http\Request;

class AdminTugasPengumpulanController extends Controller
{
    public function index()
    {
        $pengumpulan = TugasPengumpulan::with(['tugas', 'pegawai.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tugas_pengumpulan.index', compact('pengumpulan'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Tambahkan catatan sebagai opsional/nullable
        $request->validate([
            'status' => 'required|in:diterima,revisi',
            'catatan' => 'nullable|string|max:500', // Tambahkan validasi catatan
        ]);

        $pengumpulan = TugasPengumpulan::findOrFail($id);
        $pengumpulan->status = $request->status;

        // Tambahkan logika untuk menyimpan catatan hanya jika statusnya 'revisi'
        if ($request->status === 'revisi') {
            $pengumpulan->catatan = $request->catatan;
        } else {
            // Jika diterima, mungkin ingin mengosongkan catatan revisi sebelumnya
            $pengumpulan->catatan = null;
        }

        $pengumpulan->save();

        // ... (Logika update status tugas Anda yang sudah benar)
        $tugas = $pengumpulan->tugas;
        if ($request->status === 'diterima') {
            $tugas->status = 'Selesai';
        } elseif ($request->status === 'revisi') {
            $tugas->status = 'Dikerjakan';
        }
        $tugas->save();

        return redirect()->back()->with('success', 'Status pengumpulan diperbarui.');
    }
}
