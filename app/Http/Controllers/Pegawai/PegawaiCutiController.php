<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiCutiController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user()->pegawai; // ambil pegawai terkait user login

        $cutis = Cuti::where('pegawai_id', $pegawai->id)
            ->latest()
            ->get();

        return view('pegawai.cuti.index', compact('cutis'));
    }

    public function create()
    {
        return view('pegawai.cuti.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'nullable|string',
        ]);

        $pegawai = Auth::user()->pegawai;

        Cuti::create([
            'pegawai_id'      => $pegawai->id, // pakai ID dari tabel pegawais
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan'      => $request->keterangan,
            'status'          => 'Diajukan',
        ]);

        return redirect()->route('pegawai.cuti.index')
            ->with('success', 'Pengajuan cuti berhasil diajukan.');
    }
}
