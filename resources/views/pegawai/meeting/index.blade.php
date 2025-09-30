<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Meeting
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            @forelse($meetings as $meeting)
                <div class="border rounded-lg p-4">
                    <h3 class="font-bold text-lg">{{ $meeting->judul }}</h3>
                    <p class="text-sm text-gray-500">
                        Dibuat oleh: {{ $meeting->pembuat->nama ?? '-' }} |
                        Peserta: {{ $meeting->pesertas_count }}
                    </p>
                    <p class="text-sm">
                        Waktu: {{ \Carbon\Carbon::parse($meeting->waktu_mulai)->format('d M Y H:i') }}
                        - {{ \Carbon\Carbon::parse($meeting->waktu_selesai)->format('d M Y H:i') }}
                    </p>
                    <p class="text-sm">Lokasi: {{ $meeting->lokasi }}</p>
                    <a href="{{ route('pegawai.meeting.show', $meeting->id) }}"
                        class="text-blue-600 hover:underline text-sm">Detail</a>
                </div>
            @empty
                <p class="text-gray-500 text-center">Belum ada meeting yang dijadwalkan.</p>
            @endforelse

            <div class="mt-4">
                {{ $meetings->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>
