<x-app-layout>
    <div class="p-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                Review Pengumpulan Tugas
            </h2>
            <p class="text-sm text-gray-500 mt-1">Lihat, terima, atau beri revisi pada pengumpulan tugas pegawai</p>

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

            {{-- Filter dan Pencarian --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <form method="GET" action="{{ route('admin.tugas_pengumpulan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 items-end text-sm">
                    <div class="lg:col-span-2">
                        <label for="search" class="block font-medium text-gray-800">Cari Tugas/Pegawai</label>
                        <input type="search" name="search" id="search" value="{{ request('search') }}" placeholder="Ketik untuk mencari..." class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="bulan" class="block font-medium text-gray-800">Bulan</label>
                        <select name="bulan" id="bulan" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="tahun" class="block font-medium text-gray-800">Tahun</label>
                        <select name="tahun" id="tahun" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua</option>
                            @for ($i = date('Y'); $i >= date('Y') - 3; $i--)
                                <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="jabatan_id" class="block font-medium text-gray-800">Jabatan</label>
                        <select name="jabatan_id" id="jabatan_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua</option>
                            @foreach($jabatans as $item)
                                <option value="{{ $item->id }}" {{ request('jabatan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="divisi_id" class="block font-medium text-gray-800">Divisi</label>
                        <select name="divisi_id" id="divisi_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua</option>
                            @foreach($divisis as $item)
                                <option value="{{ $item->id }}" {{ request('divisi_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block font-medium text-gray-800">Status</label>
                        <select name="status" id="status" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2 col-span-full sm:col-span-2">
                        <button type="submit" class="bg-gray-900 hover:bg-black text-white font-medium py-2 px-4 rounded-xl shadow w-full transition-all duration-200">Filter</button>
                        <a href="{{ route('admin.tugas_pengumpulan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-xl shadow w-full text-center transition-all duration-200">Reset</a>
                    </div>
                </form>
            </div>

            {{-- Tabel Pengumpulan --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <tr>
                                <th class="border-b px-4 py-3 text-left">Judul Tugas</th>
                                <th class="border-b px-4 py-3 text-left">Pegawai</th>
                                <th class="border-b px-4 py-3 text-left">File</th>
                                <th class="border-b px-4 py-3 text-left">Tanggal</th>
                                <th class="border-b px-4 py-3 text-center">Status</th>
                                <th class="border-b px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pengumpulan as $item)
                                <tr class="hover:bg-gray-50 transition-all">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $item->tugas->judul_tugas ?? 'Tugas Dihapus' }}</td>
                                    <td class="px-4 py-3 text-gray-800">{{ $item->pegawai->nama ?? 'Pegawai Dihapus' }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ asset('storage/' . $item->file) }}" target="_blank" class="text-indigo-600 hover:underline text-sm">
                                            {{ basename($item->file) }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-sm">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($item->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($item->status == 'diterima') bg-green-100 text-green-800
                                            @elseif($item->status == 'revisi') bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($item->status == 'pending')
                                            <div class="flex justify-center gap-2">
                                                <form action="{{ route('admin.tugas_pengumpulan.update', $item->id) }}" method="POST" onsubmit="return confirm('Terima tugas ini?')">
                                                    @csrf
                                                    <input type="hidden" name="status" value="diterima">
                                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                        Terima
                                                    </button>
                                                </form>
                                                <button type="button" 
                                                    class="revisi-button bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200"
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
                                    <td colspan="6" class="text-center py-6 text-gray-500 italic">Tidak ada pengumpulan tugas yang cocok dengan kriteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $pengumpulan->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Catatan Revisi --}}
    <div id="revisi-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 w-full max-w-lg">
            <h3 class="text-xl font-bold text-gray-900 mb-3">Catatan Revisi</h3>
            <form id="revisi-form" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="status" value="revisi">
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-800 mb-1">Tuliskan catatan revisi:</label>
                    <textarea name="catatan" id="catatan" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="close-modal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-xl shadow transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl shadow transition-all duration-200">
                        Kirim Revisi
                    </button>
                </div>
            </form>
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
                    revisiForm.setAttribute('action', this.dataset.action);
                    modal.classList.remove('hidden');
                });
            });

            function closeModal() {
                modal.classList.add('hidden');
                revisiForm.reset();
            }

            closeModalButton.addEventListener('click', closeModal);
            window.addEventListener('click', e => { if (e.target === modal) closeModal(); });
        });
    </script>
</x-app-layout>