<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tugas Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Daftar Tugas</h1>

                @if ($tugas->isEmpty())
                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg">
                        Belum ada tugas untuk Anda.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($tugas as $t)
                            <div class="bg-white shadow rounded-lg p-4 border">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    {{ $t->judul_tugas }}
                                </h2>
                                <p class="text-gray-600 mt-2">
                                    {{ Str::limit($t->deskripsi, 100) }}
                                </p>
                                <div class="text-sm text-gray-500 mt-3 flex justify-between">
                                    <span>
                                        Pemberi: {{ $t->pemberi->nama ?? '-' }}
                                    </span>
                                    <span>
                                        Tenggat: {{ \Carbon\Carbon::parse($t->tenggat_waktu)->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="mt-3 flex justify-between items-center">
                                    <span
                                        class="px-2 py-1 rounded text-sm
                                        @if ($t->status == 'selesai') bg-green-100 text-green-700
                                        @elseif($t->status == 'proses') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                    <a href="{{ route('pegawai.tugas.show', $t->id) }}"
                                        class="text-indigo-600 hover:underline text-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
