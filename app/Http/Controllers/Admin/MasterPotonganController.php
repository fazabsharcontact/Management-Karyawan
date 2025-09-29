<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPotongan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterPotonganController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.tunjangan-potongan.index');
    }

    public function create()
    {
        // Mengarahkan ke view untuk membuat potongan baru
        return view('admin.tunjangan.master-potongan.create');
    }

    /**
     * Menyimpan potongan baru ke database.
     */
    public function store(Request $request)
    {
        // PERBAIKAN: Tambahkan validasi untuk 'jumlah_default'
        $validated = $request->validate([
            'nama_potongan' => 'required|string|max:100|unique:master_potongans,nama_potongan',
            'jumlah_default' => 'nullable|numeric|min:0', // Memastikan nilainya angka & tidak negatif
            'deskripsi' => 'nullable|string',
        ]);

        MasterPotongan::create($validated);

        return redirect()->route('admin.tunjangan-potongan.index')
            ->with('success', 'Jenis potongan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit potongan.
     */
    public function edit(MasterPotongan $masterPotongan)
    {
        // Mengarahkan ke view untuk mengedit potongan
        return view('admin.tunjangan.master-potongan.edit', compact('masterPotongan'));
    }

    /**
     * Memperbarui data potongan di database.
     */
    public function update(Request $request, MasterPotongan $masterPotongan)
    {
        // PERBAIKAN: Tambahkan validasi untuk 'jumlah_default'
        $validated = $request->validate([
            'nama_potongan' => ['required', 'string', 'max:100', Rule::unique('master_potongans')->ignore($masterPotongan->id)],
            'jumlah_default' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $masterPotongan->update($validated);

        return redirect()->route('admin.tunjangan-potongan.index')
            ->with('success', 'Jenis potongan berhasil diperbarui.');
    }

    /**
     * Menghapus data potongan dari database.
     */
    public function destroy(MasterPotongan $masterPotongan)
    {
        $masterPotongan->delete();
        return redirect()->route('admin.tunjangan-potongan.index')
            ->with('success', 'Jenis potongan berhasil dihapus.');
    }
}

