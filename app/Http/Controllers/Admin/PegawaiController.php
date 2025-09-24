<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PegawaiController extends Controller
{
    /**
     * Menampilkan daftar semua pegawai dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        $jabatanFilter = $request->get('jabatan');
        $search = $request->get('search');

        // Query menggunakan Eloquent dengan eager loading untuk efisiensi
        $query = Pegawai::with('jabatan', 'user');

        // --- FILTER DIPERBAIKI ---
        // Logika diubah untuk memfilter berdasarkan nama jabatan, sesuai data yang dikirim view
        $query->when($jabatanFilter, function ($q) use ($jabatanFilter) {
            $q->whereHas('jabatan', function ($subQuery) use ($jabatanFilter) {
                $subQuery->where('nama_jabatan', $jabatanFilter);
            });
        });

        $query->when($search, function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%");
        });

        // --- NAMA VARIABEL DIUBAH ---
        $pegawai = $query->latest()->get();
        $jabatan = Jabatan::all();

        return view('admin.pegawai.index', compact('pegawai', 'jabatan'));
    }

    /**
     * Menampilkan form untuk membuat pegawai baru.
     */
    public function create()
    {
        // --- NAMA VARIABEL DIUBAH ---
        $jabatan = Jabatan::all();
        return view('admin.pegawai.create', compact('jabatan'));
    }

    /**
     * Menyimpan pegawai baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'tanggal_masuk' => ['required', 'date'],
            'gaji_pokok' => ['required', 'numeric'],
        ]);

        // 1. Buat data User
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pegawai',
        ]);

        // 2. Buat data Pegawai yang berelasi dengan User
        $user->pegawai()->create([
            'jabatan_id' => $request->jabatan_id,
            'nama' => $request->nama,
            'email' => $request->email, // Simpan juga di pegawai untuk akses mudah
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal_masuk' => $request->tanggal_masuk,
            'gaji_pokok' => $request->gaji_pokok,
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }


    /**
     * Menampilkan form untuk mengedit data pegawai.
     */
    public function edit(Pegawai $pegawai)
    {
        // --- NAMA VARIABEL DIUBAH ---
        $jabatan = Jabatan::all();
        // Load relasi user agar bisa diakses di form
        $pegawai->load('user');
        return view('admin.pegawai.edit', compact('pegawai', 'jabatan'));
    }

    /**
     * Memperbarui data pegawai di database.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $user = $pegawai->user;

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'tanggal_masuk' => ['required', 'date'],
            'gaji_pokok' => ['required', 'numeric'],
        ]);

        // 1. Update data User
        $user->update([
            'username' => $request->username,
            'email' => $request->email,
        ]);
        
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // 2. Update data Pegawai
        $pegawai->update($request->except(['username', 'email', 'password']));

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Menghapus data pegawai.
     */
    public function destroy(Pegawai $pegawai)
    {
        // Hapus data User yang berelasi.
        // Karena di migrasi Pegawai ada onDelete('cascade') pada user_id,
        // data pegawai akan ikut terhapus otomatis saat user-nya dihapus.
        $pegawai->user()->delete();

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}

