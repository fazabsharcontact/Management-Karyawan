<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Performa Pegawai</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10px; 
            line-height: 1.4; 
            margin: 20px; 
            background-color: #ffffff; /* Putih polos agar print bersih */
            color: #000000; /* Hitam pekat */
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
            border-radius: 8px; /* Rounded corners untuk tabel */
            overflow: hidden; /* Clip border untuk radius */
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); /* Subtle shadow minimalis */
        }
        th, td { 
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            text-align: left;
            color: #000000; /* Pastikan isi tabel hitam */
        }
        thead th {
            background-color: #cbd5e1 !important; /* abu lebih gelap */
            color: #000000 !important;
        }

        /* Header nama kolom */
        #table-performa thead th {
            font-family: Helvetica, Arial, sans-serif;
            background-color: #cbd5e1 !important; /* abu agak gelap biar kontras */
            color: #000000 !important;
            font-weight: bold;
        }

        /* Baris terakhir TOTAL / RATA-RATA */
        #table-performa tbody.footer-table tr td {
            font-family: Helvetica, Arial, sans-serif;
            font-weight: bold;
            color: #000000;
        }


        th { 
            background: linear-gradient(135deg, #6366f1, #8b5cf6); /* Gradient indigo-ungu youthful */
            color: white; 
            font-weight: 600; /* Semi-bold untuk modern */
            text-shadow: 0 1px 2px rgba(0,0,0,0.1); /* Subtle shadow */
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: 600; }
        h1, h2, h3, h4 { 
            text-align: center; 
            margin: 10px 0; 
            font-weight: 700; /* Bold tapi clean */
        }
        h1 { 
            font-size: 22px; 
            color: #1e40af; /* Biru tua vibrant */
            text-shadow: 0 2px 4px rgba(30,64,175,0.1); /* Soft shadow */
            letter-spacing: 0.5px; /* Spacing untuk feel fresh */
        }
        h2 { font-size: 16px; color: #374151; }
        h3 { font-size: 13px; color: #4b5563; }
        h4 { 
            font-size: 12px; 
            font-weight: 600; 
            margin-bottom: 12px; 
            color: #6366f1; /* Aksen indigo untuk youthful vibe */
        }
        .page-break { page-break-before: always; }
        .footer-table { 
            background: linear-gradient(135deg, #10b981, #059669); /* Gradient hijau emerald */
            color: white; 
            font-weight: 600; 
        }
        .chart-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 25px; /* Spacing lebih luas untuk minimalis */
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .chart-grid td {
            width: 50%;
            text-align: center;
            border: none;
            vertical-align: top;
            padding: 10px; /* Padding untuk breathable layout */
            background: white; /* White background untuk charts */
            border-radius: 12px; /* Rounded lebih besar */
            box-shadow: 0 4px 20px rgba(99,102,241,0.1); /* Shadow indigo subtle */
        }
        .chart-grid img {
            max-width: 100%;
            border: 2px solid #e0e7ff; /* Border biru muda pastel */
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(99,102,241,0.15); /* Shadow lebih playful */
            background: linear-gradient(145deg, #ffffff, #eff6ff); /* Gradient biru sangat ringan */
        }
        .section-header {
            text-align: left; 
            border-bottom: 2px solid #e0e7ff; /* Border biru muda dashed-like */
            padding-bottom: 8px;
            margin: 30px 0 15px 0;
            color: #1e40af; /* Biru vibrant */
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.3px;
            background: linear-gradient(to right, #f0f9ff, #e0f2fe); /* Subtle gradient background */
            padding: 10px 15px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(59,130,246,0.1);
        }
        /* Alternating row untuk tabel, feel modern */
        tbody tr:nth-child(even) {
            background-color: #f1f5f9; /* Abu muda jelas */
        }
        tbody tr:hover {
            background-color: #ffffff; /* Putih */

        }
    </style>
</head>
<body>
    @php
        // Fungsi helper untuk generate array warna gradient-like untuk bar charts (berdasarkan jumlah labels) - lebih youthful dengan pastel
        $numLabels = count($chartData['labels']);
        $kehadiranColors = [];
        $jamKerjaColors = [];
        for ($i = 0; $i < $numLabels; $i++) {
            $hue = 160 + ($i * (60 / max(1, $numLabels))); // Mint hijau ke cyan untuk fresh vibe
            $kehadiranColors[] = "hsl($hue, 60%, 60%)"; // Saturation rendah untuk pastel
            $jamKerjaColors[] = "hsl(" . (200 + ($i * (40 / max(1, $numLabels)))) . ", 60%, 55%)"; // Biru ke lavender
        }
        
        // Config untuk Kehadiran: Horizontal Bar dengan warna pastel per bar, rounded, minimalis
        $kehadiranConfig = [ 
            'type' => 'horizontalBar', 
            'data' => [ 
                'labels' => $chartData['labels'], 
                'datasets' => [ 
                    [
                        'label' => 'Total Hadir', 
                        'data' => $chartData['kehadiran'],
                        'backgroundColor' => $kehadiranColors, // Pastel variasi
                        'borderColor' => array_map(function($color) { return str_replace('60%', '40%', $color); }, $kehadiranColors), // Border lebih soft
                        'borderWidth' => 1, // Tipis untuk minimalis
                        'borderRadius' => 8, // Rounded lebih besar
                        'borderSkipped' => false
                    ] 
                ] 
            ], 
            'options' => [ 
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Distribusi Kehadiran',
                        'font' => ['size' => 14, 'weight' => 'bold', 'family' => 'Helvetica'], // Ubah ke 'bold' untuk kompatibilitas
                        'color' => '#1e40af',
                        'padding' => 15
                    ],
                    'legend' => [
                        'display' => false, // Hide legend untuk minimalis, fokus data
                    ],
                    'tooltip' => [
                        'backgroundColor' => 'rgba(255,255,255,0.95)',
                        'titleColor' => '#1f2937',
                        'bodyColor' => '#4b5563',
                        'borderColor' => '#e0e7ff',
                        'borderWidth' => 1,
                        'titleFont' => ['size' => 12, 'weight' => 'bold'],
                        'bodyFont' => ['size' => 11]
                    ]
                ],
                'scales' => [ 
                    'xAxes' => [ 
                        [
                            'ticks' => ['beginAtZero' => true, 'fontColor' => '#6b7280', 'fontSize' => 10],
                            'gridLines' => ['color' => 'rgba(226,232,240,0.5)', 'drawBorder' => false, 'zeroLineColor' => '#e0e7ff'],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Hari Hadir',
                                'fontColor' => '#1e40af',
                                'fontSize' => 11
                            ]
                        ] 
                    ],
                    'yAxes' => [
                        [
                            'ticks' => ['fontColor' => '#6b7280', 'fontSize' => 9],
                            'gridLines' => ['color' => 'rgba(226,232,240,0.5)', 'drawBorder' => false],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Pegawai',
                                'fontColor' => '#1e40af',
                                'fontSize' => 11
                            ]
                        ]
                    ]
                ],
                'layout' => [
                    'padding' => ['top' => 15, 'right' => 15, 'bottom' => 15, 'left' => 15]
                ],
                'elements' => [
                    'point' => ['radius' => 0] // No points untuk clean
                ]
            ] 
        ];
        
        // Config untuk Jam Kerja: Mirip, warna lavender-ish
        $jamKerjaConfig = [ 
            'type' => 'horizontalBar', 
            'data' => [ 
                'labels' => $chartData['labels'], 
                'datasets' => [ 
                    [
                        'label' => 'Rata-rata Jam', 
                        'data' => $chartData['rataRataWaktuKerja'],
                        'backgroundColor' => $jamKerjaColors,
                        'borderColor' => array_map(function($color) { return str_replace('55%', '35%', $color); }, $jamKerjaColors),
                        'borderWidth' => 1,
                        'borderRadius' => 8,
                        'borderSkipped' => false
                    ] 
                ] 
            ], 
            'options' => [ 
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Durasi Kerja Harian',
                        'font' => ['size' => 14, 'weight' => 'bold', 'family' => 'Helvetica'],
                        'color' => '#1e40af',
                        'padding' => 15
                    ],
                    'legend' => [
                        'display' => false,
                    ],
                    'tooltip' => [
                        'backgroundColor' => 'rgba(255,255,255,0.95)',
                        'titleColor' => '#1f2937',
                        'bodyColor' => '#4b5563',
                        'borderColor' => '#e0e7ff',
                        'borderWidth' => 1,
                        'titleFont' => ['size' => 12, 'weight' => 'bold'],
                        'bodyFont' => ['size' => 11]
                    ]
                ],
                'scales' => [ 
                    'xAxes' => [ 
                        [
                            'ticks' => ['min' => 7, 'max' => 19, 'stepSize' => 1, 'fontColor' => '#6b7280'],
                            'gridLines' => ['color' => 'rgba(226,232,240,0.5)', 'drawBorder' => false],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Jam (rata-rata)',
                                'fontColor' => '#1e40af',
                                'fontSize' => 11
                            ]
                        ] 
                    ],
                    'yAxes' => [
                        [
                            'ticks' => ['fontColor' => '#6b7280', 'fontSize' => 9],
                            'gridLines' => ['color' => 'rgba(226,232,240,0.5)', 'drawBorder' => false],
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Pegawai',
                                'fontColor' => '#1e40af',
                                'fontSize' => 11
                            ]
                        ]
                    ]
                ],
                'layout' => [
                    'padding' => ['top' => 15, 'right' => 15, 'bottom' => 15, 'left' => 15]
                ],
                'elements' => [
                    'point' => ['radius' => 0]
                ]
            ] 
        ];
        
        // Config untuk Pie Tugas: Diperbaiki lebih lanjut - gunakan type 'pie' sederhana, config minimal untuk menghindari overlap data, solid colors unik, no advanced features
        $pieTugasConfig = [ 
            'type' => 'pie', // Ubah ke 'pie' untuk sederhana dan kompatibilitas lebih baik (tanpa cutout issue)
            'data' => [ 
                'labels' => ['Tugas Selesai', 'Tugas Belum Selesai'], // Labels lebih deskriptif dan unik
                'datasets' => [ 
                    [
                        'data' => [$chartData['pieTugas']['selesai'], $chartData['pieTugas']['belum_selesai']],
                        'backgroundColor' => [
                            '#10b981', // Hijau emerald unik untuk selesai
                            '#f59e0b'  // Kuning amber unik untuk belum
                        ],
                        'borderColor' => [
                            '#059669', 
                            '#d97706'
                        ],
                        'borderWidth' => 2
                    ] 
                ] 
            ], 
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Ringkasan Progres Tugas',
                        'font' => ['size' => 14, 'weight' => 'bold'],
                        'color' => '#1e40af',
                        'padding' => 10
                    ],
                    'legend' => [
                        'display' => true,
                        'position' => 'right', // Pindah ke right untuk membedakan dari chart lain, hindari overlap visual
                        'labels' => [
                            'font' => ['size' => 10, 'weight' => 'normal'],
                            'padding' => 10,
                            'usePointStyle' => true,
                            'boxWidth' => 8
                        ]
                    ]
                ],
                // Hilangkan rotation, circumference, animation, cutout untuk sederhana - fokus pada data akurat
                'tooltips' => [ // Gunakan 'tooltips' alih-alih 'tooltip' untuk kompatibilitas Chart.js v2 di QuickChart
                    'enabled' => true,
                    'backgroundColor' => 'rgba(0,0,0,0.8)',
                    'titleFontSize' => 12,
                    'bodyFontSize' => 11
                ]
            ]
        ];
        
        // Config untuk Pie Keterlambatan: Serupa, type 'pie', config unik dan minimal untuk membedakan dari pie tugas
        $keterlambatanConfig = [ 
            'type' => 'pie', 
            'data' => [ 
                'labels' => ['Hari Tepat Waktu', 'Hari Terlambat'], // Labels lebih deskriptif dan unik
                'datasets' => [ 
                    [
                        'data' => [$chartData['pieKeterlambatan']['tepat_waktu'], $chartData['pieKeterlambatan']['telat']],
                        'backgroundColor' => [
                            '#3b82f6', // Biru sky unik untuk tepat waktu
                            '#ef4444'  // Merah cerah unik untuk telat
                        ],
                        'borderColor' => [
                            '#2563eb', 
                            '#dc2626'
                        ],
                        'borderWidth' => 2
                    ] 
                ] 
            ], 
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Ringkasan Disiplin Waktu',
                        'font' => ['size' => 14, 'weight' => 'bold'],
                        'color' => '#1e40af',
                        'padding' => 10
                    ],
                    'legend' => [
                        'display' => true,
                        'position' => 'left', // Posisi berbeda (left) untuk membedakan visual dari pie tugas
                        'labels' => [
                            'font' => ['size' => 10, 'weight' => 'normal'],
                            'padding' => 10,
                            'usePointStyle' => true,
                            'boxWidth' => 8
                        ]
                    ]
                ],
                'tooltips' => [ // Gunakan 'tooltips' untuk kompatibilitas
                    'enabled' => true,
                    'backgroundColor' => 'rgba(0,0,0,0.8)',
                    'titleFontSize' => 12,
                    'bodyFontSize' => 11
                ]
            ]
        ];
        
        $kehadiranUrl = 'https://quickchart.io/chart?w=450&h=280&c=' . urlencode(json_encode($kehadiranConfig));
        $jamKerjaUrl = 'https://quickchart.io/chart?w=450&h=280&c=' . urlencode(json_encode($jamKerjaConfig));
        $pieTugasUrl = 'https://quickchart.io/chart?w=300&h=200&c=' . urlencode(json_encode($pieTugasConfig));
        $keterlambatanUrl = 'https://quickchart.io/chart?w=300&h=200&c=' . urlencode(json_encode($keterlambatanConfig));
    @endphp

    {{-- ========= HALAMAN 1: JUDUL & CHART BAR ========= --}}
    <h1>Laporan Performa Pegawai</h1>
    <h2>{{ $filter['title'] }}</h2>
    <h3>Periode: {{ $filter['tanggal_mulai'] }} - {{ $filter['tanggal_selesai'] }}</h3>

    <h3 class="section-header">Grafik Bar Performa</h3>
    <table class="chart-grid">
        <tr>
            <td>
                <h4>Total Kehadiran</h4>
                <img src="{{ $kehadiranUrl }}" alt="Chart Kehadiran">
            </td>
            <td>
                <h4>Rata-rata Jam Kerja</h4>
                <img src="{{ $jamKerjaUrl }}" alt="Chart Jam Kerja">
            </td>
        </tr>
    </table>

    {{-- ========= HALAMAN 2: CHART PIE ========= --}}
    <div class="page-break"></div>
    <h3 class="section-header">Grafik Ringkasan</h3>
    <table class="chart-grid">
        <tr>
            <td>
                <h4>Ringkasan Tugas</h4>
                <img src="{{ $pieTugasUrl }}" alt="Chart Tugas">
            </td>
            <td>
                <h4>Ringkasan Keterlambatan</h4>
                <img src="{{ $keterlambatanUrl }}" alt="Chart Keterlambatan">
            </td>
        </tr>
    </table>

    {{-- ========= HALAMAN 3: DETAIL PERFORMA PEGAWAI ========= --}}
    <div class="page-break"></div>
    <h3 class="section-header">Detail Data Performa Pegawai</h3>
    <table id="table-performa">
        <thead>
            <tr>
                <th>Nama Pegawai</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Sakit/Izin</th>
                <th class="text-center">Jml Telat</th>
                <th class="text-center">Tingkat Keterlambatan</th>
                <th class="text-center">Tugas Diterima</th>
                <th class="text-center">Tugas Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pegawais as $pegawai)
            <tr>
                <td>{{ $pegawai->nama }}</td>
                <td class="text-center">{{ $pegawai->total_hadir }}</td>
                <td class="text-center">{{ $pegawai->total_sakit_izin }}</td>
                <td class="text-center">{{ $pegawai->jumlah_telat }}</td>
                <td class="text-center">{{ $pegawai->persentase_keterlambatan }}%</td>
                <td class="text-center">{{ $pegawai->total_tugas_diterima }}</td>
                <td class="text-center">{{ $pegawai->total_tugas_selesai }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data untuk filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
        @if($pegawais->isNotEmpty())
        <tfoot class="footer-table">
            <tr>
                <td>TOTAL / RATA-RATA</td>
                <td class="text-center">{{ $totals['total_hadir'] }}</td>
                <td class="text-center">{{ $totals['total_sakit_izin'] }}</td>
                <td class="text-center">{{ $totals['total_telat'] }}</td>
                <td class="text-center">{{ round($totals['rata_rata_keterlambatan'], 1) }}%</td>
                <td class="text-center">{{ $totals['total_tugas_diterima'] }}</td>
                <td class="text-center">{{ $totals['total_tugas_selesai'] }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- ========= HALAMAN 4: DETAIL DATA KEHADIRAN ========= --}}
    <div class="page-break"></div>
    <h3 class="section-header">Detail Data Kehadiran & Keterlambatan</h3>
    <table id="table-kehadiran">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
                <th class="text-center">Jam Masuk</th>
                <th class="text-center">Jam Pulang</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kehadiranDetails as $kehadiran)
            <tr>
                <td>{{ \Carbon\Carbon::parse($kehadiran->tanggal)->format('d M Y') }}</td>
                <td>{{ $kehadiran->pegawai->nama ?? 'N/A' }}</td>
                <td class="text-center">
                    @if(is_null($kehadiran->jam_masuk) || $kehadiran->jam_masuk > '09:10:00')
                        <span style="color: #ef4444; font-weight: 600;">{{ $kehadiran->jam_masuk ?? 'ABSEN' }}</span>
                    @else
                        {{ $kehadiran->jam_masuk }}
                    @endif
                </td>
                <td class="text-center">{{ $kehadiran->jam_pulang ?? '-' }}</td>
                <td class="text-center">{{ $kehadiran->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data kehadiran untuk filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>