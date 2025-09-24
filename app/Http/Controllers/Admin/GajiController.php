<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    /**
     * Menampilkan daftar semua data gaji.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jabatanFilter = $request->get('jabatan');

        $query = Gaji::with(['pegawai.jabatan'])->latest();

        $query->when($search, function ($q) use ($search) {
            $q->whereHas('pegawai', function ($subQuery) use ($search) {
                $subQuery->where('nama', 'like', "%{$search}%");
            });
        });

        $query->when($jabatanFilter, function ($q) use ($jabatanFilter) {
            $q->whereHas('pegawai.jabatan', function ($subQuery) use ($jabatanFilter) {
                $subQuery->where('nama_jabatan', $jabatanFilter);
            });
        });

        $gaji = $query->get();
        $jabatan = Jabatan::all();

        return view('admin.gaji.index', compact('gaji', 'jabatan'));
    }

    /**
     * Menampilkan form untuk membuat data gaji baru.
     */
    public function create()
    {
        $pegawais = Pegawai::orderBy('nama')->get();
        return view('admin.gaji.create', compact('pegawais'));
    }

    /**
     * Menyimpan data gaji baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'gaji_pokok' => 'required|numeric',
            'total_tunjangan' => 'required|numeric',
            'total_potongan' => 'required|numeric',
        ]);

        $gaji_bersih = $validated['gaji_pokok'] + $validated['total_tunjangan'] - $validated['total_potongan'];

        Gaji::create(array_merge($validated, ['gaji_bersih' => $gaji_bersih]));

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data gaji.
     */
    public function edit(Gaji $gaji) // Menggunakan Route Model Binding
    {
        $pegawais = Pegawai::orderBy('nama')->get();
        return view('admin.gaji.edit', compact('gaji', 'pegawais'));
    }

    /**
     * Memperbarui data gaji di database.
     */
    public function update(Request $request, Gaji $gaji) // Menggunakan Route Model Binding
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'gaji_pokok' => 'required|numeric',
            'total_tunjangan' => 'required|numeric',
            'total_potongan' => 'required|numeric',
        ]);

        $gaji_bersih = $validated['gaji_pokok'] + $validated['total_tunjangan'] - $validated['total_potongan'];

        $gaji->update(array_merge($validated, ['gaji_bersih' => $gaji_bersih]));

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil diperbarui.');
    }

    /**
     * Menghapus data gaji dari database.
     */
    public function destroy(Gaji $gaji) // Menggunakan Route Model Binding
    {
        $gaji->delete();
        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil dihapus.');
    }
}
