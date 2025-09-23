<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jabatanFilter = $request->get('jabatan');

        $query = Jabatan::query();

        if ($search) {
            $query->where('nama_jabatan', 'like', "%$search%");
        }

        if ($jabatanFilter) {
            $query->where('nama_jabatan', $jabatanFilter);
        }

        $jabatan = $query->get();
        $jabatanList = Jabatan::pluck('nama_jabatan');

        return view('admin.jabatan.index', compact('jabatan', 'jabatanList'));
    }

    public function create()
    {
        return view('admin.jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|unique:jabatan,nama_jabatan',
            'tunjangan' => 'required|numeric',
            'gaji_awal' => 'required|numeric',
        ]);

        Jabatan::create($request->all());

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('admin.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $request->validate([
            'nama_jabatan' => 'required|unique:jabatan,nama_jabatan,' . $jabatan->id_jabatan . ',id_jabatan',
            'tunjangan' => 'required|numeric',
            'gaji_awal' => 'required|numeric',
        ]);

        $jabatan->update($request->all());

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus');
    }
}