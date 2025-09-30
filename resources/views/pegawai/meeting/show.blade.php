<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Meeting
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">{{ $meeting->judul }}</h1>
                <p class="text-gray-700 mb-2">{{ $meeting->deskripsi ?? '-' }}</p>

                <p class="mb-2"><strong>Pembuat:</strong> {{ $meeting->pembuat->nama ?? '-' }}</p>
                <p class="mb-2">
                    <strong>Waktu:</strong>
                    {{ \Carbon\Carbon::parse($meeting->waktu_mulai)->format('d M Y H:i') }}
                    - {{ \Carbon\Carbon::parse($meeting->waktu_selesai)->format('d M Y H:i') }}
                </p>
                <p class="mb-2"><strong>Lokasi:</strong> {{ $meeting->lokasi }}</p>

                <div class="mt-4">
                    <strong>Peserta:</strong>
                    <ul class="list-disc list-inside">
                        @forelse($meeting->pesertas as $peserta)
                            <li>{{ $peserta->nama }}</li>
                        @empty
                            <li>-</li>
                        @endforelse
                    </ul>
                </div>

                <div class="mt-6">
                    <a href="{{ route('pegawai.meeting.index') }}" class="text-indigo-600 hover:underline">
                        &larr; Kembali ke daftar meeting
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
