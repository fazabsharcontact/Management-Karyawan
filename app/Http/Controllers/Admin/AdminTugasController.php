<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with(['pemberi.user', 'penerima.user'])->get();
        $pegawai = Pegawai::with('user')->get();

        return view('admin.tugas.index', compact('tugas', 'pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_tugas'   => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'penerima_id'   => 'required|exists:pegawai,id',
            'tenggat_waktu' => 'required|date',
        ]);

        $pemberiId = Auth::user()->pegawai->id ?? null;

        if (!$pemberiId) {
            return back()->withErrors('Akun ini belum terhubung dengan data pegawai.');
        }

        Tugas::create([
            'judul_tugas'   => $request->judul_tugas,
            'deskripsi'     => $request->deskripsi,
            'pemberi_id'    => $pemberiId,
            'penerima_id'   => $request->penerima_id,
            'tenggat_waktu' => $request->tenggat_waktu,
            'status'        => 'Baru', // default
        ]);

        return redirect()->route('admin.tugas.index')->with('success', 'Tugas berhasil ditambahkan.');
    }
}
