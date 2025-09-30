<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// PERBAIKAN: Nama class diubah agar sesuai dengan nama file
class PegawaiCutiController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user()->pegawai->load('sisaCuti');
        $cutis = Cuti::where('pegawai_id', $pegawai->id)
            ->latest()
            ->paginate(10);
            
        return view('pegawai.cuti.index', compact('cutis', 'pegawai'));
    }

    public function create()
    {
        $pegawai = Auth::user()->pegawai->load('sisaCuti');
        return view('pegawai.cuti.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
        ]);

        $pegawai = Auth::user()->pegawai;
        $sisaCuti = $pegawai->sisaCuti->sisa_cuti ?? 0;
        
        $cutiSementara = new Cuti($request->only(['tanggal_mulai', 'tanggal_selesai']));
        $durasiCuti = $cutiSementara->durasi_hari_kerja;

        if ($sisaCuti < $durasiCuti) {
            return back()->with('error', 'Sisa cuti Anda tidak mencukupi.');
        }

        Cuti::create([
            'pegawai_id' => $pegawai->id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'status' => 'Diajukan',
        ]);

        return redirect()->route('pegawai.cuti.index')->with('success', 'Pengajuan cuti berhasil dikirim.');
    }
}