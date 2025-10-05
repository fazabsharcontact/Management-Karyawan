<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Tim;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class AdminTugasController extends Controller
{
    public function index(Request $request)
    {
        $query = Tugas::with(['pemberi', 'penerima.jabatan', 'penerima.tim.divisi'])->latest();

        // Logika Pencarian
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($subquery) use ($search) {
                $subquery->where('judul_tugas', 'like', "%{$search}%")
                         ->orWhereHas('penerima', fn($sq) => $sq->where('nama', 'like', "%{$search}%"));
            });
        });

        // Logika Filter
        $query->when($request->filled('bulan'), fn($q) => $q->whereMonth('created_at', $request->bulan));
        $query->when($request->filled('tahun'), fn($q) => $q->whereYear('created_at', $request->tahun));
        $query->when($request->filled('jabatan_id'), fn($q) => $q->whereHas('penerima.jabatan', fn($sq) => $sq->where('id', 'like', $request->jabatan_id)));
        $query->when($request->filled('tim_id'), fn($q) => $q->whereHas('penerima', fn($sq) => $sq->where('tim_id', $request->tim_id)));
        $query->when($request->filled('divisi_id'), fn($q) => $q->whereHas('penerima.tim.divisi', fn($sq) => $sq->where('id', $request->divisi_id)));
        
        // --- TAMBAHAN BARU: Filter berdasarkan status ---
        $query->when($request->filled('status'), fn($q) => $q->where('status', $request->status));

        $tugas = $query->paginate(15)->withQueryString();
        
        $pegawais = Pegawai::orderBy('nama')->get();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $tims = Tim::orderBy('nama_tim')->get();
        $divisis = Divisi::orderBy('nama_divisi')->get();
        
        // --- TAMBAHAN BARU: Kirim daftar status ke view ---
        $statuses = ['Baru', 'Dikerjakan', 'Ditinjau', 'Selesai'];

        return view('admin.tugas.index', compact('tugas', 'pegawais', 'jabatans', 'tims', 'divisis', 'statuses'));
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

        if (!$pemberi) {
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
            Log::error("Error saat membuat tugas (QueryException): " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan pada database. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error("Error umum saat membuat tugas: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan tidak terduga. Silakan coba lagi.');
        }
    }
}