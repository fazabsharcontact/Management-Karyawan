<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\MasterTunjangan;
use App\Models\MasterPotongan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class GajiController extends Controller
{
    /**
     * Menampilkan daftar semua data gaji.
     */
    public function index(Request $request)
    {
        // Ambil semua input filter
        $search = $request->get('search');
        $jabatanFilter = $request->get('jabatan');
        $bulanFilter = $request->get('bulan');
        $tahunFilter = $request->get('tahun');

        $query = Gaji::with(['pegawai.jabatan'])->latest();

        // Terapkan filter pencarian nama
        $query->when($search, function ($q) use ($search) {
            $q->whereHas('pegawai', function ($subQuery) use ($search) {
                $subQuery->where('nama', 'like', "%{$search}%");
            });
        });

        // Terapkan filter jabatan
        $query->when($jabatanFilter, function ($q) use ($jabatanFilter) {
            $q->whereHas('pegawai.jabatan', function ($subQuery) use ($jabatanFilter) {
                $subQuery->where('nama_jabatan', $jabatanFilter);
            });
        });

        // --- PERBAIKAN: Tambahkan filter bulan dan tahun ---
        $query->when($bulanFilter, function ($q) use ($bulanFilter) {
            $q->where('bulan', $bulanFilter);
        });

        $query->when($tahunFilter, function ($q) use ($tahunFilter) {
            $q->where('tahun', $tahunFilter);
        });

        $gaji = $query->paginate(10);
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        
        $pegawaiBelumGajian = collect();
        if (Carbon::now()->day > 1) {
            $bulanIni = Carbon::now()->month;
            $tahunIni = Carbon::now()->year;

            $pegawaiBelumGajian = Pegawai::where('tanggal_masuk', '<=', Carbon::now())
                ->whereDoesntHave('gajis', function ($query) use ($bulanIni, $tahunIni) {
                    $query->where('bulan', $bulanIni)->where('tahun', $tahunIni);
                })->with('jabatan')->orderBy('nama')->get();
        }
        
        return view('admin.gaji.index', compact('gaji', 'jabatan', 'pegawaiBelumGajian'));
    }

    // ... (method lainnya tetap sama)
    public function create()
    {
        $pegawais = Pegawai::orderBy('nama')->get();
        $masterTunjangans = MasterTunjangan::orderBy('nama_tunjangan')->get();
        $masterPotongans = MasterPotongan::orderBy('nama_potongan')->get();

        return view('admin.gaji.create', compact('pegawais', 'masterTunjangans', 'masterPotongans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangans' => 'nullable|array',
            'tunjangans.*.master_tunjangan_id' => 'required_with:tunjangans|exists:master_tunjangans,id',
            'tunjangans.*.jumlah' => 'required_with:tunjangans|numeric|min:0',
            'potongans' => 'nullable|array',
            'potongans.*.master_potongan_id' => 'required_with:potongans|exists:master_potongans,id',
            'potongans.*.jumlah' => 'required_with:potongans|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalTunjangan = collect($request->tunjangans)->sum('jumlah');
            $totalPotongan = collect($request->potongans)->sum('jumlah');
            $gajiBersih = $request->gaji_pokok + $totalTunjangan - $totalPotongan;

            $gaji = Gaji::create([
                'pegawai_id' => $request->pegawai_id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'gaji_pokok' => $request->gaji_pokok,
                'total_tunjangan' => $totalTunjangan,
                'total_potongan' => $totalPotongan,
                'gaji_bersih' => $gajiBersih,
            ]);

            if ($request->has('tunjangans')) {
                foreach ($request->tunjangans as $tunjangan) {
                    $gaji->tunjanganDetails()->create($tunjangan);
                }
            }

            if ($request->has('potongans')) {
                foreach ($request->potongans as $potongan) {
                    $gaji->potonganDetails()->create($potongan);
                }
            }
        });

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil ditambahkan.');
    }

    public function edit(Gaji $gaji)
    {
        $gaji->load(['tunjanganDetails', 'potonganDetails']);
        $pegawais = Pegawai::orderBy('nama')->get();
        $masterTunjangans = MasterTunjangan::orderBy('nama_tunjangan')->get();
        $masterPotongans = MasterPotongan::orderBy('nama_potongan')->get();
        
        return view('admin.gaji.edit', compact('gaji', 'pegawais', 'masterTunjangans', 'masterPotongans'));
    }

    public function update(Request $request, Gaji $gaji)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangans' => 'nullable|array',
            'tunjangans.*.master_tunjangan_id' => 'required_with:tunjangans|exists:master_tunjangans,id',
            'tunjangans.*.jumlah' => 'required_with:tunjangans|numeric|min:0',
            'potongans' => 'nullable|array',
            'potongans.*.master_potongan_id' => 'required_with:potongans|exists:master_potongans,id',
            'potongans.*.jumlah' => 'required_with:potongans|numeric|min:0',
        ]);
        
        DB::transaction(function () use ($request, $gaji) {
            $gaji->tunjanganDetails()->delete();
            $gaji->potonganDetails()->delete();

            $totalTunjangan = collect($request->tunjangans)->sum('jumlah');
            $totalPotongan = collect($request->potongans)->sum('jumlah');
            $gajiBersih = $request->gaji_pokok + $totalTunjangan - $totalPotongan;

            $gaji->update([
                'pegawai_id' => $request->pegawai_id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'gaji_pokok' => $request->gaji_pokok,
                'total_tunjangan' => $totalTunjangan,
                'total_potongan' => $totalPotongan,
                'gaji_bersih' => $gajiBersih,
            ]);

            if ($request->has('tunjangans')) {
                foreach ($request->tunjangans as $tunjangan) {
                    $gaji->tunjanganDetails()->create($tunjangan);
                }
            }

            if ($request->has('potongans')) {
                foreach ($request->potongans as $potongan) {
                    $gaji->potonganDetails()->create($potongan);
                }
            }
        });

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil diperbarui.');
    }

    public function destroy(Gaji $gaji)
    {
        $gaji->delete();
        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil dihapus.');
    }

    public function unduhSlipGaji(Gaji $gaji)
    {
        $gaji->load(['pegawai.jabatan', 'tunjanganDetails.masterTunjangan', 'potonganDetails.masterPotongan']);
        $data = ['gaji' => $gaji];
        $pdf = Pdf::loadView('admin.gaji.slip-gaji-pdf', $data);
        $namaFile = 'slip-gaji-' . $gaji->pegawai->nama . '-' . $gaji->bulan . '-' . $gaji->tahun . '.pdf';
        return $pdf->download($namaFile);
    }
}