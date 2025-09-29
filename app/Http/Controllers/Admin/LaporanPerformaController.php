<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Pegawai;
use App\Models\Tim;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanPerformaController extends Controller
{
    public function index(Request $request)
    {
        $filterOptions = [
            'divisis' => Divisi::orderBy('nama_divisi')->get(),
            'tims' => Tim::orderBy('nama_tim')->get(),
            'pegawais' => Pegawai::orderBy('nama')->get(),
        ];
        list($pegawais, $chartData, $filter) = $this->fetchPerformanceData($request);
        return view('admin.laporan.performa', compact('pegawais', 'chartData', 'filter', 'filterOptions'));
    }

    /**
     * Meng-handle download laporan dalam format PDF.
     */
    public function unduhPdf(Request $request)
    {
        list($pegawais, $chartData, $filter) = $this->fetchPerformanceData($request);
        
        // --- PERBAIKAN: Kirim juga $chartData ke view PDF ---
        $data = [
            'pegawais' => $pegawais,
            'filter' => $filter,
            'chartData' => $chartData // Data ini akan digunakan untuk membuat URL gambar chart
        ];

        $pdf = Pdf::loadView('admin.laporan.performa-pdf', $data)->setPaper('a4', 'landscape');
        
        $namaFile = 'laporan-performa-' . Str::slug($filter['title']) . '.pdf';
        
        return $pdf->download($namaFile);
    }

    private function fetchPerformanceData(Request $request)
    {
        // ... (method fetchPerformanceData tidak perlu diubah, sudah benar)
        $periode = $request->input('periode', 'bulanan');
        $tanggalMulai = Carbon::now()->startOfMonth();
        $tanggalSelesai = Carbon::now()->endOfMonth();

        switch ($periode) {
            case 'harian':
                $tanggalMulai = Carbon::now()->startOfDay();
                $tanggalSelesai = Carbon::now()->endOfDay();
                break;
            case 'mingguan':
                $tanggalMulai = Carbon::now()->startOfWeek();
                $tanggalSelesai = Carbon::now()->endOfWeek();
                break;
            case 'tahunan':
                $tanggalMulai = Carbon::now()->startOfYear();
                $tanggalSelesai = Carbon::now()->endOfYear();
                break;
            case 'custom':
                if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
                    $tanggalMulai = Carbon::parse($request->input('tanggal_mulai'))->startOfDay();
                    $tanggalSelesai = Carbon::parse($request->input('tanggal_selesai'))->endOfDay();
                }
                break;
        }

        $query = Pegawai::query();
        $filterTitle = 'Semua Pegawai';

        if ($request->filled('divisi_id')) {
            $divisi = Divisi::find($request->input('divisi_id'));
            $filterTitle = 'Divisi: ' . $divisi->nama_divisi;
            $query->whereHas('tim.divisi', fn($q) => $q->where('id', $divisi->id));
        } elseif ($request->filled('tim_id')) {
            $tim = Tim::find($request->input('tim_id'));
            $filterTitle = 'Tim: ' . $tim->nama_tim;
            $query->where('tim_id', $tim->id);
        } elseif ($request->filled('pegawai_id')) {
            $pegawai = Pegawai::find($request->input('pegawai_id'));
            $filterTitle = 'Pegawai: ' . $pegawai->nama;
            $query->where('id', $pegawai->id);
        }

        $pegawais = $query->with([
            'kehadirans' => fn($q) => $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]),
            'tugasDiterima' => fn($q) => $q->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
        ])->orderBy('nama')->get();

        $totalTugasSelesai = $pegawais->sum(fn($p) => $p->tugasDiterima->where('status', 'Selesai')->count());
        $totalTugasBelumSelesai = $pegawais->sum(fn($p) => $p->tugasDiterima->where('status', '!=', 'Selesai')->count());

        $chartData = [
            'labels' => $pegawais->pluck('nama'),
            'kehadiran' => $pegawais->map(fn($p) => $p->kehadirans->where('status', 'Hadir')->count()),
            'tugasSelesai' => $pegawais->map(fn($p) => $p->tugasDiterima->where('status', 'Selesai')->count()),
            'pieTugas' => ['selesai' => $totalTugasSelesai, 'belum_selesai' => $totalTugasBelumSelesai]
        ];

        $filter = [
            'periode' => $periode,
            'tanggal_mulai' => $tanggalMulai->format('d M Y'),
            'tanggal_selesai' => $tanggalSelesai->format('d M Y'),
            'title' => $filterTitle,
        ];
        
        return [$pegawais, $chartData, $filter];
    }
}