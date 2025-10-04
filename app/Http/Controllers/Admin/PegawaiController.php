<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\User;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class PegawaiController extends Controller
{
    // Method index, create, dan edit tidak perlu diubah

    public function index(Request $request)
    {
        $jabatanFilter = $request->get('jabatan');
        $search = $request->get('search');
        $query = Pegawai::with('jabatan', 'user', 'tim.divisi');
        $query->when($jabatanFilter, fn($q) => $q->whereHas('jabatan', fn($sq) => $sq->where('nama_jabatan', $jabatanFilter)));
        $query->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%"));
        $pegawais = $query->latest()->paginate(10)->withQueryString();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        return view('admin.pegawai.index', compact('pegawais', 'jabatans'));
    }

    public function create()
    {
        $pegawai = new Pegawai(); // Kirim model kosong ke view
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        $divisis = Divisi::with('tims')->get();
        return view('admin.pegawai.create', compact('pegawai', 'jabatan', 'divisis'));
    }
    
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
            'gaji' => ['required', 'numeric', 'min:0'],
            
            // --- VALIDASI TAMBAHAN ---
            'nama_bank' => ['nullable', 'string', 'max:50'],
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pegawai',
            ]);

            $user->pegawai()->create([
                'jabatan_id' => $validated['jabatan'],
                'tim_id' => $validated['tim_id'] ?? null,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_hp' => $validated['telepon'],
                'alamat' => $validated['alamat'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'gaji_pokok' => $validated['gaji'],
                
                // --- PENYIMPANAN DATA BARU ---
                'nama_bank' => $validated['nama_bank'],
                'nomor_rekening' => $validated['nomor_rekening'],
            ]);
        });

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        $divisis = Divisi::with('tims')->get();
        $pegawai->load('user');
        return view('admin.pegawai.edit', compact('pegawai', 'jabatan', 'divisis'));
    }

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
            'gaji' => ['required', 'numeric', 'min:0'],

            // --- VALIDASI TAMBAHAN ---
            'nama_bank' => ['nullable', 'string', 'max:50'],
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
        ]);
        
        DB::transaction(function () use ($validated, $request, $pegawai, $user) {
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

                // --- PENYIMPANAN DATA BARU ---
                'nama_bank' => $validated['nama_bank'],
                'nomor_rekening' => $validated['nomor_rekening'],
            ]);
        });

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        DB::transaction(function () use ($pegawai) {
            $pegawai->user()->delete();
        });
        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}