    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Meeting
            </h2>
        </x-slot>

        <div class="p-6">
            <div class="bg-white rounded-lg shadow p-6">
                <!-- Notifikasi -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Daftar Jadwal Meeting</h3>
                    <a href="{{ route('admin.meeting.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow text-sm">
                        + Jadwalkan Meeting Baru
                    </a>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border-b px-4 py-2 text-left">Judul Meeting</th>
                                <th class="border-b px-4 py-2 text-left">Jadwal</th>
                                <th class="border-b px-4 py-2 text-left">Lokasi</th>
                                <th class="border-b px-4 py-2 text-left">Dibuat Oleh</th>
                                <th class="border-b px-4 py-2 text-center">Jumlah Peserta</th>
                                <th class="border-b px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($meetings as $meeting)
                            <tr class="hover:bg-gray-50">
                                <td class="border-b px-4 py-2 font-medium">{{ $meeting->judul }}</td>
                                <td class="border-b px-4 py-2 text-sm">
                                    {{ \Carbon\Carbon::parse($meeting->waktu_mulai)->format('d M Y, H:i') }}
                                </td>
                                <td class="border-b px-4 py-2">{{ $meeting->lokasi }}</td>
                                <td class="border-b px-4 py-2">{{ $meeting->pembuat->nama ?? 'N/A' }}</td>
                                <td class="border-b px-4 py-2 text-center">{{ $meeting->pesertas_count }}</td>
                                <td class="border-b px-4 py-2 text-center" style="width: 150px;">
                                    <a href="{{ route('admin.meeting.edit', $meeting->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.meeting.destroy', $meeting->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus meeting ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">Belum ada meeting yang dijadwalkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $meetings->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
