<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Gaji Massal (Langkah 1 dari 2): Pilih Pegawai
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Notifikasi Pegawai Belum Gajian --}}
        @if(isset($pegawaiBelumGajian) && $pegawaiBelumGajian->isNotEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.636-1.21 2.862-1.21 3.498 0l6.234 11.857a2.25 2.25 0 01-1.749 3.544H3.766a2.25 2.25 0 01-1.749-3.544l6.234-11.857zM9 12.5a1 1 0 112 0 1 1 0 01-2 0zm1-4a1 1 0 011 1v2a1 1 0 11-2 0V9.5a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-bold text-yellow-800">
                            Pegawai Belum Menerima Gaji Bulan Ini ({{ $pegawaiBelumGajian->count() }} orang)
                        </h3>
                        <p class="mt-1 text-sm text-yellow-700">Berikut adalah daftar pegawai aktif yang belum memiliki data gaji untuk periode ini. Gunakan filter di bawah untuk memproses gaji mereka.</p>
                        <div class="mt-3 max-h-48 overflow-y-auto border rounded-md bg-white">
                            <table class="w-full text-left text-sm">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2">Nama Pegawai</th>
                                        <th class="px-3 py-2">Jabatan</th>
                                        <th class="px-3 py-2">Tim</th>
                                        <th class="px-3 py-2">Divisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pegawaiBelumGajian as $pegawai)
                                        <tr class="border-b">
                                            <td class="px-3 py-2 font-medium">{{ $pegawai->nama }}</td>
                                            <td class="px-3 py-2">{{ $pegawai->jabatan->nama_jabatan ?? 'N/A' }}</td>
                                            <td class="px-3 py-2">{{ $pegawai->tim->nama_tim ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $pegawai->tim->divisi->nama_divisi ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold border-b pb-2 mb-4">Filter Pegawai</h3>
            <form method="GET" action="{{ route('admin.gaji-massal.langkah1') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end text-sm">
                <input type="hidden" name="filter" value="true">
                
                <div class="lg:col-span-2">
                    <label for="search" class="block font-medium">Cari Nama Pegawai</label>
                    <input type="search" name="search" id="search" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Ketik nama..." value="{{ request('search') }}">
                </div>
                
                <div>
                    <label for="divisi_id" class="block font-medium">Berdasarkan Divisi</label>
                    <select name="divisi_id" id="divisi_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                        <option value="">Pilih Divisi</option>
                        @foreach($divisis as $item)
                        <option value="{{ $item->id }}" @if(request('divisi_id') == $item->id) selected @endif>{{ $item->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tim_id" class="block font-medium">Berdasarkan Tim</label>
                    <select name="tim_id" id="tim_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                        <option value="">Pilih Tim</option>
                        @foreach($tims as $item)
                        <option value="{{ $item->id }}" data-divisi-id="{{ $item->divisi_id }}" @if(request('tim_id') == $item->id) selected @endif>{{ $item->nama_tim }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="jabatan_id" class="block font-medium">Berdasarkan Jabatan</label>
                    <select name="jabatan_id" id="jabatan_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatans as $item)
                        <option value="{{ $item->id }}" @if(request('jabatan_id') == $item->id) selected @endif>{{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end col-span-full lg:col-span-1">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow w-full">Tampilkan Pegawai</button>
                </div>
            </form>
        </div>

        @if($pegawais->isNotEmpty())
        <form method="POST" action="{{ route('admin.gaji-massal.langkah2') }}">
            @csrf
            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                     <div>
                        <label for="bulan" class="block font-medium text-sm text-gray-700">Bulan Penggajian</label>
                        <select name="bulan" id="bulan" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" required>
                            @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="tahun" class="block font-medium text-sm text-gray-700">Tahun Penggajian</label>
                        <input type="number" name="tahun" id="tahun" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" value="{{ date('Y') }}" required>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold border-t pt-4 mt-4">Pilih Pegawai ({{ $pegawais->count() }} ditemukan)</h3>
                <div class="mt-2 border rounded-md max-h-96 overflow-y-auto">
                    @foreach($pegawais as $pegawai)
                    <label class="flex items-center space-x-3 p-3 border-b last:border-b-0 hover:bg-gray-50">
                        <input type="checkbox" name="pegawai_ids[]" value="{{ $pegawai->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm" checked>
                        <div>
                            <span class="font-medium">{{ $pegawai->nama }}</span>
                            <span class="text-sm text-gray-500 block">{{ $pegawai->jabatan->nama_jabatan ?? 'N/A' }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">Lanjutkan ke Langkah 2</button>
                    <a href="{{ route('admin.gaji.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                </div>
            </div>
        </form>
        @elseif(request()->has('filter'))
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500">Tidak ada pegawai yang ditemukan dengan kriteria yang dipilih.</p>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const divisiSelect = document.getElementById('divisi_id');
            const timSelect = document.getElementById('tim_id');
            const allTimOptions = Array.from(timSelect.options);

            divisiSelect.addEventListener('change', function() {
                const selectedDivisiId = this.value;
                timSelect.innerHTML = '';
                timSelect.appendChild(allTimOptions[0]); 

                if (selectedDivisiId) {
                    allTimOptions.forEach(option => {
                        if (option.value && option.dataset.divisiId === selectedDivisiId) {
                            timSelect.appendChild(option.cloneNode(true));
                        }
                    });
                } else {
                    allTimOptions.forEach(option => {
                        if (option.value) {
                           timSelect.appendChild(option.cloneNode(true));
                        }
                    });
                }
            });

            divisiSelect.dispatchEvent(new Event('change'));
            timSelect.value = "{{ request('tim_id') }}";
        });
    </script>
</x-app-layout>