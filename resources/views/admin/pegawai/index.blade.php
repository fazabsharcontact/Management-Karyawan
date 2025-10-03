<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Data Pegawai
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 mb-6">
            <form method="GET" class="flex flex-col md:flex-row gap-3">
                <input type="text" name="search" placeholder="Cari pegawai..." 
                       class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3 focus:ring focus:ring-blue-200"
                       value="{{ request('search') }}">
                <select name="jabatan" 
                        class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3 focus:ring focus:ring-blue-200">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatan as $j)
                        <option value="{{ $j->nama_jabatan }}" 
                                {{ request('jabatan') == $j->nama_jabatan ? 'selected' : '' }}>
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

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Nama</th>
                            <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Jabatan</th>
                            <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Tim / Divisi</th>
                            <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Gaji Pokok</th>
                            <th class="border-b px-4 py-2 text-center text-sm font-medium text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawai as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border-b px-4 py-2 text-gray-800">{{ $p->nama }}</td>
                            <td class="border-b px-4 py-2 text-gray-800">
                                {{ $p->jabatan->nama_jabatan ?? 'Tidak Ada Jabatan' }}
                            </td>
                            <td class="border-b px-4 py-2 text-gray-800">
                                @if ($p->tim)
                                    {{ $p->tim->nama_tim }}
                                    <span class="text-xs text-gray-500 block">{{ $p->tim->divisi->nama_divisi }}</span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="border-b px-4 py-2 text-gray-800">
                                Rp {{ number_format($p->gaji_pokok, 0, ',', '.') }}
                            </td>
                            <td class="border-b px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.pegawai.edit', $p->id) }}" 
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm shadow">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.pegawai.destroy', $p->id) }}" method="POST" 
                                          onsubmit="return confirm('Hapus pegawai {{ $p->nama }}?')">
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
                            <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data pegawai yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- --- PERBAIKAN: Tambahkan kembali link paginasi di sini --- --}}
            <div class="mt-4">
                {{-- appends(request()->query()) akan memastikan filter tetap aktif saat pindah halaman --}}
                {{ $pegawai->appends(request()->query())->links() }}
            </div>
            
            <div class="mt-6">
                <a href="{{ route('admin.pegawai.create') }}" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                    + Tambah Pegawai
                </a>
            </div>
        </div>
    </div>
</x-app-layout>