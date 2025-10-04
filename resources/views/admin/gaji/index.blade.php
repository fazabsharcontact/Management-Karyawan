<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Gaji
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        @if(isset($pegawaiBelumGajian) && $pegawaiBelumGajian->isNotEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg shadow">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.636-1.21 2.862-1.21 3.498 0l6.234 11.857a2.25 2.25 0 01-1.749 3.544H3.766a2.25 2.25 0 01-1.749-3.544l6.234-11.857zM9 12.5a1 1 0 112 0 1 1 0 01-2 0zm1-4a1 1 0 011 1v2a1 1 0 11-2 0V9.5a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Pegawai Belum Menerima Gaji Bulan Ini ({{ $pegawaiBelumGajian->count() }} orang)
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($pegawaiBelumGajian as $pegawai)
                                    <li>{{ $pegawai->nama }} ({{ $pegawai->jabatan->nama_jabatan ?? 'N/A' }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-4">
            <form method="GET" action="{{ route('admin.gaji.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Pegawai</label>
                    <input type="text" name="search" id="search" placeholder="Ketik nama..." 
                           class="mt-1 border border-gray-300 rounded-lg p-2 w-full focus:ring focus:ring-blue-200"
                           value="{{ request('search') }}">
                </div>
                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <select name="jabatan" id="jabatan"
                            class="mt-1 border border-gray-300 rounded-lg p-2 w-full focus:ring focus:ring-blue-200">
                        <option value="">Semua Jabatan</option>
                        @foreach($jabatan as $j)
                            <option value="{{ $j->nama_jabatan }}" {{ request('jabatan') == $j->nama_jabatan ? 'selected' : '' }}>
                                {{ $j->nama_jabatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                    <select name="bulan" id="bulan" class="mt-1 border border-gray-300 rounded-lg p-2 w-full focus:ring focus:ring-blue-200">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                    <input type="number" name="tahun" id="tahun" placeholder="Contoh: 2024"
                           class="mt-1 border border-gray-300 rounded-lg p-2 w-full focus:ring focus:ring-blue-200"
                           value="{{ request('tahun') }}">
                </div>
                {{-- --- PERBAIKAN: Grup Tombol Filter & Reset --- --}}
                <div class="col-span-full lg:col-span-1 flex items-center gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow w-full">
                        Filter
                    </button>
                    <a href="{{ route('admin.gaji.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow w-full text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Nama Pegawai</th>
                            <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Jabatan</th>
                            <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Bulan</th>
                            <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Tahun</th>
                            <th class="border-b px-4 py-2 text-left text-sm font-medium text-gray-600">Gaji Bersih</th>
                            <th class="border-b px-4 py-2 text-center text-sm font-medium text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $bulan_nama = [1=>"Januari", 2=>"Februari", 3=>"Maret", 4=>"April", 5=>"Mei", 6=>"Juni", 7=>"Juli", 8=>"Agustus", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Desember"]; @endphp
                        @forelse($gaji as $g)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border-b px-4 py-2 text-gray-800">{{ $g->pegawai?->nama ?? '-' }}</td>
                            <td class="border-b px-4 py-2 text-gray-800">{{ $g->pegawai?->jabatan?->nama_jabatan ?? '-' }}</td>
                            <td class="border-b px-4 py-2 text-gray-800">{{ $bulan_nama[$g->bulan] ?? '-' }}</td>
                            <td class="border-b px-4 py-2 text-gray-800">{{ $g->tahun }}</td>
                            <td class="border-b px-4 py-2 text-gray-800">Rp {{ number_format($g->gaji_bersih, 0, ',', '.') }}</td>
                            <td class="border-b px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.gaji.slip', $g->id) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg text-sm shadow">Slip Gaji</a>
                                    <a href="{{ route('admin.gaji.edit', $g->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm shadow">Edit</a>
                                    <form action="{{ route('admin.gaji.destroy', $g->id) }}" method="POST" class="form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                data-nama="{{ $g->pegawai?->nama ?? 'data' }}"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm shadow">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-gray-500 py-4">Tidak ada data gaji yang ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             <div class="mt-4">{{ $gaji->appends(request()->query())->links('vendor.pagination.tailwind') }}</div>
            <div class="mt-6 flex gap-4">
                <a href="{{ route('admin.gaji.create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">+ Tambah Gaji Individual</a>
                <a href="{{ route('admin.gaji-massal.langkah1') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">+ Tambah Gaji Massal</a>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    </div>
                    <div class="ml-4 text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="delete-message">Apakah Anda yakin?</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirm-delete-yes" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Hapus
                    </button>
                    <button type="button" id="confirm-delete-no" class="mt-3 inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('delete-confirmation-modal');
            const confirmYes = document.getElementById('confirm-delete-yes');
            const confirmNo = document.getElementById('confirm-delete-no');
            const deleteMessage = document.getElementById('delete-message');
            let formToSubmit = null;

            document.querySelectorAll('.form-hapus').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    formToSubmit = this;
                    const nama = this.querySelector('button').dataset.nama;
                    deleteMessage.textContent = `Apakah Anda yakin ingin menghapus data gaji untuk ${nama}? Aksi ini tidak dapat dibatalkan.`;
                    modal.classList.remove('hidden');
                });
            });

            confirmYes.addEventListener('click', function () {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
            });

            confirmNo.addEventListener('click', function () {
                modal.classList.add('hidden');
                formToSubmit = null;
            });
        });
    </script>
</x-app-layout>