<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Tim & Divisi
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        <!-- Notifikasi -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Bagian Manajemen Divisi -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Divisi</h3>
                <a href="{{ route('admin.divisi.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow text-sm">
                    + Tambah Divisi
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border-b px-4 py-2 text-left">Nama Divisi</th>
                            <th class="border-b px-4 py-2 text-left">Jumlah Tim</th>
                            <th class="border-b px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($divisis as $divisi)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2 font-medium">{{ $divisi->nama_divisi }}</td>
                            <td class="border-b px-4 py-2">{{ $divisi->tims_count }} Tim</td>
                            <td class="border-b px-4 py-2 text-center" style="width: 150px;">
                                <a href="{{ route('admin.divisi.edit', $divisi->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                <form action="{{ route('admin.divisi.destroy', $divisi->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus divisi {{ $divisi->nama_divisi }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">Belum ada divisi yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bagian Manajemen Tim -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Tim</h3>
                <a href="{{ route('admin.tim.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow text-sm">
                    + Tambah Tim
                </a>
            </div>
            <div class="space-y-4">
                @forelse($divisis as $divisi)
                    @if($divisi->tims->isNotEmpty())
                        <div class="border rounded-lg overflow-hidden">
                            <h4 class="bg-gray-50 px-4 py-2 font-semibold">{{ $divisi->nama_divisi }}</h4>
                            <table class="min-w-full">
                                <tbody>
                                    @foreach($divisi->tims as $tim)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $tim->nama_tim }}</td>
                                        <td class="px-4 py-2 text-gray-500 text-sm">{{ $tim->pegawais->count() }} Anggota</td>
                                        <td class="px-4 py-2 text-right" style="width: 150px;">
                                            <a href="{{ route('admin.tim.edit', $tim->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.tim.destroy', $tim->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tim {{ $tim->nama_tim }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @empty
                    <p class="text-center text-gray-500 py-4">Belum ada tim yang ditambahkan.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
