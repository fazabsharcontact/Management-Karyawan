<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTunjangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterTunjanganController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.tunjangan-potongan.index');
    }

    public function create()
    {
        return view('admin.tunjangan.master-tunjangan.create');
    }

    /**
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tunjangan' => 'required|string|max:100|unique:master_tunjangans,nama_tunjangan',
            'jumlah_default' => 'nullable|numeric|min:0', 
            'deskripsi' => 'nullable|string',
        ]);

        MasterTunjangan::create($validated);

        return redirect()->route('admin.tunjangan-potongan.index')
            ->with('success', 'Jenis tunjangan berhasil ditambahkan.');
    }

    /**
     */
    public function edit(MasterTunjangan $masterTunjangan)
    {
        return view('admin.tunjangan.master-tunjangan.edit', compact('masterTunjangan'));
    }

    /**
     */
    public function update(Request $request, MasterTunjangan $masterTunjangan)
    {
        $validated = $request->validate([
            'nama_tunjangan' => ['required', 'string', 'max:100', Rule::unique('master_tunjangans')->ignore($masterTunjangan->id)],
            'jumlah_default' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $masterTunjangan->update($validated);

        return redirect()->route('admin.tunjangan-potongan.index')
            ->with('success', 'Jenis tunjangan berhasil diperbarui.');
    }

    public function destroy(MasterTunjangan $masterTunjangan)
    {
        $masterTunjangan->delete();
        return redirect()->route('admin.tunjangan-potongan.index')
            ->with('success', 'Jenis tunjangan berhasil dihapus.');
    }
}

