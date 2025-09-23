<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Manajemen Jabatan
        </h2>
    </x-slot>

    <div class="p-6">
        <!-- Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Filter -->
            <form method="GET" class="flex flex-wrap gap-2 mb-4">
                <input type="text" name="search" placeholder="Cari jabatan..." 
                       class="border rounded px-3 py-2 w-60"
                       value="{{ request('search') }}">
                <select name="jabatan" class="border rounded px-3 py-2">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatanList as $j)
                        <option value="{{ $j }}" {{ request('jabatan') == $j ? 'selected' : '' }}>
                            {{ $j }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Filter
                </button>
            </form>

            <!-- Tabel -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Nama Jabatan</th>
                            <th class="border px-4 py-2 text-left">Tunjangan</th>
                            <th class="border px-4 py-2 text-left">Gaji Awal</th>
                            <th class="border px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jabatan as $j)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $j->nama_jabatan }}</td>
                            <td class="border px-4 py-2">Rp {{ number_format($j->tunjangan, 0, ',', '.') }}</td>
                            <td class="border px-4 py-2">Rp {{ number_format($j->gaji_awal, 0, ',', '.') }}</td>
                            <td class="border px-4 py-2 text-center">
                                <a href="{{ route('admin.jabatan.edit', $j->id_jabatan) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded mr-2">
                                    Edit
                                </a>
                                <form action="{{ route('admin.jabatan.destroy', $j->id_jabatan) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                                            onclick="return confirm('Hapus jabatan ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.jabatan.create') }}" 
               class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                + Tambah Jabatan
            </a>
        </div>
    </div>
</x-app-layout>