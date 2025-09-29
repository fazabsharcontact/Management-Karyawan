<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Tunjangan & Potongan
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        <!-- Notifikasi -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Bagian Master Tunjangan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Jenis Tunjangan</h3>
                <a href="{{ route('admin.master-tunjangan.create') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                    + Tambah Tunjangan
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border-b px-4 py-2 text-left">Nama Tunjangan</th>
                            <th class="border-b px-4 py-2 text-left">Deskripsi</th>
                            {{-- KOLOM BARU DITAMBAHKAN --}}
                            <th class="border-b px-4 py-2 text-left">Jumlah Default</th>
                            <th class="border-b px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($masterTunjangans as $tunjangan)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2">{{ $tunjangan->nama_tunjangan }}</td>
                            <td class="border-b px-4 py-2">{{ $tunjangan->deskripsi ?? '-' }}</td>
                            {{-- DATA BARU DITAMPILKAN --}}
                            <td class="border-b px-4 py-2">
                                Rp {{ number_format($tunjangan->jumlah_default ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="border-b px-4 py-2 text-center" style="width: 150px;">
                                <a href="{{ route('admin.master-tunjangan.edit', $tunjangan->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</a>
                                <form action="{{ route('admin.master-tunjangan.destroy', $tunjangan->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jenis tunjangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- Colspan disesuaikan menjadi 4 --}}
                            <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data jenis tunjangan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $masterTunjangans->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>

        <!-- Bagian Master Potongan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Jenis Potongan</h3>
                <a href="{{ route('admin.master-potongan.create') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                    + Tambah Potongan
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border-b px-4 py-2 text-left">Nama Potongan</th>
                            <th class="border-b px-4 py-2 text-left">Deskripsi</th>
                            {{-- KOLOM BARU DITAMBAHKAN --}}
                            <th class="border-b px-4 py-2 text-left">Jumlah Default</th>
                            <th class="border-b px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($masterPotongans as $potongan)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2">{{ $potongan->nama_potongan }}</td>
                            <td class="border-b px-4 py-2">{{ $potongan->deskripsi ?? '-' }}</td>
                            {{-- DATA BARU DITAMPILKAN --}}
                            <td class="border-b px-4 py-2">
                                Rp {{ number_format($potongan->jumlah_default ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="border-b px-4 py-2 text-center" style="width: 150px;">
                                <a href="{{ route('admin.master-potongan.edit', $potongan->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">Edit</a>
                                <form action="{{ route('admin.master-potongan.destroy', $potongan->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jenis potongan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- Colspan disesuaikan menjadi 4 --}}
                            <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data jenis potongan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $masterPotongans->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

