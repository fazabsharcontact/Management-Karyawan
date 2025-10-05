<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Pengumpulan Tugas
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Area Filter dan Pencarian --}}
        <div class="bg-white p-4 rounded-lg shadow">
            <form method="GET" action="{{ route('admin.tugas_pengumpulan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 items-end text-sm">
                <div class="lg:col-span-2">
                    <label for="search" class="block font-medium">Cari Tugas/Pegawai</label>
                    <input type="search" name="search" id="search" value="{{ request('search') }}" placeholder="Ketik untuk mencari..." class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="bulan" class="block font-medium">Bulan</label>
                    <select name="bulan" id="bulan" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Semua</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block font-medium">Tahun</label>
                    <select name="tahun" id="tahun" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Semua</option>
                        @for ($i = date('Y'); $i >= date('Y') - 3; $i--)
                            <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="jabatan_id" class="block font-medium">Jabatan</label>
                    <select name="jabatan_id" id="jabatan_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Semua</option>
                        @foreach($jabatans as $item)
                            <option value="{{ $item->id }}" {{ request('jabatan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label for="divisi_id" class="block font-medium">Divisi</label>
                    <select name="divisi_id" id="divisi_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Semua</option>
                        @foreach($divisis as $item)
                            <option value="{{ $item->id }}" {{ request('divisi_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block font-medium">Status</label>
                    <select name="status" id="status" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Semua</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow w-full">Filter</button>
                    <a href="{{ route('admin.tugas_pengumpulan.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md shadow w-full text-center">Reset</a>
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Pengumpulan --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border-b px-4 py-3 text-left text-sm font-medium text-gray-600">Judul Tugas</th>
                            <th class="border-b px-4 py-3 text-left text-sm font-medium text-gray-600">Pegawai</th>
                            <th class="border-b px-4 py-3 text-left text-sm font-medium text-gray-600">File</th>
                            <th class="border-b px-4 py-3 text-left text-sm font-medium text-gray-600">Tanggal</th>
                            <th class="border-b px-4 py-3 text-center text-sm font-medium text-gray-600">Status</th>
                            <th class="border-b px-4 py-3 text-center text-sm font-medium text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengumpulan as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border-b px-4 py-2 text-gray-800 font-medium">{{ $item->tugas->judul_tugas ?? 'Tugas Dihapus' }}</td>
                            <td class="border-b px-4 py-2 text-gray-800">{{ $item->pegawai->nama ?? 'Pegawai Dihapus' }}</td>
                            <td class="border-b px-4 py-2">
                                <a href="{{ asset('storage/' . $item->file) }}" target="_blank" class="text-indigo-600 hover:underline text-sm">
                                    {{ basename($item->file) }}
                                </a>
                            </td>
                            <td class="border-b px-4 py-2 text-gray-600 text-sm">{{ $item->created_at->format('d M Y, H:i') }}</td>
                            <td class="border-b px-4 py-2 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($item->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($item->status == 'diterima') bg-green-100 text-green-800
                                    @elseif($item->status == 'revisi') bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="border-b px-4 py-2">
                                @if($item->status == 'pending')
                                <div class="flex justify-center items-center gap-2">
                                    <form action="{{ route('admin.tugas_pengumpulan.update', $item->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menerima tugas ini?')">
                                        @csrf
                                        <input type="hidden" name="status" value="diterima">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs shadow">
                                            Terima
                                        </button>
                                    </form>
                                    <button type="button" 
                                            class="revisi-button bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs shadow"
                                            data-action="{{ route('admin.tugas_pengumpulan.update', $item->id) }}">
                                        Revisi
                                    </button>
                                </div>
                                @else
                                <span class="text-gray-400 text-xs italic">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">Tidak ada pengumpulan tugas yang cocok dengan kriteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-4">
                {{ $pengumpulan->links() }}
            </div>
        </div>
    </div>

    {{-- Modal untuk Form Catatan Revisi --}}
    <div id="revisi-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Catatan Revisi</h3>
                <form id="revisi-form" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="status" value="revisi">
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700">Tuliskan catatan revisi untuk pegawai:</label>
                        <textarea name="catatan" id="catatan" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                    </div>
                    <div class="items-center px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Kirim Revisi
                        </button>
                        <button type="button" id="close-modal" class="mt-3 inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('revisi-modal');
            const revisiForm = document.getElementById('revisi-form');
            const closeModalButton = document.getElementById('close-modal');
            const revisiButtons = document.querySelectorAll('.revisi-button');

            revisiButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const actionUrl = this.dataset.action;
                    revisiForm.setAttribute('action', actionUrl);
                    modal.classList.remove('hidden');
                });
            });

            function closeModal() {
                modal.classList.add('hidden');
                revisiForm.reset();
            }

            closeModalButton.addEventListener('click', closeModal);

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            });
        });
    </script>
</x-app-layout>