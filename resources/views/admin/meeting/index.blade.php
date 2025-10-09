<x-app-layout>
    <div class="p-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                Manajemen Meeting
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola jadwal meeting dan peserta dengan mudah</p>

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

            {{-- Bagian Meeting --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Jadwal Meeting</h3>
                    <a href="{{ route('admin.meeting.create') }}" 
                        class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow">
                        + Jadwalkan Meeting Baru
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <tr>
                                <th class="border-b px-4 py-3 text-left">Judul Meeting</th>
                                <th class="border-b px-4 py-3 text-left">Jadwal</th>
                                <th class="border-b px-4 py-3 text-left">Lokasi</th>
                                <th class="border-b px-4 py-3 text-left">Dibuat Oleh</th>
                                <th class="border-b px-4 py-3 text-center">Jumlah Peserta</th>
                                <th class="border-b px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($meetings as $meeting)
                                <tr class="hover:bg-gray-50 transition-all">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $meeting->judul }}</td>
                                    <td class="px-4 py-3 text-gray-700 text-sm">
                                        {{ \Carbon\Carbon::parse($meeting->waktu_mulai)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $meeting->lokasi }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $meeting->pembuat->nama ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ $meeting->pesertas_count }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.meeting.edit', $meeting->id) }}" 
                                                class="bg-gray-800 hover:bg-black text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.meeting.destroy', $meeting->id) }}" method="POST" onsubmit="return confirm('Hapus meeting {{ $meeting->judul }}?')">
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
                                    <td colspan="6" class="text-center py-5 text-gray-500 italic">
                                        Belum ada meeting yang dijadwalkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $meetings->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>