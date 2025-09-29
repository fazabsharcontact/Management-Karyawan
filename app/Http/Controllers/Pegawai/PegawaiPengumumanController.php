<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengumumanPenerima;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Pegawai;


class PegawaiPengumumanController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user()->pegawai; // relasi user -> pegawai

        $pengumumans = PengumumanPenerima::with('pengumuman')
            ->where(function ($q) use ($pegawai) {
                $q->where(function ($q2) use ($pegawai) {
                    $q2->where('target_type', 'pegawai')
                        ->where('target_id', $pegawai->id);
                })
                    ->orWhere(function ($q2) use ($pegawai) {
                        $q2->where('target_type', 'jabatan')
                            ->where('target_id', $pegawai->jabatan_id);
                    })
                    ->orWhere(function ($q2) use ($pegawai) {
                        $q2->where('target_type', 'divisi')
                            ->where('target_id', $pegawai->divisi_id);
                    })
                    ->orWhere(function ($q2) use ($pegawai) {
                        $q2->where('target_type', 'tim')
                            ->where('target_id', $pegawai->tim_id);
                    })
                    ->orWhere('target_type', 'semua'); // semua pegawai
            })
            ->orderByDesc('created_at')
            ->get();

        return view('pegawai.pengumuman.index', compact('pengumumans'));
    }
}
