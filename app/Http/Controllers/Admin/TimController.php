<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tim;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TimController extends Controller
{
    /**
     * Menampilkan form untuk membuat tim baru.
     */
    public function create()
    {
        $divisis = Divisi::orderBy('nama_divisi')->get();
        return view('admin.timdivisi.tim-create', compact('divisis'));
    }

    /**
     * Menyimpan tim baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tim' => 'required|string|max:100',
            'divisi_id' => 'required|exists:divisis,id',
        ]);

        Tim::create($validated);

        return redirect()->route('admin.tim-divisi.index')
            ->with('success', 'Tim baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit tim.
     */
    public function edit(Tim $tim)
    {
        $divisis = Divisi::orderBy('nama_divisi')->get();
        return view('admin.timdivisi.tim-edit', compact('tim', 'divisis'));
    }

    /**
     * Memperbarui data tim di database.
     */
    public function update(Request $request, Tim $tim)
    {
        $validated = $request->validate([
            'nama_tim' => 'required|string|max:100',
            'divisi_id' => 'required|exists:divisis,id',
        ]);

        $tim->update($validated);

        return redirect()->route('admin.tim-divisi.index')
            ->with('success', 'Data tim berhasil diperbarui.');
    }

    /**
     * Menghapus data tim dari database.
     */
    public function destroy(Tim $tim)
    {
        // Optional: Cek jika tim masih memiliki anggota
        if ($tim->pegawais()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus tim yang masih memiliki anggota.');
        }
        
        $tim->delete();

        return redirect()->route('admin.tim-divisi.index')
            ->with('success', 'Tim berhasil dihapus.');
    }
}

