<!DOCTYPE html>
<html>
<head>
    <title>Laporan Performa Pegawai</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        h1, h2, h3 { text-align: center; margin: 5px 0; }
        .chart-container { text-align: center; margin-bottom: 20px; }
        .chart { max-width: 80%; margin: 10px auto; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <h1>Laporan Performa Pegawai</h1>
    <h2>{{ $filter['title'] }}</h2>
    <h3>Periode: {{ $filter['tanggal_mulai'] }} - {{ $filter['tanggal_selesai'] }}</h3>

    {{-- --- BAGIAN BARU UNTUK MENAMPILKAN CHART --- --}}
    @php
        // Siapkan data untuk chart kehadiran (bar)
        $kehadiranConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $chartData['labels'],
                'datasets' => [[
                    'label' => 'Total Hari Hadir',
                    'data' => $chartData['kehadiran']
                ]]
            ]
        ];
        $kehadiranUrl = 'https://quickchart.io/chart?w=500&h=300&c=' . urlencode(json_encode($kehadiranConfig));

        // Siapkan data untuk chart tugas (pie)
        $pieTugasConfig = [
            'type' => 'pie',
            'data' => [
                'labels' => ['Tugas Selesai', 'Belum Selesai'],
                'datasets' => [[
                    'data' => [$chartData['pieTugas']['selesai'], $chartData['pieTugas']['belum_selesai']]
                ]]
            ]
        ];
        $pieTugasUrl = 'https://quickchart.io/chart?w=400&h=250&c=' . urlencode(json_encode($pieTugasConfig));
    @endphp

    <div class="chart-container">
        <h3>Grafik Visualisasi Performa</h3>
        @if(count($chartData['labels']) > 0)
            <img src="{{ $kehadiranUrl }}" alt="Chart Kehadiran" class="chart">
            <img src="{{ $pieTugasUrl }}" alt="Chart Status Tugas" class="chart">
        @else
            <p>Tidak ada data untuk ditampilkan dalam bentuk chart.</p>
        @endif
    </div>
    {{-- --------------------------------------------- --}}
    
    <div class="page-break"></div>

    <h3>Detail Data Pegawai</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Pegawai</th>
                <th class="text-center">Total Hadir</th>
                <th class="text-center">Total Sakit/Izin</th>
                <th class="text-center">Tugas Diterima</th>
                <th class="text-center">Tugas Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pegawais as $pegawai)
            <tr>
                <td>{{ $pegawai->nama }}</td>
                <td class="text-center">{{ $pegawai->kehadirans->where('status', 'Hadir')->count() }}</td>
                <td class="text-center">{{ $pegawai->kehadirans->whereIn('status', ['Sakit', 'Izin'])->count() }}</td>
                <td class="text-center">{{ $pegawai->tugasDiterima->count() }}</td>
                <td class="text-center">{{ $pegawai->tugasDiterima->where('status', 'Selesai')->count() }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data untuk filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>