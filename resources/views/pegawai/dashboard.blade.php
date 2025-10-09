<x-app-layout>
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- === HEADER === -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <div>
                    <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                        Dashboard Pegawai
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Selamat datang kembali, <span class="font-semibold text-gray-900">{{ $pegawai->nama }}</span> 
                    </p>
                </div>
                <div class="flex items-center bg-gray-100 px-4 py-2 rounded-lg shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h18M9 3v18m6-18v18M4.5 6h15m-15 4.5h15m-15 4.5h15m-15 4.5h15"/>
                    </svg>
                    <span class="text-gray-700 text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>

            <!-- === GRID STATISTIK === -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @php
                    $stats = [
                        ['title' => 'Gaji Tahun Ini', 'value' => 'Rp ' . number_format($totalGajiTahun, 0, ',', '.'), 'color' => 'green', 'icon' => 'currency-dollar'],
                        ['title' => 'Total Hadir', 'value' => $totalHariMasuk . ' hari', 'color' => 'blue', 'icon' => 'check-circle'],
                        ['title' => 'Total Terlambat', 'value' => $totalTerlambat . ' kali', 'color' => 'black', 'icon' => 'clock'],
                        ['title' => 'Total Izin', 'value' => $totalIzin . ' hari', 'color' => 'yellow', 'icon' => 'calendar'],
                        ['title' => 'Total Sakit', 'value' => $totalSakit . ' hari', 'color' => 'red', 'icon' => 'heart'],
                        ['title' => 'Total Absen', 'value' => $totalAbsen . ' hari', 'color' => 'gray', 'icon' => 'x-circle'],
                    ];
                @endphp

                @foreach ($stats as $stat)
                    <div class="bg-gray-50 border border-gray-100 rounded-2xl shadow-sm p-5 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-{{ $stat['color'] }}-100 text-{{ $stat['color'] }}-600 shadow-inner">
                                @switch($stat['icon'])
                                    @case('currency-dollar')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10v12"/>
                                        </svg>
                                        @break
                                    @case('check-circle')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @break
                                    @case('clock')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @break
                                    @case('calendar')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
                                        </svg>
                                        @break
                                    @case('heart')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-8.682a4.5 4.5 0 010-6.364z"/>
                                        </svg>
                                        @break
                                    @case('x-circle')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @break
                                @endswitch
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-semibold">{{ $stat['title'] }}</p>
                                <p class="text-xl font-bold text-gray-900 mt-1">{{ $stat['value'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- === VISUALISASI DATA === -->
            <div class="mt-10">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-2">Visualisasi Data</h3>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Grafik Kehadiran -->
                    <div class="bg-gray-50 rounded-2xl shadow-md p-6 border border-gray-100 flex flex-col items-center justify-center">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 text-center">
                            Ringkasan Kehadiran Bulan {{ $bulanSekarang }}
                        </h4>
                        <div class="h-72 w-72 flex items-center justify-center">
                            <canvas id="kehadiranChart"></canvas>
                        </div>
                    </div>

                    <!-- Grafik Gaji -->
                    <div class="bg-gray-50 rounded-2xl shadow-md p-6 border border-gray-100 lg:col-span-2">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 text-center">
                            Tren Gaji Bersih Tahun {{ $tahunSekarang }}
                        </h4>
                        <div class="w-full aspect-video">
                            <canvas id="gajiChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- === GRID BARU: DAFTAR PENGUMUMAN === -->
            <div class="mt-10 w-full">
                <div class="bg-gray-50 rounded-2xl shadow-md p-6 border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-2">Daftar Pengumuman</h3>

                    @if ($pengumumans->isEmpty())
                        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center font-medium">
                            Belum ada pengumuman untuk Anda.
                        </div>
                    @else
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach ($pengumumans as $p)
                                <div class="bg-white shadow-sm border rounded-xl p-4 hover:shadow-md transition duration-200">
                                    <h4 class="font-bold text-gray-800 text-lg mb-1">
                                        {{ $p->pengumuman->judul ?? '-' }}
                                    </h4>
                                    <p class="text-gray-600 text-sm mb-2 line-clamp-3">
                                        {{ $p->pengumuman->isi ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        {{ $p->pengumuman->created_at?->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- === SCRIPT CHARTS === -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const bulanArray = @json($bulanArray);
        const dataGaji = @json($dataGaji);
        const dataKehadiran = @json($dataKehadiran);

        const gajiChart = new Chart(document.getElementById('gajiChart'), {
            type: 'line',
            data: {
                labels: bulanArray,
                datasets: [{
                    label: 'Total Gaji Bersih (Rp)',
                    data: dataGaji,
                    borderColor: '#111827',
                    backgroundColor: 'rgba(17, 24, 39, 0.08)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#111827'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e5e7eb' } },
                    x: { grid: { display: false } }
                }
            }
        });

        new Chart(document.getElementById('kehadiranChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(dataKehadiran),
                datasets: [{
                    data: Object.values(dataKehadiran),
                    backgroundColor: ['#3B82F6', '#F59E0B', '#EF4444', '#6B7280', '#22C55E'],
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { padding: 15 } } }
            }
        });
    </script>
</x-app-layout>