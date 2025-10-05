<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Monitoring Kehadiran Pegawai
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">

            {{-- Alert --}}
            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700">{{ session('error') }}</div>
            @endif

            {{-- Filter --}}
            <form method="GET" class="flex flex-wrap items-end gap-3 mb-6 pb-4 border-b">
                {{-- Select2 untuk Pegawai --}}
                <div class="flex-grow min-w-[250px]">
                    <label for="pegawai-select" class="block text-sm font-medium text-gray-700">Pegawai</label>
                    <select name="pegawai_id" id="pegawai-select" class="mt-1 block w-full">
                        <option value="">Semua Pegawai</option>
                        {{-- PERBAIKAN: Gunakan $pegawais --}}
                        @foreach ($pegawais as $p)
                            <option value="{{ $p->id }}" {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Bulan --}}
                <div class="min-w-[150px]">
                     <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                    <select name="bulan" id="bulan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @foreach (['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $key => $val)
                            <option value="{{ $key }}" {{ request('bulan', now()->format('m')) == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tahun --}}
                <div class="min-w-[100px]">
                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                    <select name="tahun" id="tahun" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @for ($i = now()->year; $i >= now()->year - 5; $i--)
                            <option value="{{ $i }}" {{ request('tahun', now()->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Tombol Filter --}}
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                    Filter
                </button>
            </form>

            {{-- Tabel Rekapitulasi --}}
             <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Rekapitulasi Kehadiran</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 text-center">
                    @php
                        $total_hadir = $rekap->sum('total_hadir');
                        $total_terlambat = $rekap->sum('total_terlambat');
                        $total_sakit = $rekap->sum('total_sakit');
                        $total_izin = $rekap->sum('total_izin');
                        $total_absen = $rekap->sum('total_absen');
                    @endphp
                    <div class="p-4 bg-green-50 rounded-lg"><div class="text-2xl font-bold text-green-700">{{ $total_hadir }}</div><div class="text-sm text-green-600">Hadir</div></div>
                    <div class="p-4 bg-yellow-50 rounded-lg"><div class="text-2xl font-bold text-yellow-700">{{ $total_terlambat }}</div><div class="text-sm text-yellow-600">Terlambat</div></div>
                    <div class="p-4 bg-blue-50 rounded-lg"><div class="text-2xl font-bold text-blue-700">{{ $total_sakit }}</div><div class="text-sm text-blue-600">Sakit</div></div>
                    <div class="p-4 bg-indigo-50 rounded-lg"><div class="text-2xl font-bold text-indigo-700">{{ $total_izin }}</div><div class="text-sm text-indigo-600">Izin</div></div>
                    <div class="p-4 bg-red-50 rounded-lg"><div class="text-2xl font-bold text-red-700">{{ $total_absen }}</div><div class="text-sm text-red-600">Absen</div></div>
                </div>
            </div>

            {{-- Tabel Kehadiran Detail --}}
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Pegawai</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Jam Masuk</th>
                            <th class="px-4 py-2 border">Jam Pulang</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kehadiran as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border">{{ $row->pegawai->nama }}</td>
                                <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                                <td class="px-4 py-2 border">{{ $row->jam_masuk ? \Carbon\Carbon::parse($row->jam_masuk)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-2 border">{{ $row->jam_pulang ? \Carbon\Carbon::parse($row->jam_pulang)->format('H:i') : '-' }}</td>
                                <td class="px-4 py-2 border text-center">
                                     <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($row->status == 'Hadir') bg-green-100 text-green-800
                                        @elseif($row->status == 'Terlambat') bg-yellow-100 text-yellow-800
                                        @elseif($row->status == 'Sakit') bg-blue-100 text-blue-800
                                        @elseif($row->status == 'Izin') bg-indigo-100 text-indigo-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $row->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    @if ($row->bukti)
                                        <a href="{{ route('admin.kehadiran.downloadBukti', $row->id) }}" class="text-blue-500 hover:underline">Download</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data kehadiran yang cocok dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Link Pagination --}}
            <div class="mt-4">
                {{ $kehadiran->links() }}
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 pada dropdown pegawai
            $('#pegawai-select').select2({
                placeholder: "Cari atau pilih pegawai",
                allowClear: true,
                width: 'resolve' // Otomatis menyesuaikan lebar
            });
        });
    </script>
    @endpush
</x-app-layout>
