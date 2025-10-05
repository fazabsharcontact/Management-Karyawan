<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Tugas Pegawai
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Tombol Aksi Utama & Area Filter --}}
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-bold text-gray-800">Daftar Tugas</h3>
            <button id="open-modal-button" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                + Tambah Tugas Baru
            </button>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
             <form method="GET" action="{{ route('admin.tugas.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 items-end text-sm">
                <div class="lg:col-span-2">
                    <label for="search" class="block font-medium">Cari Judul/Pegawai</label>
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
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow w-full">Filter</button>
                    <a href="{{ route('admin.tugas.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md shadow w-full text-center">Reset</a>
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Tugas --}}
        <div class="bg-white rounded-lg shadow-md border border-gray-100 p-4">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border-b px-4 py-3 text-left text-sm font-medium text-gray-600">Judul Tugas</th>
                            <th class="border-b px-4 py-3 text-left text-sm font-medium text-gray-600">Penerima</th>
                            <th class="border-b px-4 py-3 text-left text-sm font-medium text-gray-600">Pemberi</th>
                            <th class="border-b px-4 py-3 text-left text-sm font-medium text-gray-600">Tenggat</th>
                            <th class="border-b px-4 py-3 text-center text-sm font-medium text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tugas as $t)
                        <tr class="hover:bg-gray-50">
                            <td class="border-b px-4 py-2 text-gray-800 font-medium">{{ $t->judul_tugas }}</td>
                            <td class="border-b px-4 py-2 text-gray-700">
                                {{ $t->penerima->nama ?? '-' }}
                                <span class="text-xs text-gray-500 block">{{ $t->penerima->jabatan->nama_jabatan ?? '' }}</span>
                            </td>
                            <td class="border-b px-4 py-2 text-gray-700">{{ $t->pemberi->nama ?? 'N/A' }}</td>
                            <td class="border-b px-4 py-2 text-sm text-gray-600">{{ \Carbon\Carbon::parse($t->tenggat_waktu)->format('d M Y, H:i') }}</td>
                            <td class="border-b px-4 py-2 text-center">
                                 <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if ($t->status == 'Selesai') bg-green-100 text-green-800
                                    @elseif($t->status == 'Dikerjakan') bg-blue-100 text-blue-800
                                    @elseif($t->status == 'Ditinjau') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $t->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">Tidak ada data tugas yang cocok dengan kriteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-4">
                {{ $tugas->links() }}
            </div>
        </div>
    </div>

    {{-- Modal untuk Tambah Tugas --}}
    <div id="add-task-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-xl font-semibold text-gray-900">Form Tambah Tugas Baru</h3>
                <button id="close-modal-button" class="text-gray-400 hover:text-gray-600 text-3xl font-light">&times;</button>
            </div>
            <div class="mt-5">
                <form action="{{ route('admin.tugas.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="judul_tugas_modal" class="block text-sm font-medium text-gray-700">Judul Tugas</label>
                        <input type="text" id="judul_tugas_modal" name="judul_tugas" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label for="deskripsi_modal" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="deskripsi_modal" name="deskripsi" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            {{-- ====================================================== --}}
                            {{--           PERUBAHAN UTAMA: SEARCHABLE SELECT           --}}
                            {{-- ====================================================== --}}
                            <label for="search-pegawai-input" class="block text-sm font-medium text-gray-700">Penerima Tugas</label>
                            <div class="relative mt-1">
                                <input type="text" id="search-pegawai-input" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ketik nama pegawai..." autocomplete="off">
                                <input type="hidden" name="penerima_id" id="penerima_id_hidden" required>
                                
                                <div id="pegawai-dropdown-list" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 max-h-40 overflow-y-auto hidden shadow-lg">
                                    @foreach ($pegawais as $p)
                                        <div class="cursor-pointer p-2 hover:bg-gray-100 pegawai-item" data-id="{{ $p->id }}" data-nama="{{ $p->nama }}">
                                            {{ $p->nama }}
                                        </div>
                                    @endforeach
                                    <div id="no-pegawai-found" class="p-2 text-center text-gray-500 hidden">
                                        Pegawai tidak ditemukan.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="tenggat_waktu_modal" class="block text-sm font-medium text-gray-700">Tenggat Waktu</label>
                            <input type="datetime-local" id="tenggat_waktu_modal" name="tenggat_waktu" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4 border-t mt-4 space-x-2">
                        <button type="button" id="cancel-modal-button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika untuk modal utama
            const modal = document.getElementById('add-task-modal');
            const openModalButton = document.getElementById('open-modal-button');
            const closeModalButton = document.getElementById('close-modal-button');
            const cancelModalButton = document.getElementById('cancel-modal-button');

            const openModal = () => modal.classList.remove('hidden');
            const closeModal = () => {
                modal.classList.add('hidden');
                // Reset form searchable select saat modal ditutup
                document.getElementById('search-pegawai-input').value = '';
                document.getElementById('penerima_id_hidden').value = '';
                document.getElementById('pegawai-dropdown-list').classList.add('hidden');
            };

            openModalButton.addEventListener('click', openModal);
            closeModalButton.addEventListener('click', closeModal);
            cancelModalButton.addEventListener('click', closeModal);

            // ====================================================== //
            //      JAVASCRIPT BARU UNTUK SEARCHABLE SELECT           //
            // ====================================================== //
            const searchInput = document.getElementById('search-pegawai-input');
            const hiddenInput = document.getElementById('penerima_id_hidden');
            const dropdown = document.getElementById('pegawai-dropdown-list');
            const noResult = document.getElementById('no-pegawai-found');
            const pegawaiItems = document.querySelectorAll('.pegawai-item');

            // Tampilkan dropdown saat input diklik/fokus
            searchInput.addEventListener('focus', () => dropdown.classList.remove('hidden'));

            // Logika pencarian
            searchInput.addEventListener('keyup', () => {
                const filter = searchInput.value.toLowerCase();
                let found = false;
                pegawaiItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        item.style.display = '';
                        found = true;
                    } else {
                        item.style.display = 'none';
                    }
                });
                noResult.style.display = found ? 'none' : 'block';
            });

            // Logika saat memilih item dari dropdown
            pegawaiItems.forEach(item => {
                item.addEventListener('click', () => {
                    searchInput.value = item.dataset.nama; // Tampilkan nama di input
                    hiddenInput.value = item.dataset.id;   // Simpan ID di input hidden
                    dropdown.classList.add('hidden');      // Sembunyikan dropdown
                });
            });

            // Sembunyikan dropdown jika klik di luar komponen
            window.addEventListener('click', function(event) {
                if (!searchInput.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>

