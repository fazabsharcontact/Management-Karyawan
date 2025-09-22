<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\Kehadiran;
use App\Models\Gaji;
use App\Models\User;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $jabatanFilter = $request->get('jabatan');
        $search = $request->get('search');

        $query = Pegawai::with('jabatan');

        if ($jabatanFilter) {
            $query->whereHas('jabatan', function ($q) use ($jabatanFilter) {
                $q->where('nama_jabatan', $jabatanFilter);
            });
        }

        if ($search) {
            $query->where('nama', 'like', "%$search%");
        }

        $pegawai = $query->get();
        $jabatan = Jabatan::all();

        return view('admin.pegawai.index', compact('pegawai', 'jabatan'));
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        // Hapus relasi
        Kehadiran::where('id_pegawai', $id)->delete();
        Gaji::where('id_pegawai', $id)->delete();

        $userId = $pegawai->id_users;

        $pegawai->delete();

        if ($userId) {
            User::where('id_users', $userId)->delete();
        }

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus');
    }

    public function create()
    {
        $jabatan = Jabatan::all();
        $users = User::where('role', 'pegawai')->get(); // ambil user untuk di-relasi
        return view('admin.pegawai.create', compact('jabatan', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'nama' => 'required',
            'email' => 'required|email',
            'telepon' => 'required|numeric',
            'alamat' => 'required',
            'jabatan' => 'required|exists:jabatan,id_jabatan',
            'tanggal_masuk' => 'required|date',
            'gaji' => 'required|numeric',
        ]);

        // Simpan user
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => 'pegawai',
        ]);

        // Simpan pegawai
        Pegawai::create([
            'id_users' => $user->id_users,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->telepon,
            'alamat' => $request->alamat,
            'id_jabatan' => $request->jabatan,
            'tanggal_masuk' => $request->tanggal_masuk,
            'gaji' => $request->gaji,
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $jabatan = Jabatan::all();
        return view('admin.pegawai.edit', compact('pegawai','jabatan'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $user = User::find($pegawai->id_users);

        $request->validate([
            'username' => 'required|unique:users,username,'.$user->id_users.',id_users',
            'nama' => 'required',
            'email' => 'required|email',
            'telepon' => 'required|numeric',
            'alamat' => 'required',
            'jabatan' => 'required|exists:jabatan,id_jabatan',
            'tanggal_masuk' => 'required|date',
            'gaji' => 'required|numeric',
        ]);

        // update user
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        // update pegawai
        $pegawai->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->telepon,
            'alamat' => $request->alamat,
            'id_jabatan' => $request->jabatan,
            'tanggal_masuk' => $request->tanggal_masuk,
            'gaji' => $request->gaji,
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui');
    }
}