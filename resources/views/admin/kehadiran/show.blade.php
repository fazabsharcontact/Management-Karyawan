<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Kehadiran - {{ $pegawai->user->name }}
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Filter Bulan & Tahun -->
            <form method="GET" class="flex flex-wrap gap-3 mb-4">
                <select name="bulan"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="tahun"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    @foreach(range(now()->year - 5, now()->year + 1) as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                    Filter
                </button>
            </form>

            <!-- Tabel Kehadiran -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border-b px-4 py-2 text-left">Tanggal</th>
                            <th class="border-b px-4 py-2 text-left">Status</th>
                            <th class="border-b px-4 py-2 text-left">Keterangan</th>
                            <th class="border-b px-4 py-2 text-center">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kehadiran as $k)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2">
                                {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="border-b px-4 py-2">{{ $k->status }}</td>
                            <td class="border-b px-4 py-2">{{ $k->keterangan ?? '-' }}</td>
                            <td class="border-b px-4 py-2 text-center">
                                @if($k->bukti)
                                    <a href="{{ route('admin.kehadiran.downloadBukti', $k->id) }}"
                                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm shadow">
                                        Download
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                Tidak ada data kehadiran untuk bulan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tombol Kembali -->
            <a href="{{ route('admin.kehadiran.index') }}"
               class="mt-4 inline-block bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md shadow">
                ← Kembali
            </a>
        </div>
    </div>
</x-app-layout>
