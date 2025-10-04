<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">

                <h1 class="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-2">
                    Halo, {{ $pegawai->nama }}! 👋
                </h1>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-10">

                    {{-- Statistik Gaji Tahunan (Highlight) --}}
                    <div
                        class="flex items-center p-4 bg-white border border-green-200 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-[1.02]">
                        <div class="flex-shrink-0 p-3 bg-green-500 rounded-full text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Gaji Tahun Ini</p>
                            <p class="text-lg font-bold text-green-700">Rp
                                {{ number_format($totalGajiTahun, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Statistik Kehadiran Bulanan --}}
                    <x-statistic-card color="blue" title="Total Hadir" value="{{ $totalHariMasuk }} hari"
                        icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <x-statistic-card color="black" title="Total Terlambat" value="{{ $totalTerlambat }} kali"
                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <x-statistic-card color="yellow" title="Total Izin" value="{{ $totalIzin }} hari"
                        icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <x-statistic-card color="red" title="Total Sakit" value="{{ $totalSakit }} hari"
                        icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M12 10.5v.5m0-4v.5" />
                    <x-statistic-card color="gray" title="Total Absen" value="{{ $totalAbsen }} hari"
                        icon="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636" />

                </div>

                <h2 class="text-2xl font-bold text-gray-800 mt-4 mb-4 border-b pb-2">Visualisasi Data</h2>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Grafik Kehadiran (1 Kolom) --}}
                    <div class="p-6 lg:col-span-1">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Ringkasan Kehadiran (Bulan
                            {{ $bulanSekarang }})</h3>
                        <div class="w-full h-96 flex items-center justify-center">
                            <canvas id="kehadiranChart"></canvas>
                        </div>
                    </div>

                    {{-- Grafik Gaji (2 Kolom) - PASTIKAN MENGISI PENUH --}}
                    <div class="p-6 lg:col-span-2">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Tren Gaji Bersih Tahun {{ $tahunSekarang }}
                        </h3>
                        {{-- Hapus style height dan biarkan w-full aspect-video yang mengatur proporsi --}}
                        <div class="w-full aspect-video" style="max-height: 450px;">
                            <canvas id="gajiChart" class="w-full"></canvas>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>

    <x-slot name="statCardComponent">
        <script></script>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const bulanArray = @json($bulanArray);
        const dataGaji = @json($dataGaji);
        const dataKehadiran = @json($dataKehadiran);

        // --- CHART GAJI (LINE CHART) ---
        const gajiChart = new Chart(document.getElementById('gajiChart'), {
            type: 'line',
            data: {
                labels: bulanArray,
                datasets: [{
                    label: 'Total Gaji Bersih (Rp)',
                    data: dataGaji,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(59, 130, 246)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: '#e5e7eb'
                        },
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // --- SOLUSI PAKSA RESIZE (FINAL TWEAK UNTUK MEMASTIKAN LEBAR TERBACA) ---
        // Ini adalah cara paling andal untuk memaksa Chart.js me-render ulang dengan lebar container yang benar.
        // Kita gunakan window.onload agar eksekusi setelah semua elemen di DOM selesai dimuat.
        window.onload = function() {
            if (gajiChart) {
                // Perintah resize Chart.js untuk menyesuaikan lebar penuh container (2 kolom)
                gajiChart.resize();
            }
        };


        // --- CHART KEHADIRAN (PIE CHART) ---
        new Chart(document.getElementById('kehadiranChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(dataKehadiran),
                datasets: [{
                    data: Object.values(dataKehadiran),
                    backgroundColor: [
                        '#3B82F6',
                        '#F59E0B',
                        '#EF4444',
                        '#6B7280',
                        '#F97316'
                    ],
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20
                        }
                    },
                    title: {
                        display: false,
                    }
                }
            }
        });
    </script>
</x-app-layout>
