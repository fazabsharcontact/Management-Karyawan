<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\User;
use App\Models\Divisi;
use App\Models\SisaCuti; // <-- 1. Import model SisaCuti
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- 2. Import DB facade untuk transaksi
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class PegawaiController extends Controller
{
    /**
     * Menampilkan daftar semua pegawai.
     */
    public function index(Request $request)
    {
        $jabatanFilter = $request->get('jabatan');
        $search = $request->get('search');

        $query = Pegawai::with('jabatan', 'user', 'tim.divisi');

        $query->when($jabatanFilter, function ($q) use ($jabatanFilter) {
            $q->whereHas('jabatan', function ($subQuery) use ($jabatanFilter) {
                $subQuery->where('nama_jabatan', $jabatanFilter);
            });
        });

        $query->when($search, function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%");
        });

        $pegawai = $query->latest()->get();
        $jabatan = Jabatan::all();

        return view('admin.pegawai.index', compact('pegawai', 'jabatan'));
    }

    /**
     * Menampilkan form untuk membuat pegawai baru.
     */
    public function create()
    {
        $jabatan = Jabatan::all();
        $divisis = Divisi::with('tims')->get();
        return view('admin.pegawai.create', compact('jabatan', 'divisis'));
    }

    
    /**
     * Menyimpan pegawai baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'jabatan' => ['required', 'exists:jabatans,id'],
            'tim_id' => ['nullable', 'exists:tims,id'], 
            'tanggal_masuk' => ['required', 'date'],
            'gaji' => ['required', 'numeric'],
        ]);

        // --- 3. Gunakan Transaksi Database ---
        // Ini memastikan jika salah satu proses gagal, semua akan dibatalkan.
        DB::transaction(function () use ($validated, $request) {
            // Buat data User
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pegawai',
            ]);

            // Buat data Pegawai yang berelasi
            $pegawai = $user->pegawai()->create([
                'jabatan_id' => $validated['jabatan'],
                'tim_id' => $validated['tim_id'] ?? null,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_hp' => $validated['telepon'],
                'alamat' => $validated['alamat'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'gaji_pokok' => $validated['gaji'],
            ]);

            // --- PERBAIKAN UTAMA: Buat data sisa_cuti secara otomatis ---
            $pegawai->sisaCuti()->create([
                'sisa_cuti' => 12, // Nilai default jatah cuti tahunan
            ]);
        });

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data pegawai.
     */
    public function edit(Pegawai $pegawai)
    {
        $jabatan = Jabatan::all();
        $divisis = Divisi::with('tims')->get();
        $pegawai->load('user');
        return view('admin.pegawai.edit', compact('pegawai', 'jabatan', 'divisis'));
    }

    /**
     * Memperbarui data pegawai di database.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $user = $pegawai->user;

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nama' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'jabatan' => ['required', 'exists:jabatans,id'],
            'tim_id' => ['nullable', 'exists:tims,id'],
            'tanggal_masuk' => ['required', 'date'],
            'gaji' => ['required', 'numeric'],
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);
        
        if ($request->filled('password')) {
            $request->validate(['password' => ['required', 'confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $pegawai->update([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['telepon'],
            'alamat' => $validated['alamat'],
            'jabatan_id' => $validated['jabatan'],
            'tim_id' => $validated['tim_id'] ?? null, 
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'gaji_pokok' => $validated['gaji'],
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->user()->delete();
        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}