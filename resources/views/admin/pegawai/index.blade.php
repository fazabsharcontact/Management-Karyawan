<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👔 Manajemen Data Pegawai
        </h2>
    </x-slot>

    <div class="flex">
        <!-- Konten -->
        <div class="w-4/5 p-6">
            <!-- Filter -->
            <form method="GET" class="flex gap-2 mb-4">
                <input type="text" name="search" placeholder="Cari pegawai..." 
                       class="border rounded p-2" value="{{ request('search') }}">
                <select name="jabatan" class="border rounded p-2">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatan as $j)
                        <option value="{{ $j->nama_jabatan }}" 
                                {{ request('jabatan') == $j->nama_jabatan ? 'selected' : '' }}>
                            {{ $j->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 rounded">Filter</button>
            </form>

            <!-- Tabel -->
            <table class="table-auto w-full border">
                <thead>
                    <tr>
                        <th class="border px-2 py-1">Nama</th>
                        <th class="border px-2 py-1">Jabatan</th>
                        <th class="border px-2 py-1">Gaji</th>
                        <th class="border px-2 py-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawai as $p)
                    <tr>
                        <td class="border px-2 py-1">{{ $p->nama }}</td>
                        <td class="border px-2 py-1">{{ $p->jabatan->nama_jabatan ?? 'Tidak Ada Jabatan' }}</td>
                        <td class="border px-2 py-1">Rp {{ number_format($p->gaji, 0, ',', '.') }}</td>
                        <td class="border px-2 py-1">
                            <a href="{{ route('admin.pegawai.edit', $p->id_pegawai) }}" 
                               class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                            <form action="{{ route('admin.pegawai.destroy', $p->id_pegawai) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-2 py-1 rounded"
                                        onclick="return confirm('Hapus pegawai {{ $p->nama }}?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('admin.pegawai.create') }}" 
               class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded">
                + Tambah Pegawai
            </a>
        </div>
    </div>
</x-app-layout>