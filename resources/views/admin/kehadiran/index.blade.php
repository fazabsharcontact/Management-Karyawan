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
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter --}}
            <form method="GET" class="flex flex-wrap gap-3 mb-4">
                <select name="pegawai_id" class="border-gray-300 rounded-md shadow-sm w-full md:w-auto">
                    <option value="">Semua Pegawai</option>
                    @foreach ($pegawai as $p)
                        <option value="{{ $p->id }}" {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>

                <select name="bulan" class="border-gray-300 rounded-md shadow-sm w-full md:w-auto">
                    @foreach ([
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ] as $key => $val)
                        <option value="{{ $key }}"
                            {{ request('bulan', now()->format('m')) == $key ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>

                <select name="tahun" class="border-gray-300 rounded-md shadow-sm w-full md:w-auto">
                    @for ($i = now()->year; $i >= now()->year - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun', now()->year) == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                    Filter
                </button>
            </form>

            {{-- Tabel Kehadiran --}}
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
                                <td class="px-4 py-2 border">
                                    {{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 border">{{ $row->jam_masuk ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ $row->jam_pulang ?? '-' }}</td>
                                <td class="px-4 py-2 border">
                                    @if ($row->status == 'Hadir')
                                        <span class="text-green-600 font-semibold">{{ $row->status }}</span>
                                    @elseif($row->status == 'Izin' || $row->status == 'Sakit')
                                        <span class="text-blue-600 font-semibold">{{ $row->status }}</span>
                                    @else
                                        <span class="text-red-600 font-semibold">{{ $row->status }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    @if ($row->bukti)
                                        <a href="{{ route('admin.kehadiran.downloadBukti', $row->id) }}"
                                            class="text-blue-500 hover:underline">Download</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data kehadiran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
