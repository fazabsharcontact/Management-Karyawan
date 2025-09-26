<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\MasterTunjangan;
use App\Models\MasterPotongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GajiController extends Controller
{
    /**
     * Menampilkan daftar semua data gaji.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $jabatanFilter = $request->get('jabatan');

        $query = Gaji::with(['pegawai.jabatan'])->latest();

        $query->when($search, function ($q) use ($search) {
            $q->whereHas('pegawai', function ($subQuery) use ($search) {
                $subQuery->where('nama', 'like', "%{$search}%");
            });
        });

        $query->when($jabatanFilter, function ($q) use ($jabatanFilter) {
            $q->whereHas('pegawai.jabatan', function ($subQuery) use ($jabatanFilter) {
                $subQuery->where('nama_jabatan', $jabatanFilter);
            });
        });

        $gaji = $query->paginate(10); // Menggunakan paginasi
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();

        return view('admin.gaji.index', compact('gaji', 'jabatan'));
    }

    /**
     * Menampilkan form untuk membuat data gaji baru.
     */
    public function create()
    {
        $pegawais = Pegawai::orderBy('nama')->get();
        $masterTunjangans = MasterTunjangan::orderBy('nama_tunjangan')->get();
        $masterPotongans = MasterPotongan::orderBy('nama_potongan')->get();

        return view('admin.gaji.create', compact('pegawais', 'masterTunjangans', 'masterPotongans'));
    }

    /**
     * Menyimpan data gaji baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'gaji_pokok' => 'required|numeric|min:0',
            // Validasi untuk array
            'tunjangans' => 'nullable|array',
            'tunjangans.*.master_tunjangan_id' => 'required|exists:master_tunjangans,id',
            'tunjangans.*.jumlah' => 'required|numeric|min:0',
            'tunjangans.*.keterangan' => 'nullable|string',
            'potongans' => 'nullable|array',
            'potongans.*.master_potongan_id' => 'required|exists:master_potongans,id',
            'potongans.*.jumlah' => 'required|numeric|min:0',
            'potongans.*.keterangan' => 'nullable|string',
        ]);

        // Memulai transaksi database
        DB::transaction(function () use ($validated, $request) {
            $totalTunjangan = collect($request->tunjangans)->sum('jumlah');
            $totalPotongan = collect($request->potongans)->sum('jumlah');
            $gajiBersih = $validated['gaji_pokok'] + $totalTunjangan - $totalPotongan;

            // 1. Buat record Gaji utama
            $gaji = Gaji::create([
                'pegawai_id' => $validated['pegawai_id'],
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'gaji_pokok' => $validated['gaji_pokok'],
                'total_tunjangan' => $totalTunjangan,
                'total_potongan' => $totalPotongan,
                'gaji_bersih' => $gajiBersih,
            ]);

            // 2. Simpan detail tunjangan jika ada
            if ($request->has('tunjangans')) {
                foreach ($request->tunjangans as $tunjangan) {
                    $gaji->tunjanganDetails()->create($tunjangan);
                }
            }

            // 3. Simpan detail potongan jika ada
            if ($request->has('potongans')) {
                foreach ($request->potongans as $potongan) {
                    $gaji->potonganDetails()->create($potongan);
                }
            }
        });

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data gaji.
     */
    public function edit(Gaji $gaji)
    {
        $gaji->load(['tunjanganDetails', 'potonganDetails']);
        $pegawais = Pegawai::orderBy('nama')->get();
        $masterTunjangans = MasterTunjangan::orderBy('nama_tunjangan')->get();
        $masterPotongans = MasterPotongan::orderBy('nama_potongan')->get();
        
        return view('admin.gaji.edit', compact('gaji', 'pegawais', 'masterTunjangans', 'masterPotongans'));
    }

    /**
     * Memperbarui data gaji di database.
     */
    public function update(Request $request, Gaji $gaji)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangans' => 'nullable|array',
            'tunjangans.*.master_tunjangan_id' => 'required|exists:master_tunjangans,id',
            'tunjangans.*.jumlah' => 'required|numeric|min:0',
            'tunjangans.*.keterangan' => 'nullable|string',
            'potongans' => 'nullable|array',
            'potongans.*.master_potongan_id' => 'required|exists:master_potongans,id',
            'potongans.*.jumlah' => 'required|numeric|min:0',
            'potongans.*.keterangan' => 'nullable|string',
        ]);
        
        DB::transaction(function () use ($validated, $request, $gaji) {
            // Hapus detail lama
            $gaji->tunjanganDetails()->delete();
            $gaji->potonganDetails()->delete();

            $totalTunjangan = collect($request->tunjangans)->sum('jumlah');
            $totalPotongan = collect($request->potongans)->sum('jumlah');
            $gajiBersih = $validated['gaji_pokok'] + $totalTunjangan - $totalPotongan;

            // 1. Update record Gaji utama
            $gaji->update([
                'pegawai_id' => $validated['pegawai_id'],
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'gaji_pokok' => $validated['gaji_pokok'],
                'total_tunjangan' => $totalTunjangan,
                'total_potongan' => $totalPotongan,
                'gaji_bersih' => $gajiBersih,
            ]);

            // 2. Simpan kembali detail tunjangan yang baru
            if ($request->has('tunjangans')) {
                foreach ($request->tunjangans as $tunjangan) {
                    $gaji->tunjanganDetails()->create($tunjangan);
                }
            }

            // 3. Simpan kembali detail potongan yang baru
            if ($request->has('potongans')) {
                foreach ($request->potongans as $potongan) {
                    $gaji->potonganDetails()->create($potongan);
                }
            }
        });

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil diperbarui.');
    }

    /**
     * Menghapus data gaji dari database.
     */
    public function destroy(Gaji $gaji)
    {
        $gaji->delete();
        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil dihapus.');
    }
}

