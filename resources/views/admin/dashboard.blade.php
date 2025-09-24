<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6 text-gray-800">Ringkasan</h1>

                    <!-- Grid Statistik -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <!-- Card Total Pegawai -->
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-100 flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Total Pegawai Aktif</h3>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalPegawai }}</p>
                            </div>
                        </div>

                        <!-- Card Total Gaji -->
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-100 flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Total Gaji Periode Terakhir</h3>
                                <p class="mt-2 text-3xl font-bold text-gray-900">
                                    Rp {{ number_format($totalGaji, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Card Chart Jabatan -->
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-100 md:col-span-2 lg:col-span-1">
                            <h3 class="text-sm font-medium text-gray-500 mb-4">Pegawai per Jabatan</h3>
                            <div class="h-48 flex items-center justify-center">
                                <canvas id="jabatanChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- --- BAGIAN BARU UNTUK AKTIVITAS --- -->
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Card Aktivitas Terbaru -->
                        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Aktivitas Terbaru</h3>
                            <ul class="space-y-4">
                                @forelse ($aktivitas as $item)
                                    <li class="flex items-start space-x-3 pb-3 border-b border-gray-100 last:border-b-0">
                                        <div class="flex-shrink-0 pt-1">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
                                                {{-- Mengambil emoji dari keterangan --}}
                                                {{ mb_substr(explode(' ', $item->keterangan)[0], 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            {{-- Gunakan {!! !!} agar tag <b> bisa dirender sebagai HTML --}}
                                            <p class="text-sm text-gray-800">{!! $item->keterangan !!}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($item->waktu)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </li>
                                @empty
                                    <li>
                                        <p class="text-center text-gray-500 py-4">Tidak ada aktivitas terbaru dalam 7 hari terakhir.</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let jabatanLabels = {!! json_encode(array_keys($jabatanData)) !!};
            let jabatanValues = {!! json_encode(array_values($jabatanData)) !!};

            if (jabatanLabels.length > 0) {
                new Chart(document.getElementById("jabatanChart"), {
                    type: "pie",
                    data: {
                        labels: jabatanLabels,
                        datasets: [{
                            data: jabatanValues,
                            backgroundColor: [
                                "#4F46E5", "#10B981", "#F59E0B",
                                "#EF4444", "#8B5CF6", "#14B8A6"
                            ],
                            borderWidth: 2,
                            borderColor: "#fff"
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "bottom",
                                labels: {
                                    font: { size: 12 },
                                    color: "#374151",
                                    padding: 15
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
