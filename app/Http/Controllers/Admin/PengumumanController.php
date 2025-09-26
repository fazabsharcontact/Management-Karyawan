<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Pengumuman;
use App\Models\Tim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengumumanController extends Controller
{
    /**
     * Menampilkan daftar semua pengumuman.
     */
    public function index()
    {
        $pengumumans = Pengumuman::with('pembuat')->latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    /**
     * Menampilkan form untuk membuat pengumuman baru.
     */
    public function create()
    {
        // Ambil semua data yang mungkin menjadi target
        $pegawais = Pegawai::orderBy('nama')->get();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $tims = Tim::orderBy('nama_tim')->get();
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('admin.pengumuman.create', compact('pegawais', 'jabatans', 'tims', 'divisis'));
    }

    /**
     * Menyimpan pengumuman baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_type' => 'required|in:semua,divisi,tim,jabatan,pegawai',
            'target_ids' => 'nullable|array', // Target_ids wajib ada jika tipe bukan 'semua'
            'target_ids.*' => 'integer', // Pastikan semua isinya integer
        ], [
            'target_ids.required_if' => 'Pilihan target harus diisi.'
        ]);

        // Gunakan transaksi untuk memastikan semua data tersimpan
        DB::transaction(function () use ($request) {
            // 1. Buat pengumuman utama
            $pengumuman = Pengumuman::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'user_id' => Auth::id(), // ID admin yang sedang login
            ]);

            // 2. Simpan data penerima
            if ($request->target_type === 'semua') {
                $pengumuman->penerimas()->create([
                    'target_type' => 'semua',
                    'target_id' => null,
                ]);
            } else {
                if (!empty($request->target_ids)) {
                    foreach ($request->target_ids as $targetId) {
                        $pengumuman->penerimas()->create([
                            'target_type' => $request->target_type,
                            'target_id' => $targetId,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    /**
     * Menghapus pengumuman.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}