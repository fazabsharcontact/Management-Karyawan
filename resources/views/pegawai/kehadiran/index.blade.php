<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kehadiran Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- Alert --}}
                @if(session('success'))
                    <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Form Absensi --}}
                <form action="{{ route('pegawai.kehadiran.store') }}" method="POST" class="flex gap-3 mb-6">
                    @csrf
                    <select name="status" required class="border rounded px-2 py-1">
                        <option value="Hadir">Hadir</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Absen">Absen</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Lakukan Kehadiran
                    </button>
                </form>

                {{-- Filter --}}
                <form method="GET" action="{{ route('pegawai.kehadiran.index') }}" class="flex gap-3 mb-6 flex-wrap">
                    <div>
                        <label for="tahun" class="block text-sm font-medium">Tahun:</label>
                        <select name="tahun" id="tahun" class="border rounded px-2 py-1">
                            @for($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="bulan" class="block text-sm font-medium">Bulan:</label>
                        <select name="bulan" id="bulan" class="border rounded px-2 py-1">
                            @foreach ([
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ] as $key => $val)
                                <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Filter
                        </button>
                    </div>
                </form>

                {{-- Tabel Riwayat --}}
                <h3 class="text-lg font-semibold mb-2">Riwayat Absensi</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Tanggal</th>
                                <th class="px-4 py-2 border">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kehadiran as $row)
                                <tr>
                                    <td class="px-4 py-2 border">
                                        {{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ $row->status }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-2 border text-center">Tidak ada data absensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>