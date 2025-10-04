<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tugas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">{{ $tugas->judul_tugas }}</h1>

                <p class="text-gray-700 mb-4">{{ $tugas->deskripsi }}</p>

                <div class="mb-4">
                    <strong>Pemberi:</strong> {{ $tugas->pemberi->nama ?? '-' }}
                </div>
                <div class="mb-4">
                    <strong>Tenggat Waktu:</strong>
                    {{ \Carbon\Carbon::parse($tugas->tenggat_waktu)->format('d M Y H:i') }}
                </div>

                {{-- Status Tugas --}}
                <div class="mb-4">
                    <strong>Status Tugas:</strong>
                    <span
                        class="px-2 py-1 rounded text-sm
                        @if ($tugas->status == 'Selesai') bg-green-100 text-green-700
                        @elseif($tugas->status == 'Dikerjakan') bg-blue-100 text-blue-700
                        @elseif($tugas->status == 'Ditinjau') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ $tugas->status }}
                    </span>
                </div>

                {{-- Status Pengumpulan --}}
                @if ($tugas->pengumpulanTerbaru)
                    <div class="mb-4">
                        <strong>Status Pengumpulan:</strong>
                        <span
                            class="px-2 py-1 rounded text-sm
            @if ($tugas->pengumpulanTerbaru->status == 'pending') bg-gray-100 text-gray-700
            @elseif($tugas->pengumpulanTerbaru->status == 'revisi') bg-red-100 text-red-700
            @elseif($tugas->pengumpulanTerbaru->status == 'diterima') bg-green-100 text-green-700 @endif">
                            {{ ucfirst($tugas->pengumpulanTerbaru->status) }}
                        </span>

                        @if ($tugas->pengumpulanTerbaru->catatan)
                            <p class="mt-1 text-sm text-gray-600">Catatan: {{ $tugas->pengumpulanTerbaru->catatan }}</p>
                        @endif
                    </div>
                @endif

                {{-- Jika status Baru → tombol ubah ke Dikerjakan --}}
                @if ($tugas->status == 'Baru')
                    <form action="{{ route('pegawai.tugas.updateStatus', $tugas->id) }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="status" value="Dikerjakan">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Mulai Kerjakan
                        </button>
                    </form>
                @endif

                {{-- Jika status Dikerjakan atau Revisi → form upload pengumpulan (tapi hilang kalau sudah diterima) --}}
                @if ($tugas->status == 'Dikerjakan' && (!$tugas->pengumpulanTerbaru || $tugas->pengumpulanTerbaru->status != 'diterima'))
                    <form action="{{ route('pegawai.tugas.pengumpulan.store', $tugas->id) }}" method="POST"
                        enctype="multipart/form-data" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">
                                Upload File Tugas
                            </label>
                            <input type="file" id="file" name="file" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700">
                                Catatan (opsional)
                            </label>
                            <textarea id="catatan" name="catatan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Kumpulkan Tugas
                        </button>
                    </form>
                @endif


                {{-- Jika ada pengumpulan, tampilkan detail file --}}
                @if ($tugas->pengumpulanTerbaru)
                    <div class="mt-4">
                        <p>File Terakhir:
                            <a href="{{ asset('storage/' . $tugas->pengumpulanTerbaru->file) }}" target="_blank">
                                {{ basename($tugas->pengumpulanTerbaru->file) }}
                            </a>
                        </p>
                    </div>
                @else
                    <p class="mt-4">Belum ada pengumpulan.</p>
                @endif

                <div class="mt-6">
                    <a href="{{ route('pegawai.tugas.index') }}" class="text-indigo-600 hover:underline">
                        &larr; Kembali ke daftar tugas
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
