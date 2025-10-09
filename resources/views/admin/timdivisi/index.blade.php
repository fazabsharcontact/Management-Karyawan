<x-app-layout>
    <div class="p-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                Manajemen Tim & Divisi
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola struktur organisasi perusahaan dengan mudah</p>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-xl border border-red-200 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Bagian Divisi --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Divisi</h3>
                    <a href="{{ route('admin.divisi.create') }}" 
                        class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow">
                        + Tambah Divisi
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <tr>
                                <th class="border-b px-4 py-3 text-left">Nama Divisi</th>
                                <th class="border-b px-4 py-3 text-left">Jumlah Tim</th>
                                <th class="border-b px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($divisis as $divisi)
                                <tr class="hover:bg-gray-50 transition-all">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $divisi->nama_divisi }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $divisi->tims_count }} Tim</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.divisi.edit', $divisi->id) }}" 
                                                class="bg-gray-800 hover:bg-black text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.divisi.destroy', $divisi->id) }}" method="POST" onsubmit="return confirm('Hapus divisi {{ $divisi->nama_divisi }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-gray-500 italic">Belum ada divisi yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Bagian Tim --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Tim</h3>
                    <a href="{{ route('admin.tim.create') }}" 
                        class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow">
                        + Tambah Tim
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($divisis as $divisi)
                        @if($divisi->tims->isNotEmpty())
                            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                                <h4 class="bg-gray-100 px-4 py-2 font-semibold text-gray-900">{{ $divisi->nama_divisi }}</h4>
                                <table class="w-full">
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($divisi->tims as $tim)
                                            <tr class="hover:bg-gray-50 transition-all">
                                                <td class="px-4 py-3 text-gray-900">{{ $tim->nama_tim }}</td>
                                                <td class="px-4 py-3 text-gray-600 text-sm">{{ $tim->pegawais->count() }} Anggota</td>
                                                <td class="px-4 py-3 text-right">
                                                    <div class="flex justify-end gap-2">
                                                        <a href="{{ route('admin.tim.edit', $tim->id) }}" 
                                                            class="bg-gray-800 hover:bg-black text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('admin.tim.destroy', $tim->id) }}" method="POST" onsubmit="return confirm('Hapus tim {{ $tim->nama_tim }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @empty
                        <p class="text-center text-gray-500 py-4 italic">Belum ada tim yang ditambahkan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>