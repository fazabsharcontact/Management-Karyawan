<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jabatanFilter = $request->get('jabatan');

        $query = Gaji::with(['pegawai.jabatan']);

        if ($search) {
            $query->whereHas('pegawai', function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            });
        }

        if ($jabatanFilter) {
            $query->whereHas('pegawai.jabatan', function ($q) use ($jabatanFilter) {
                $q->where('nama_jabatan', $jabatanFilter);
            });
        }

        $gaji = $query->get();
        $jabatan = Jabatan::all();

        return view('admin.gaji.index', compact('gaji', 'jabatan'));
    }

    public function create()
    {
        $pegawai = Pegawai::with('jabatan')->get();

        $bulan_nama = [
            1=>"Januari", 2=>"Februari", 3=>"Maret", 4=>"April", 5=>"Mei", 6=>"Juni",
            7=>"Juli", 8=>"Agustus", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Desember"
        ];

        return view('admin.gaji.create', compact('pegawai', 'bulan_nama'));
    }

    public function edit($id)
    {
        $gaji = Gaji::findOrFail($id);
        $pegawai = Pegawai::with('jabatan')->get();

        $bulan_nama = [
            1=>"Januari", 2=>"Februari", 3=>"Maret", 4=>"April", 5=>"Mei", 6=>"Juni",
            7=>"Juli", 8=>"Agustus", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Desember"
        ];

        return view('admin.gaji.edit', compact('gaji', 'pegawai', 'bulan_nama'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
            'total_gaji' => 'required|numeric',
        ]);

        Gaji::create($request->all());

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
            'total_gaji' => 'required|numeric',
        ]);

        $gaji = Gaji::findOrFail($id);
        $gaji->update($request->all());

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil diperbarui');
    }

    public function destroy($id)
    {
        $gaji = Gaji::findOrFail($id);
        $gaji->delete();

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil dihapus');
    }
}