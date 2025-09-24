<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Gaji
        </h2>
    </x-slot>

    <div class="p-6">
        <!-- Filter -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 mb-6">
            <form method="GET" class="flex flex-col md:flex-row gap-3">
                <input type="text" name="search" placeholder="Cari nama pegawai..." 
                       class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3 focus:ring focus:ring-blue-200"
                       value="{{ request('search') }}">
                <select name="jabatan" 
                        class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3 focus:ring focus:ring-blue-200">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatan as $j)
                        <option value="{{ $j->nama_jabatan }}" {{ request('jabatan') == $j->nama_jabatan ? 'selected' : '' }}>
                            {{ $j->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                    Filter
                </button>
            </form>
        </div>

        <!-- Tabel -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4">
            <table class="w-full border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Nama Pegawai</th>
                        <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Jabatan</th>
                        <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Bulan</th>
                        <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Tahun</th>
                        <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Gaji Bersih</th>
                        <th class="border-b px-4 py-2 text-center text-sm font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $bulan_nama = [
                            1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 
                            5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus", 
                            9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
                        ];
                    @endphp
                    @forelse($gaji as $g)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="border-b px-4 py-2 text-gray-800">{{ $g->pegawai->nama ?? '-' }}</td>
                        <td class="border-b px-4 py-2 text-gray-800">{{ $g->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                        <td class="border-b px-4 py-2 text-gray-800">{{ $bulan_nama[$g->bulan] ?? '-' }}</td>
                        <td class="border-b px-4 py-2 text-gray-800">{{ $g->tahun }}</td>
                        {{-- PERBAIKAN: Gunakan 'gaji_bersih' --}}
                        <td class="border-b px-4 py-2 text-gray-800">Rp {{ number_format($g->gaji_bersih, 0, ',', '.') }}</td>
                        <td class="border-b px-4 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- PERBAIKAN: Gunakan '$g->id' --}}
                                <a href="{{ route('admin.gaji.edit', $g->id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm shadow">
                                    Edit
                                </a>
                                {{-- PERBAIKAN: Gunakan '$g->id' --}}
                                <form action="{{ route('admin.gaji.destroy', $g->id) }}" method="POST" 
                                      onsubmit="return confirm('Hapus data gaji ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm shadow">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">Tidak ada data gaji yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Tombol tambah -->
            <div class="mt-6">
                <a href="{{ route('admin.gaji.create') }}" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                    + Tambah Gaji
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
