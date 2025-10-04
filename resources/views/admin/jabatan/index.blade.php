<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Jabatan
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Filter -->
            <form method="GET" class="flex flex-wrap gap-3 mb-4">
                <input type="text" name="search" placeholder="Cari nama jabatan..."
                       class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full md:w-auto"
                       value="{{ request('search') }}">
                <select name="jabatan" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full md:w-auto">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatans as $j)
                        <option value="{{ $j->nama_jabatan }}" {{ request('jabatan') == $j->nama_jabatan ? 'selected' : '' }}>
                            {{ $j->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                    Filter
                </button>
            </form>

            <!-- Tabel -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border-b px-4 py-2 text-left">Nama Jabatan</th>
                            {{-- KOLOM BARU DITAMBAHKAN --}}
                            <th class="border-b px-4 py-2 text-left">Gaji Awal</th>
                            <th class="border-b px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jabatans as $jabatan)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2">{{ $jabatan->nama_jabatan }}</td>
                            <td class="border-b px-4 py-2">Rp {{ number_format($jabatan->tunjangan, 0, ',', '.') }}</td>
                            {{-- DATA BARU DITAMPILKAN --}}
                            <td class="border-b px-4 py-2">Rp {{ number_format($jabatan->gaji_awal, 0, ',', '.') }}</td>
                            <td class="border-b px-4 py-2 text-center">
                                <a href="{{ route('admin.jabatan.edit', $jabatan->id) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm shadow mr-2">
                                    Edit
                                </a>
                                <form action="{{ route('admin.jabatan.destroy', $jabatan->id) }}"
                                      method="POST" class="inline" onsubmit="return confirm('Hapus jabatan {{ $jabatan->nama_jabatan }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm shadow">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- COLSPAN DIPERBARUI MENJADI 4 --}}
                            <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data jabatan ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.jabatan.create') }}"
               class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
                + Tambah Jabatan
            </a>
        </div>
    </div>
</x-app-layout>

