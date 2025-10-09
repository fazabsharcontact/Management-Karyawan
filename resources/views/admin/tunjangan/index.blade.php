<x-app-layout>
    <div class="p-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                Manajemen Tunjangan & Potongan
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data jenis tunjangan dan potongan karyawan dengan mudah</p>

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

            {{-- Bagian Master Tunjangan --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Jenis Tunjangan</h3>
                    <a href="{{ route('admin.master-tunjangan.create') }}" 
                        class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow">
                        + Tambah Tunjangan
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <tr>
                                <th class="border-b px-4 py-3 text-left">Nama Tunjangan</th>
                                <th class="border-b px-4 py-3 text-left">Deskripsi</th>
                                <th class="border-b px-4 py-3 text-left">Jumlah Default</th>
                                <th class="border-b px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($masterTunjangans as $tunjangan)
                                <tr class="hover:bg-gray-50 transition-all">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $tunjangan->nama_tunjangan }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $tunjangan->deskripsi ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        Rp {{ number_format($tunjangan->jumlah_default ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.master-tunjangan.edit', $tunjangan->id) }}" 
                                                class="bg-gray-800 hover:bg-black text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.master-tunjangan.destroy', $tunjangan->id) }}" method="POST" onsubmit="return confirm('Hapus jenis tunjangan {{ $tunjangan->nama_tunjangan }}?')">
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
                                    <td colspan="4" class="text-center py-5 text-gray-500 italic">Belum ada jenis tunjangan yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $masterTunjangans->links('vendor.pagination.tailwind') }}
                </div>
            </div>

            {{-- Bagian Master Potongan --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Jenis Potongan</h3>
                    <a href="{{ route('admin.master-potongan.create') }}" 
                        class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow">
                        + Tambah Potongan
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <tr>
                                <th class="border-b px-4 py-3 text-left">Nama Potongan</th>
                                <th class="border-b px-4 py-3 text-left">Deskripsi</th>
                                <th class="border-b px-4 py-3 text-left">Jumlah Default</th>
                                <th class="border-b px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($masterPotongans as $potongan)
                                <tr class="hover:bg-gray-50 transition-all">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $potongan->nama_potongan }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $potongan->deskripsi ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        Rp {{ number_format($potongan->jumlah_default ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.master-potongan.edit', $potongan->id) }}" 
                                                class="bg-gray-800 hover:bg-black text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.master-potongan.destroy', $potongan->id) }}" method="POST" onsubmit="return confirm('Hapus jenis potongan {{ $potongan->nama_potongan }}?')">
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
                                    <td colspan="4" class="text-center py-5 text-gray-500 italic">Belum ada jenis potongan yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $masterPotongans->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>