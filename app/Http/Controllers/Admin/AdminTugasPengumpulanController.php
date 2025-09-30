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
        $request->validate([
            'status' => 'required|in:diterima,revisi',
        ]);

        $pengumpulan = TugasPengumpulan::findOrFail($id);
        $pengumpulan->status = $request->status;
        $pengumpulan->save();

        // update status tugas juga
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
