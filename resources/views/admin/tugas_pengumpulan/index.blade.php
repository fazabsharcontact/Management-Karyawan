<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Pengumpulan Tugas
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Daftar Pengumpulan</h3>
            </div>

            <div class="space-y-4">
                @forelse($pengumpulan as $item)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-lg text-gray-800">
                                    {{ $item->tugas->judul_tugas }}
                                </h4>
                                <p class="text-xs text-gray-500">
                                    Oleh <span class="font-medium">{{ $item->pegawai->user->username ?? '-' }}</span>
                                    pada {{ $item->created_at->format('d M Y, H:i') }}
                                </p>
                                <p class="mt-1 text-sm text-gray-600">
                                    Catatan: {{ $item->catatan ?? '-' }}
                                </p>
                                <p class="mt-1 text-sm">
                                    Status:
                                    <span
                                        class="px-2 py-1 text-xs rounded
                                    @if ($item->status == 'pending') bg-gray-100 text-gray-700
                                    @elseif($item->status == 'diterima') bg-green-100 text-green-700
                                    @elseif($item->status == 'revisi') bg-yellow-100 text-yellow-700 @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ asset('storage/' . $item->file) }}" target="_blank"
                                    class="text-blue-600 hover:underline text-sm">Lihat File</a>

                                {{-- Tombol aksi admin --}}
                                <form action="{{ route('admin.tugas_pengumpulan.update', $item->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="diterima">
                                    <button type="submit"
                                        class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                        ✔ Terima
                                    </button>
                                </form>
                                <form action="{{ route('admin.tugas_pengumpulan.update', $item->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="revisi">
                                    <button type="submit"
                                        class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                        ✎ Revisi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <p>Belum ada pengumpulan tugas.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
