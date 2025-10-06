<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Performa Pegawai
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold border-b pb-3 mb-4 text-gray-800">Filter Laporan</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end text-sm">
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
                <div id="custom-date-range" class="{{ request('periode') == 'custom' ? 'grid' : 'hidden' }} grid-cols-2 gap-4 col-span-1 md:col-span-2 lg:col-span-1">
                    <div>
                        <label for="tanggal_mulai" class="block font-medium">Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block font-medium">Sampai Tanggal</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                    </div>
                </div>

                {{-- Filter Hirarki --}}
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

                <div class="flex gap-2 col-span-full md:col-span-1">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-sm">Terapkan</button>
                    <a href="{{ route('admin.laporan.performa.pdf', request()->query()) }}" target="_blank"
                       class="w-full text-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md shadow-sm">
                        Unduh PDF
                    </a>
                </div>
            </form>
        </div>
        
        <div class="text-center">
            <h3 class="text-xl font-bold text-gray-800">Laporan Performa untuk: {{ $filter['title'] }}</h3>
            <p class="text-sm text-gray-500">Periode: {{ $filter['tanggal_mulai'] }} s/d {{ $filter['tanggal_selesai'] }}</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6"><h3 class="font-semibold mb-4 text-gray-700">Total Kehadiran</h3><div class="h-96"><canvas id="kehadiranChart"></canvas></div></div>
            <div class="bg-white rounded-lg shadow-sm p-6"><h3 class="font-semibold mb-4 text-gray-700">Rata-rata Jam Kerja</h3><div class="h-96"><canvas id="jamKerjaChart"></canvas></div></div>
            <div class="bg-white rounded-lg shadow-sm p-6"><h3 class="font-semibold mb-4 text-gray-700">Ringkasan Tugas</h3><div class="h-80 flex items-center justify-center"><canvas id="pieTugasChart"></canvas></div></div>
            <div class="bg-white rounded-lg shadow-sm p-6"><h3 class="font-semibold mb-4 text-gray-700">Ringkasan Keterlambatan</h3><div class="h-80 flex items-center justify-center"><canvas id="keterlambatanChart"></canvas></div></div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
             <h3 class="text-lg font-semibold mb-4">Detail Performa Pegawai</h3>
             <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pegawai</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit/Izin</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Telat</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Keterlambatan</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas Diterima</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pegawais as $pegawai)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium whitespace-nowrap">{{ $pegawai->nama }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">{{ $pegawai->total_hadir }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">{{ $pegawai->total_sakit_izin }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">{{ $pegawai->jumlah_telat }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap font-medium">{{ $pegawai->persentase_keterlambatan }}%</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">{{ $pegawai->total_tugas_diterima }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">{{ $pegawai->total_tugas_selesai }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data untuk filter yang dipilih.</td></tr>
                        @endforelse
                    </tbody>
                    @if($pegawais->isNotEmpty())
                    <tfoot class="bg-gray-50 font-bold">
                        <tr>
                            <td class="px-4 py-2 text-left">TOTAL / RATA-RATA</td>
                            <td class="px-4 py-2 text-center">{{ $totals['total_hadir'] }}</td>
                            <td class="px-4 py-2 text-center">{{ $totals['total_sakit_izin'] }}</td>
                            <td class="px-4 py-2 text-center">{{ $totals['total_telat'] }}</td>
                            <td class="px-4 py-2 text-center text-blue-600">{{ round($totals['rata_rata_keterlambatan'], 1) }}%</td>
                            <td class="px-4 py-2 text-center">{{ $totals['total_tugas_diterima'] }}</td>
                            <td class="px-4 py-2 text-center">{{ $totals['total_tugas_selesai'] }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Detail Data Kehadiran & Keterlambatan</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pegawai</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kehadiranDetails as $kehadiran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($kehadiran->tanggal)->format('d M Y') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $kehadiran->pegawai->nama ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-center text-sm font-mono">
                                @if($kehadiran->status === 'Terlambat')
                                    <span class="font-bold text-red-600">{{ $kehadiran->jam_masuk ? \Carbon\Carbon::parse($kehadiran->jam_masuk)->format('H:i') : 'N/A' }}</span>
                                @elseif(is_null($kehadiran->jam_masuk))
                                    <span class="text-gray-400">ABSEN</span>
                                @else
                                    {{ \Carbon\Carbon::parse($kehadiran->jam_masuk)->format('H:i') }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-sm font-mono">{{ $kehadiran->jam_pulang ? \Carbon\Carbon::parse($kehadiran->jam_pulang)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-center text-sm">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($kehadiran->status == 'Hadir') bg-green-100 text-green-800
                                    @elseif($kehadiran->status == 'Terlambat') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $kehadiran->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data kehadiran untuk filter yang dipilih.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $kehadiranDetails->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ... (Skrip chart Anda tidak diubah, hanya skrip filter di bawah) ...
            document.getElementById('periode').addEventListener('change', function () {
                document.getElementById('custom-date-range').classList.toggle('hidden', this.value !== 'custom');
            });
            const divisiSelect = document.getElementById('divisi_id');
            const timSelect = document.getElementById('tim_id');
            const pegawaiSelect = document.getElementById('pegawai_id');
            divisiSelect.addEventListener('change', () => { if (divisiSelect.value) { timSelect.value = ''; pegawaiSelect.value = ''; } });
            timSelect.addEventListener('change', () => { if (timSelect.value) { divisiSelect.value = ''; pegawaiSelect.value = ''; } });
            pegawaiSelect.addEventListener('change', () => { if (pegawaiSelect.value) { divisiSelect.value = ''; timSelect.value = ''; } });

            const chartData = @json($chartData);
            
            const kehadiranCtx = document.getElementById('kehadiranChart').getContext('2d');
            const gradientKehadiran = kehadiranCtx.createLinearGradient(0, 0, 0, 400);
            gradientKehadiran.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
            gradientKehadiran.addColorStop(1, 'rgba(59, 130, 246, 0.2)');
            new Chart(kehadiranCtx, {
                type: 'bar', data: { labels: chartData.labels, datasets: [{
                label: 'Total Hari Hadir', data: chartData.kehadiran, backgroundColor: gradientKehadiran, 
                borderColor: 'rgba(59, 130, 246, 1)', borderWidth: 1, borderRadius: 8 }]
                }, options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, scales: { x: { beginAtZero: true } }, plugins: { tooltip: { callbacks: { label: (context) => `${context.raw} hari hadir` } } } }
            });

            const jamKerjaCtx = document.getElementById('jamKerjaChart').getContext('2d');
            function toHHMM(decimalHour) {
                if (decimalHour === null || isNaN(decimalHour)) return 'N/A';
                const hours = Math.floor(decimalHour);
                const minutes = Math.round((decimalHour - hours) * 60);
                return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
            }
            new Chart(jamKerjaCtx, {
                type: 'bar', data: { labels: chartData.labels, datasets: [{
                label: 'Rata-rata Jam Kerja', data: chartData.rataRataWaktuKerja, backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)', borderWidth: 1, borderRadius: 5, borderSkipped: false }]
                }, options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, scales: { x: { min: 7, max: 19, ticks: { callback: (value) => value + ':00' } } }, 
                plugins: { tooltip: { callbacks: { label: function(context) { const jamMasuk = toHHMM(context.raw[0]); const jamPulang = toHHMM(context.raw[1]); return ` Rata-rata: ${jamMasuk} - ${jamPulang}`; } } } } }
            });

            const pieTugasCtx = document.getElementById('pieTugasChart').getContext('2d');
            new Chart(pieTugasCtx, {
                type: 'doughnut', data: { labels: ['Selesai', 'Belum Selesai'], datasets: [{
                data: [chartData.pieTugas.selesai, chartData.pieTugas.belum_selesai], backgroundColor: ['rgba(16, 185, 129, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                borderColor: ['#fff'], borderWidth: 2 }]
                }, options: { responsive: true, maintainAspectRatio: false, plugins: { tooltip: { callbacks: { label: (c) => `${c.label}: ${c.raw} tugas` } } } }
            });

            const keterlambatanCtx = document.getElementById('keterlambatanChart').getContext('2d');
            new Chart(keterlambatanCtx, {
                type: 'doughnut', data: { labels: ['Tepat Waktu', 'Terlambat'], datasets: [{
                data: [chartData.pieKeterlambatan.tepat_waktu, chartData.pieKeterlambatan.telat], backgroundColor: ['rgba(16, 185, 129, 0.8)', 'rgba(245, 158, 11, 0.8)'],
                borderColor: ['#fff'], borderWidth: 2 }]
                }, options: { responsive: true, maintainAspectRatio: false, plugins: { tooltip: { callbacks: { label: (c) => `${c.label}: ${c.raw} kehadiran` } } } }
            });
        });
    </script>
    @endpush
</x-app-layout>