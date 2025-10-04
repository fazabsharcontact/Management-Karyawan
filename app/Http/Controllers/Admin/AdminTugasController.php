<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Pegawai;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminTugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with(['pemberi.user', 'penerima.user'])->latest()->get();
        $pegawai = Pegawai::with('user')->orderBy('nama')->get();
        return view('admin.tugas.index', compact('tugas', 'pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_tugas'   => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'penerima_id'   => 'required|exists:pegawais,id',
            'tenggat_waktu' => 'required|date',
        ]);

        $pemberi = Auth::user()->pegawai;

        // Pengecekan penting: Pastikan admin yang login juga memiliki data di tabel pegawai.
        if (!$pemberi) {
            // Pastikan view Anda bisa menampilkan pesan 'error'
            return back()->with('error', 'Gagal membuat tugas: Akun Admin tidak terhubung dengan data pegawai.');
        }

        try {
            Tugas::create([
                'judul_tugas'   => $request->judul_tugas,
                'deskripsi'     => $request->deskripsi,
                'pemberi_id'    => $pemberi->id,
                'penerima_id'   => $request->penerima_id,
                'tenggat_waktu' => $request->tenggat_waktu,
                'status'        => 'Baru',
            ]);

            return redirect()->route('admin.tugas.index')->with('success', 'Tugas berhasil ditambahkan.');

        } catch (QueryException $e) {
            // Log error untuk developer di file laravel.log
            Log::error("Error saat membuat tugas (QueryException): " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan pada database. Silakan coba lagi.');
        
        } catch (\Exception $e) {
            // Menangkap error umum lainnya
            Log::error("Error umum saat membuat tugas: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan tidak terduga. Silakan coba lagi.');
        }
    }
}

