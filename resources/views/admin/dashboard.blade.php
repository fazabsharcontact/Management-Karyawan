<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="flex">
        <div class="w-4/5 p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

            <div class="grid grid-cols-3 gap-6">
                <div class="bg-white p-4 shadow rounded">
                    <h3 class="text-lg">Total Pegawai</h3>
                    <p class="text-2xl font-bold">{{ $totalPegawai }}</p>
                </div>
                <div class="bg-white p-4 shadow rounded">
                    <h3 class="text-lg">Total Gaji Bulan Ini</h3>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalGaji, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 shadow rounded">
                    <h3 class="text-lg">Pegawai per Jabatan</h3>
                    <canvas id="jabatanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

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
                    backgroundColor: ["#007bff", "#28a745", "#ffc107", "#dc3545", "#17a2b8", "#6f42c1"]
                }]
            }
        });
    </script>
</x-app-layout>