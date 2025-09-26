<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Pengajuan Cuti
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">
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

            <form method="GET" class="flex flex-wrap items-center gap-3 mb-4">
                <input type="text" name="search" placeholder="Cari nama pegawai..."
                       class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full md:w-auto"
                       value="{{ request('search') }}">
                <select name="status" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full md:w-auto">
                    <option value="">Semua Status</option>
                    <option value="Diajukan" {{ request('status') == 'Diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">
                    Filter
                </button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border-b px-4 py-2 text-left">Nama Pegawai</th>
                            <th class="border-b px-4 py-2 text-left">Jabatan</th>
                            <th class="border-b px-4 py-2 text-left">Tanggal Cuti</th>
                            <th class="border-b px-4 py-2 text-left">Sisa Cuti</th>
                            <th class="border-b px-4 py-2 text-left">Keterangan</th>
                            <th class="border-b px-4 py-2 text-center">Status</th>
                            <th class="border-b px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cutis as $cuti)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2 font-medium">{{ $cuti->pegawai->nama ?? 'N/A' }}</td>
                            <td class="border-b px-4 py-2 text-sm text-gray-600">{{ $cuti->pegawai->jabatan->nama_jabatan ?? 'N/A' }}</td>
                            <td class="border-b px-4 py-2 text-sm">
                                {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}
                            </td>
                            <td class="border-b px-4 py-2 text-center font-bold">{{ $cuti->pegawai->sisa_cuti_tahunan ?? 'N/A' }}</td>
                            <td class="border-b px-4 py-2 text-sm">{{ $cuti->keterangan }}</td>
                            <td class="border-b px-4 py-2 text-center">
                                @if($cuti->status == 'Disetujui')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Disetujui</span>
                                @elseif($cuti->status == 'Ditolak')
                                    <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Ditolak</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Diajukan</span>
                                @endif
                            </td>
                            <td class="border-b px-4 py-2 text-center">
                                @if($cuti->status == 'Diajukan')
                                <div class="flex justify-center gap-2">
                                     <form action="{{ route('admin.cuti.updateStatus', $cuti->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Disetujui">
                                        <button type="submit" class="text-green-600 hover:text-green-900 text-sm font-medium">Setujui</button>
                                    </form>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('admin.cuti.updateStatus', $cuti->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="Ditolak">
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Tolak</button>
                                    </form>
                                </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada pengajuan cuti yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                 <div class="mt-4">
                    {{ $cutis->appends(request()->query())->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>