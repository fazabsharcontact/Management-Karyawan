<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Pengajuan Cuti
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        <!-- Notifikasi -->
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
             <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                {{ session('error') }}
            </div>
        @endif
        
        {{-- Card untuk Tombol Reset Cuti --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Pengaturan Cuti Tahunan</h3>
            <p class="text-sm text-gray-600 mb-4">Gunakan tombol ini sekali pada awal tahun untuk mereset jatah cuti semua pegawai kembali menjadi 12.</p>
            <form action="{{ route('admin.cuti.resetTahunan') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset sisa cuti semua pegawai menjadi 12? Aksi ini tidak dapat dibatalkan.')">
                @csrf
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow text-sm font-medium">
                    Reset Cuti Tahunan Sekarang
                </button>
            </form>
        </div>

        <!-- Card Daftar Pengajuan Cuti -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Pengajuan Cuti</h3>
            <form method="GET" class="flex flex-wrap items-center gap-3 mb-4">
                <input type="text" name="search" placeholder="Cari nama pegawai..." class="border-gray-300 rounded-md shadow-sm" value="{{ request('search') }}">
                <select name="status" class="border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="Diajukan" @if(request('status') == 'Diajukan') selected @endif>Diajukan</option>
                    <option value="Disetujui" @if(request('status') == 'Disetujui') selected @endif>Disetujui</option>
                    <option value="Ditolak" @if(request('status') == 'Ditolak') selected @endif>Ditolak</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Filter</button>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border-b px-4 py-2 text-left">Nama Pegawai</th>
                            <th class="border-b px-4 py-2 text-left">Tanggal Cuti</th>
                            <th class="border-b px-4 py-2 text-center">Durasi</th>
                            <th class="border-b px-4 py-2 text-left">Keterangan</th>
                            <th class="border-b px-4 py-2 text-center">Status</th>
                            <th class="border-b px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cutis as $cuti)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2">
                                <div>{{ $cuti->pegawai->nama ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $cuti->pegawai->jabatan->nama_jabatan ?? 'N/A' }}</div>
                            </td>
                            <td class="border-b px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}</td>
                            <td class="border-b px-4 py-2 text-center text-sm font-medium">{{ $cuti->durasi_hari_kerja }} hari</td>
                            <td class="border-b px-4 py-2 text-sm">{{ $cuti->keterangan }}</td>
                            <td class="border-b px-4 py-2 text-center">
                                @if($cuti->status == 'Disetujui') <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Disetujui</span>
                                @elseif($cuti->status == 'Ditolak') <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Ditolak</span>
                                @else <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Diajukan</span>
                                @endif
                            </td>
                            <td class="border-b px-4 py-2 text-center">
                                @if($cuti->status == 'Diajukan')
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('admin.cuti.updateStatus', $cuti->id) }}" method="POST" class="inline"> @csrf @method('PATCH') <input type="hidden" name="status" value="Disetujui"><button type="submit" class="text-green-600 hover:text-green-900 text-sm font-medium">Setujui</button></form>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('admin.cuti.updateStatus', $cuti->id) }}" method="POST" class="inline"> @csrf @method('PATCH') <input type="hidden" name="status" value="Ditolak"><button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Tolak</button></form>
                                </div>
                                @else <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-gray-500">Tidak ada pengajuan cuti yang cocok dengan filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                 <div class="mt-4">{{ $cutis->appends(request()->query())->links('vendor.pagination.tailwind') }}</div>
            </div>
        </div>
        
        {{-- --- Tabel Rekapitulasi Sisa Cuti --- --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rekapitulasi Sisa Cuti Pegawai</h3>
             <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border-b px-4 py-2 text-left">Nama Pegawai</th>
                            <th class="border-b px-4 py-2 text-left">Jabatan</th>
                            <th class="border-b px-4 py-2 text-center">Sisa Cuti Tahunan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawais as $pegawai)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2 font-medium">{{ $pegawai->nama }}</td>
                            <td class="border-b px-4 py-2 text-sm">{{ $pegawai->jabatan->nama_jabatan ?? 'N/A' }}</td>
                            <td class="border-b px-4 py-2 text-center font-bold text-lg">
                                {{-- Mengambil sisa cuti dari relasi sisaCuti, dengan default 12 jika belum ada --}}
                                {{ $pegawai->sisaCuti->sisa_cuti ?? 12 }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">Tidak ada data pegawai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $pegawais->appends(request()->query())->links('vendor.pagination.tailwind') }}</div>
            </div>
        </div>
    </div>
</x-app-layout>

