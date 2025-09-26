<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisiController extends Controller
{
    /**
     * Menampilkan form untuk membuat divisi baru.
     */
    public function create()
    {
        return view('admin.timdivisi.divisi-create');
    }

    /**
     * Menyimpan divisi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_divisi' => 'required|string|max:100|unique:divisis,nama_divisi',
        ]);

        Divisi::create($validated);

        return redirect()->route('admin.tim-divisi.index')
            ->with('success', 'Divisi baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit divisi.
     */
    public function edit(Divisi $divisi)
    {
        return view('admin.timdivisi.divisi-edit', compact('divisi'));
    }

    /**
     * Memperbarui data divisi di database.
     */
    public function update(Request $request, Divisi $divisi)
    {
        $validated = $request->validate([
            'nama_divisi' => ['required', 'string', 'max:100', Rule::unique('divisis')->ignore($divisi->id)],
        ]);

        $divisi->update($validated);

        return redirect()->route('admin.tim-divisi.index')
            ->with('success', 'Nama divisi berhasil diperbarui.');
    }

    /**
     * Menghapus data divisi dari database.
     */
    public function destroy(Divisi $divisi)
    {
        // Optional: Cek jika divisi masih memiliki tim
        if ($divisi->tims()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus divisi yang masih memiliki tim.');
        }

        $divisi->delete();

        return redirect()->route('admin.tim-divisi.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }
}
