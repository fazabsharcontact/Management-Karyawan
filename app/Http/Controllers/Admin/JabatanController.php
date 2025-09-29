<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JabatanController extends Controller
{
    /**
     * Menampilkan daftar semua data jabatan dengan filter.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jabatanFilter = $request->get('jabatan');

        $query = Jabatan::query();

        $query->when($search, function ($q) use ($search) {
            $q->where('nama_jabatan', 'like', "%{$search}%");
        });

        $query->when($jabatanFilter, function ($q) use ($jabatanFilter) {
            $q->where('nama_jabatan', $jabatanFilter);
        });

        // PERBAIKAN: Gunakan nama variabel yang konsisten: 'jabatans'
        $jabatans = $query->latest()->get();

        return view('admin.jabatan.index', compact('jabatans'));
    }

    /**
     * Menampilkan form untuk membuat jabatan baru.
     */
    public function create()
    {
        return view('admin.jabatan.create');
    }

    /**
     * Menyimpan jabatan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // PERBAIKAN: Nama tabel di 'unique' harus 'jabatans' (plural)
            'nama_jabatan' => 'required|unique:jabatans,nama_jabatan',
            'tunjangan' => 'required|numeric|min:0',
            'gaji_awal' => 'required|numeric|min:0', // Menambahkan validasi untuk gaji_awal
        ]);

        Jabatan::create($validated);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit jabatan.
     * Menggunakan Route Model Binding untuk kode yang lebih bersih.
     */
    public function edit(Jabatan $jabatan)
    {
        return view('admin.jabatan.edit', compact('jabatan'));
    }

    /**
     * Memperbarui data jabatan di database.
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            // PERBAIKAN: Aturan 'unique' yang lebih modern dan aman
            'nama_jabatan' => ['required', Rule::unique('jabatans')->ignore($jabatan->id)],
            'tunjangan' => 'required|numeric|min:0',
            'gaji_awal' => 'required|numeric|min:0',
        ]);

        $jabatan->update($validated);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Menghapus data jabatan dari database.
     */
    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}
