<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Pegawai
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h1 class="text-2xl font-bold mb-4">Selamat datang, {{ $pegawai->nama }}!</h1>

                <!-- Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-gray-100 p-4 rounded shadow text-center">
                        <h3 class="font-semibold">Total Hari Masuk</h3>
                        <p class="text-xl font-bold">{{ $totalHariMasuk }} hari</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded shadow text-center">
                        <h3 class="font-semibold">Total Gaji Bulan Ini</h3>
                        <p class="text-xl font-bold">Rp {{ number_format($totalGaji, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded shadow text-center">
                        <h3 class="font-semibold">Total Izin Bulan Ini</h3>
                        <p class="text-xl font-bold">{{ $totalIzin }} hari</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded shadow text-center">
                        <h3 class="font-semibold">Total Sakit Bulan Ini</h3>
                        <p class="text-xl font-bold">{{ $totalSakit }} hari</p>
                    </div>
                </div>

                <!-- Grafik -->
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-semibold mb-4">Grafik Gaji Tahun {{ now()->year }}</h3>
                    <canvas id="gajiChart" height="100"></canvas>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('gajiChart'), {
            type: 'line',
            data: {
                labels: @json($bulanArray),
                datasets: [{
                    label: 'Total Gaji',
                    data: @json($dataGaji),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</x-app-layout>