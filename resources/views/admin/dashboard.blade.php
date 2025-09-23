<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Admin</h1>

        <!-- Grid Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card Total Pegawai -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-sm font-medium text-gray-500">Total Pegawai</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalPegawai }}</p>
            </div>

            <!-- Card Total Gaji -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-sm font-medium text-gray-500">Total Gaji Bulan Ini</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    Rp {{ number_format($totalGaji, 0, ',', '.') }}
                </p>
            </div>

            <!-- Card Chart -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-sm font-medium text-gray-500 mb-4">Pegawai per Jabatan</h3>
                <canvas id="jabatanChart" class="h-48"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let jabatanLabels = {!! json_encode(array_keys($jabatanData)) !!};
        let jabatanValues = {!! json_encode(array_values($jabatanData)) !!};

        new Chart(document.getElementById("jabatanChart"), {
            type: "pie",
            data: {
                labels: jabatanLabels,
                datasets: [{
                    data: jabatanValues,
                    backgroundColor: [
                        "#3B82F6", "#10B981", "#F59E0B",
                        "#EF4444", "#6366F1", "#14B8A6"
                    ],
                    borderWidth: 1,
                    borderColor: "#fff"
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            font: { size: 12 },
                            color: "#374151"
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>