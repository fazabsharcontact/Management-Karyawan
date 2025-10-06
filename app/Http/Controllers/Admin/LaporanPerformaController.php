<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Pegawai;
use App\Models\Tim;
use App\Models\Kehadiran;
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
        
        list($pegawais, $chartData, $filter, $totals, $kehadiranDetails) = $this->fetchPerformanceData($request);

        return view('admin.laporan.performa', compact('pegawais', 'chartData', 'filter', 'filterOptions', 'totals', 'kehadiranDetails'));
    }

    public function unduhPdf(Request $request)
    {
        list($pegawais, $chartData, $filter, $totals, $kehadiranDetails) = $this->fetchPerformanceData($request, false);

        $data = [
            'pegawais' => $pegawais, 'filter' => $filter, 'chartData' => $chartData,
            'totals' => $totals, 'kehadiranDetails' => $kehadiranDetails
        ];

        $pdf = Pdf::loadView('admin.laporan.performa-pdf', $data)->setPaper('a4', 'landscape');
        $namaFile = 'laporan-performa-' . Str::slug($filter['title']) . '.pdf';
        return $pdf->download($namaFile);
    }

    private function fetchPerformanceData(Request $request, $paginateKehadiran = true)
    {
        $periode = $request->input('periode', 'bulanan');
        $tanggalMulai = Carbon::now()->startOfMonth();
        $tanggalSelesai = Carbon::now()->endOfMonth();

        switch ($periode) {
            case 'harian': $tanggalMulai = Carbon::now()->startOfDay(); $tanggalSelesai = Carbon::now()->endOfDay(); break;
            case 'mingguan': $tanggalMulai = Carbon::now()->startOfWeek(); $tanggalSelesai = Carbon::now()->endOfWeek(); break;
            case 'tahunan': $tanggalMulai = Carbon::now()->startOfYear(); $tanggalSelesai = Carbon::now()->endOfYear(); break;
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
            if($divisi) $filterTitle = 'Divisi: ' . $divisi->nama_divisi;
            $query->whereHas('tim.divisi', fn($q) => $q->where('id', $request->input('divisi_id')));
        } elseif ($request->filled('tim_id')) {
            $tim = Tim::find($request->input('tim_id'));
            if($tim) $filterTitle = 'Tim: ' . $tim->nama_tim;
            $query->where('tim_id', $request->input('tim_id'));
        } elseif ($request->filled('pegawai_id')) {
            $pegawai = Pegawai::find($request->input('pegawai_id'));
            if($pegawai) $filterTitle = 'Pegawai: ' . $pegawai->nama;
            $query->where('id', $request->input('pegawai_id'));
        }

        $pegawais = $query->with([
            'kehadirans' => fn($q) => $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6'),
            'tugasDiterima' => fn($q) => $q->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])->whereRaw('DAYOFWEEK(created_at) BETWEEN 2 AND 6'),
        ])->orderBy('nama')->get();
        
        $pegawaiIds = $pegawais->pluck('id');
        $kehadiranQuery = Kehadiran::with('pegawai')
            ->whereIn('pegawai_id', $pegawaiIds)
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->whereRaw('DAYOFWEEK(tanggal) BETWEEN 2 AND 6')
            ->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');

        $kehadiranDetails = $paginateKehadiran ? $kehadiranQuery->paginate(15, ['*'], 'kehadiran_page') : $kehadiranQuery->get();

        $pegawais->each(function ($pegawai) {
            $pegawai->total_hadir = $pegawai->kehadirans->where('status', 'Hadir')->count();
            $pegawai->total_sakit_izin = $pegawai->kehadirans->whereIn('status', ['Sakit', 'Izin'])->count();
            $pegawai->jumlah_telat = $pegawai->kehadirans->where('status', 'Terlambat')->count();
            $totalHariMasukKerja = $pegawai->total_hadir + $pegawai->jumlah_telat;
            $pegawai->persentase_keterlambatan = ($totalHariMasukKerja > 0) ? round(($pegawai->jumlah_telat / $totalHariMasukKerja) * 100) : 0;
            $pegawai->total_tugas_diterima = $pegawai->tugasDiterima->count();
            $pegawai->total_tugas_selesai = $pegawai->tugasDiterima->where('status', 'Selesai')->count();
        });
        
        $totals = [
            'total_hadir' => $pegawais->sum('total_hadir'),
            'total_sakit_izin' => $pegawais->sum('total_sakit_izin'),
            'total_telat' => $pegawais->sum('jumlah_telat'),
            'rata_rata_keterlambatan' => $pegawais->avg('persentase_keterlambatan'),
            'total_tugas_diterima' => $pegawais->sum('total_tugas_diterima'),
            'total_tugas_selesai' => $pegawais->sum('total_tugas_selesai'),
        ];
        
        $chartData = [
            'labels' => $pegawais->pluck('nama'),
            'kehadiran' => $pegawais->pluck('total_hadir'),
            'pieTugas' => ['selesai' => $totals['total_tugas_selesai'], 'belum_selesai' => $totals['total_tugas_diterima'] - $totals['total_tugas_selesai']],
            'pieKeterlambatan' => ['tepat_waktu' => $totals['total_hadir'], 'telat' => $totals['total_telat']],
            'rataRataWaktuKerja' => $pegawais->map(function ($pegawai) {
                $avgMasuk = $pegawai->kehadirans->whereNotNull('jam_masuk')->avg(fn($k) => Carbon::parse($k->jam_masuk)->hour + Carbon::parse($k->jam_masuk)->minute / 60);
                $avgPulang = $pegawai->kehadirans->whereNotNull('jam_pulang')->avg(fn($k) => Carbon::parse($k->jam_pulang)->hour + Carbon::parse($k->jam_pulang)->minute / 60);
                return [$avgMasuk, $avgPulang];
            })
        ];

        $filter = [
            'periode' => $periode,
            'tanggal_mulai' => $tanggalMulai->format('d M Y'),
            'tanggal_selesai' => $tanggalSelesai->format('d M Y'),
            'title' => $filterTitle,
        ];
        
        return [$pegawais, $chartData, $filter, $totals, $kehadiranDetails];
    }
}
