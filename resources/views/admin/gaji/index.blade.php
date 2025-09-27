<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Gaji
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- --- BAGIAN BARU: Menampilkan Pegawai Belum Gajian --- --}}
        @if(isset($pegawaiBelumGajian) && $pegawaiBelumGajian->isNotEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg shadow">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.636-1.21 2.862-1.21 3.498 0l6.234 11.857a2.25 2.25 0 01-1.749 3.544H3.766a2.25 2.25 0 01-1.749-3.544l6.234-11.857zM9 12.5a1 1 0 112 0 1 1 0 01-2 0zm1-4a1 1 0 011 1v2a1 1 0 11-2 0V9.5a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Pegawai Belum Menerima Gaji Bulan Ini ({{ $pegawaiBelumGajian->count() }} orang)
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($pegawaiBelumGajian as $pegawai)
                                    <li>{{ $pegawai->nama }} ({{ $pegawai->jabatan->nama_jabatan ?? 'N/A' }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- -------------------------------------------------------- --}}

        <div class="bg-white rounded-lg shadow-md p-4">
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
        
        <div class="bg-white rounded-lg shadow-md p-4">
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
                        <td class="border-b px-4 py-2 text-gray-800">Rp {{ number_format($g->gaji_bersih, 0, ',', '.') }}</td>
                        <td class="border-b px-4 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.gaji.slip', $g->id) }}" target="_blank"
                                   class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg text-sm shadow">
                                    Slip Gaji
                                </a>
                                <a href="{{ route('admin.gaji.edit', $g->id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm shadow">
                                    Edit
                                </a>
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
             <div class="mt-4">
                {{ $gaji->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.gaji.create') }}" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                    + Tambah Gaji
                </a>
            </div>
        </div>
    </div>
</x-app-layout>