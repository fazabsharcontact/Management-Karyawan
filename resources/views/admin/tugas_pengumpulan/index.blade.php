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
                                    Catatan Pengumpulan: {{ $item->catatan ?? '-' }}
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

                                {{-- Tombol TERIMA (Tetap inline karena tidak butuh input tambahan) --}}
                                @if ($item->status !== 'diterima')
                                    <form action="{{ route('admin.tugas_pengumpulan.update', $item->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="diterima">
                                        <button type="submit"
                                            class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700"
                                            onclick="return confirm('Apakah Anda yakin ingin menerima tugas ini? Status tugas akan diubah menjadi Selesai.')">
                                            ✔ Terima
                                        </button>
                                    </form>
                                @endif

                                {{-- Tombol REVISI (Memicu Modal untuk Catatan) --}}
                                @if ($item->status !== 'diterima')
                                    <button type="button" {{-- Gunakan Alpine.js untuk mengatur action URL dan menampilkan modal --}}
                                        @click="$store.revisionModal.open = true; $store.revisionModal.actionUrl = '{{ route('admin.tugas_pengumpulan.update', $item->id) }}'"
                                        class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                        ✎ Revisi
                                    </button>
                                @endif
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

    {{-- MODAL REVISI DITEMPATKAN DI LUAR DIV CONTAINER UTAMA --}}
    <div x-data="{
        revisionModal: $store.revisionModal,
        // Jika Anda menggunakan Store (seperti di atas), ini akan terisi
    }" x-init="$store.revisionModal = { open: false, actionUrl: '' }">

        {{-- Modal Overlay --}}
        <div x-show="revisionModal.open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">

            {{-- Modal Content --}}
            <div x-show="revisionModal.open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">

                <h3 class="text-lg font-bold mb-4 text-gray-800">Minta Revisi Tugas</h3>

                {{-- FORM AKSI REVISI --}}
                <form x-bind:action="revisionModal.actionUrl" method="POST">
                    @csrf

                    <input type="hidden" name="status" value="revisi">

                    <div class="mb-4">
                        <label for="catatan_revisi" class="block text-sm font-medium text-gray-700">Catatan Revisi
                            (Wajib)</label>
                        {{-- Input catatan yang akan dikirim ke controller --}}
                        <textarea name="catatan" id="catatan_revisi" rows="4" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="revisionModal.open = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-md hover:bg-yellow-700">
                            Kirim Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
