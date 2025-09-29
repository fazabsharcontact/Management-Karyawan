<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Performa Pegawai
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        <div class="bg-white rounded-lg shadow p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end text-sm">
                {{-- Filter Periode --}}
                <div>
                    <label for="periode" class="block font-medium">Periode</label>
                    <select name="periode" id="periode" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                        <option value="harian" @if(request('periode') == 'harian') selected @endif>Hari Ini</option>
                        <option value="mingguan" @if(request('periode') == 'mingguan') selected @endif>Minggu Ini</option>
                        <option value="bulanan" @if(request('periode', 'bulanan') == 'bulanan') selected @endif>Bulan Ini</option>
                        <option value="tahunan" @if(request('periode') == 'tahunan') selected @endif>Tahun Ini</option>
                        <option value="custom" @if(request('periode') == 'custom') selected @endif>Tanggal Kustom</option>
                    </select>
                </div>
                
                {{-- Filter Tanggal Kustom --}}
                <div id="custom-date-range" class="{{ request('periode') == 'custom' ? 'grid' : 'hidden' }} grid-cols-2 gap-2 col-span-1 md:col-span-2">
                    <div>
                        <label for="tanggal_mulai" class="block font-medium">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block font-medium">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                    </div>
                </div>

                {{-- --- FILTER BARU UNTUK HIRARKI --- --}}
                <div>
                    <label for="divisi_id" class="block font-medium">Filter per Divisi</label>
                    <select name="divisi_id" id="divisi_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                        <option value="">Semua Divisi</option>
                        @foreach($filterOptions['divisis'] as $divisi)
                        <option value="{{ $divisi->id }}" @if(request('divisi_id') == $divisi->id) selected @endif>{{ $divisi->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label for="tim_id" class="block font-medium">Filter per Tim</label>
                    <select name="tim_id" id="tim_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                        <option value="">Semua Tim</option>
                         @foreach($filterOptions['tims'] as $tim)
                        <option value="{{ $tim->id }}" @if(request('tim_id') == $tim->id) selected @endif>{{ $tim->nama_tim }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="pegawai_id" class="block font-medium">Filter per Pegawai</label>
                    <select name="pegawai_id" id="pegawai_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                        <option value="">Semua Pegawai</option>
                        @foreach($filterOptions['pegawais'] as $pegawai)
                        <option value="{{ $pegawai->id }}" @if(request('pegawai_id') == $pegawai->id) selected @endif>{{ $pegawai->nama }}</option>
                        @endforeach
                    </select>
                </div>
                 {{-- ------------------------------------ --}}

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Terapkan</button>
                    <a href="{{ route('admin.laporan.performa.pdf', request()->query()) }}" target="_blank"
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md shadow">
                        PDF
                    </a>
                </div>
            </form>
        </div>

        <div class="text-center">
            <h3 class="text-xl font-bold text-gray-800">Laporan Performa untuk: {{ $filter['title'] }}</h3>
            <p class="text-sm text-gray-500">Periode: {{ $filter['tanggal_mulai'] }} - {{ $filter['tanggal_selesai'] }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                <h3 class="font-semibold mb-4 text-gray-700">Total Kehadiran</h3>
                <div class="h-96"><canvas id="kehadiranChart"></canvas></div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold mb-4 text-gray-700">Ringkasan Tugas</h3>
                 <div class="h-96 flex items-center justify-center"><canvas id="pieTugasChart"></canvas></div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
             <h3 class="text-lg font-semibold mb-4">Detail Performa Pegawai</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border-b px-4 py-2 text-left">Nama Pegawai</th>
                            <th class="border-b px-4 py-2 text-center">Total Hadir</th>
                            <th class="border-b px-4 py-2 text-center">Total Sakit/Izin</th>
                            <th class="border-b px-4 py-2 text-center">Tugas Diterima</th>
                            <th class="border-b px-4 py-2 text-center">Tugas Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawais as $pegawai)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2 font-medium">{{ $pegawai->nama }}</td>
                            <td class="border-b px-4 py-2 text-center">{{ $pegawai->kehadirans->where('status', 'Hadir')->count() }}</td>
                            <td class="border-b px-4 py-2 text-center">{{ $pegawai->kehadirans->whereIn('status', ['Sakit', 'Izin'])->count() }}</td>
                            <td class="border-b px-4 py-2 text-center">{{ $pegawai->tugasDiterima->count() }}</td>
                            <td class="border-b px-4 py-2 text-center">{{ $pegawai->tugasDiterima->where('status', 'Selesai')->count() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data untuk filter yang dipilih.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.getElementById('periode').addEventListener('change', function () {
            document.getElementById('custom-date-range').classList.toggle('hidden', this.value !== 'custom');
        });

        // Logika untuk memastikan hanya satu filter hirarki yang aktif
        const divisiSelect = document.getElementById('divisi_id');
        const timSelect = document.getElementById('tim_id');
        const pegawaiSelect = document.getElementById('pegawai_id');

        divisiSelect.addEventListener('change', () => {
            if (divisiSelect.value) {
                timSelect.value = '';
                pegawaiSelect.value = '';
            }
        });
        timSelect.addEventListener('change', () => {
             if (timSelect.value) {
                divisiSelect.value = '';
                pegawaiSelect.value = '';
            }
        });
        pegawaiSelect.addEventListener('change', () => {
             if (pegawaiSelect.value) {
                divisiSelect.value = '';
                timSelect.value = '';
            }
        });

        const chartData = @json($chartData);
        // (Kode JavaScript untuk Chart tetap sama...)
        const kehadiranCtx = document.getElementById('kehadiranChart').getContext('2d');
        const gradientKehadiran = kehadiranCtx.createLinearGradient(0, 0, 0, 400);
        gradientKehadiran.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        gradientKehadiran.addColorStop(1, 'rgba(59, 130, 246, 0.2)');

        new Chart(kehadiranCtx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Total Hari Hadir',
                    data: chartData.kehadiran,
                    backgroundColor: gradientKehadiran,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: { x: { beginAtZero: true } },
                plugins: {
                    tooltip: {
                        callbacks: { label: (context) => `${context.raw} hari hadir` }
                    }
                }
            }
        });

        const pieTugasCtx = document.getElementById('pieTugasChart').getContext('2d');
        new Chart(pieTugasCtx, {
            type: 'pie',
            data: {
                labels: ['Tugas Selesai', 'Belum Selesai'],
                datasets: [{
                    data: [chartData.pieTugas.selesai, chartData.pieTugas.belum_selesai],
                    backgroundColor: ['rgba(16, 185, 129, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                    borderColor: ['#fff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                 plugins: {
                    tooltip: {
                        callbacks: { label: (context) => `${context.label}: ${context.raw} tugas` }
                    }
                }
            }
        });
    </script>
</x-app-layout>