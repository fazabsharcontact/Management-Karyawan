<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Gaji;
use App\Models\Jabatan;
use App\Models\MasterPotongan;
use App\Models\MasterTunjangan;
use App\Models\Pegawai;
use App\Models\Tim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GajiMassalController extends Controller
{
    /**
     * Langkah 1: Menampilkan form untuk memilih kriteria & pegawai.
     */
    public function langkahSatu(Request $request)
    {
        $pegawais = collect();
        $inputs = $request->all();

        if ($request->has('filter')) {
            $query = Pegawai::query()->with('jabatan');

            // --- PERBAIKAN: Tambahkan filter pencarian nama ---
            $query->when($request->filled('search'), function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
            
            $query->when($request->filled('divisi_id'), function ($q) use ($request) {
                $q->whereHas('tim.divisi', fn ($sq) => $sq->where('id', $request->divisi_id));
            });
            $query->when($request->filled('tim_id'), fn ($q) => $q->where('tim_id', $request->tim_id));
            $query->when($request->filled('jabatan_id'), fn ($q) => $q->where('jabatan_id', $request->jabatan_id));

            $pegawais = $query->orderBy('nama')->get();
        }
        
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $pegawaiBelumGajian = Pegawai::where('tanggal_masuk', '<=', Carbon::now())
            ->whereDoesntHave('gajis', function ($query) use ($bulanIni, $tahunIni) {
                $query->where('bulan', $bulanIni)->where('tahun', $tahunIni);
            })
            ->with(['jabatan', 'tim.divisi'])
            ->orderBy('nama')
            ->get();
        
        $divisis = Divisi::orderBy('nama_divisi')->get();
        $tims = Tim::orderBy('nama_tim')->get();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();

        return view('admin.gaji.gaji-massal-1', compact('divisis', 'tims', 'jabatans', 'pegawais', 'inputs', 'pegawaiBelumGajian'));
    }

    // ... (method langkahDua() dan simpan() tidak berubah)
    public function langkahDua(Request $request)
    {
        $request->validate(['pegawai_ids' => 'required|array|min:1'], ['pegawai_ids.required' => 'Anda harus memilih setidaknya satu pegawai.']);
        $pegawaiIds = $request->input('pegawai_ids');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $pegawais = Pegawai::whereIn('id', $pegawaiIds)->orderBy('nama')->get();
        $masterTunjangans = MasterTunjangan::orderBy('nama_tunjangan')->get();
        $masterPotongans = MasterPotongan::orderBy('nama_potongan')->get();
        return view('admin.gaji.gaji-massal-2', compact('pegawais', 'bulan', 'tahun', 'masterTunjangans', 'masterPotongans'));
    }

    public function simpan(Request $request)
    {
        $validated = $request->validate([
            'pegawai_gaji' => 'required|array',
            'pegawai_gaji.*.pegawai_id' => 'required|exists:pegawais,id',
            'pegawai_gaji.*.gaji_pokok' => 'required|numeric',
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
            'tunjangans' => 'nullable|array',
            'tunjangans.*.master_tunjangan_id' => 'required_with:tunjangans|exists:master_tunjangans,id',
            'tunjangans.*.jumlah' => 'required_with:tunjangans|numeric|min:0',
            'potongans' => 'nullable|array',
            'potongans.*.master_potongan_id' => 'required_with:potongans|exists:master_potongans,id',
            'potongans.*.jumlah' => 'required_with:potongans|numeric|min:0',
        ]);

        $tunjangansUmum = $request->tunjangans ?? [];
        $potongansUmum = $request->potongans ?? [];
        $totalTunjanganUmum = collect($tunjangansUmum)->sum('jumlah');
        $totalPotonganUmum = collect($potongansUmum)->sum('jumlah');

        DB::transaction(function () use ($validated, $tunjangansUmum, $potongansUmum, $totalTunjanganUmum, $totalPotonganUmum) {
            foreach ($validated['pegawai_gaji'] as $dataPegawai) {
                $gajiPokok = $dataPegawai['gaji_pokok'];
                $gajiBersih = $gajiPokok + $totalTunjanganUmum - $totalPotonganUmum;

                $gaji = Gaji::create([
                    'pegawai_id' => $dataPegawai['pegawai_id'],
                    'bulan' => $validated['bulan'],
                    'tahun' => $validated['tahun'],
                    'gaji_pokok' => $gajiPokok,
                    'total_tunjangan' => $totalTunjanganUmum,
                    'total_potongan' => $totalPotonganUmum,
                    'gaji_bersih' => $gajiBersih,
                ]);
                foreach ($tunjangansUmum as $tunjangan) { $gaji->tunjanganDetails()->create($tunjangan); }
                foreach ($potongansUmum as $potongan) { $gaji->potonganDetails()->create($potongan); }
            }
        });

        return redirect()->route('admin.gaji.index')
            ->with('success', 'Gaji untuk ' . count($validated['pegawai_gaji']) . ' pegawai berhasil ditambahkan.');
    }
}

