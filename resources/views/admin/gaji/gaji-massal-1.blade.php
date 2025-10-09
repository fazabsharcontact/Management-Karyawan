<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">

                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                    Tambah Gaji Massal (Langkah 1 dari 2): Pilih Pegawai
                </h2>

                {{-- Notifikasi Pegawai Belum Gajian --}}
                @if(isset($pegawaiBelumGajian) && $pegawaiBelumGajian->isNotEmpty())
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg shadow-sm">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-yellow-500 mt-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.636-1.21 2.862-1.21 3.498 0l6.234 11.857a2.25 2.25 0 01-1.749 3.544H3.766a2.25 2.25 0 01-1.749-3.544l6.234-11.857zM9 12.5a1 1 0 112 0 1 1 0 01-2 0zm1-4a1 1 0 011 1v2a1 1 0 11-2 0V9.5a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-yellow-800">
                                    Pegawai Belum Menerima Gaji Bulan Ini ({{ $pegawaiBelumGajian->count() }} orang)
                                </h3>
                                <p class="mt-1 text-sm text-yellow-700">Gunakan filter di bawah untuk memproses gaji mereka.</p>
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
                                                <tr class="border-b hover:bg-gray-50 transition">
                                                    <td class="px-3 py-2 font-medium text-gray-800">{{ $pegawai->nama }}</td>
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

                {{-- Filter Pegawai --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h3 class="text-lg font-semibold border-b border-gray-200 pb-2 mb-4">Filter Pegawai</h3>
                    <form method="GET" action="{{ route('admin.gaji-massal.langkah1') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end text-sm">
                        <input type="hidden" name="filter" value="true">
                        
                        <div class="lg:col-span-2">
                            <label for="search" class="block font-medium text-gray-700">Cari Nama Pegawai</label>
                            <input type="search" name="search" id="search" class="border-gray-300 rounded-md shadow-sm mt-1 w-full focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik nama..." value="{{ request('search') }}">
                        </div>
                        
                        <div>
                            <label for="divisi_id" class="block font-medium text-gray-700">Berdasarkan Divisi</label>
                            <select name="divisi_id" id="divisi_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                                <option value="">Pilih Divisi</option>
                                @foreach($divisis as $item)
                                <option value="{{ $item->id }}" @if(request('divisi_id') == $item->id) selected @endif>{{ $item->nama_divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="tim_id" class="block font-medium text-gray-700">Berdasarkan Tim</label>
                            <select name="tim_id" id="tim_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                                <option value="">Pilih Tim</option>
                                @foreach($tims as $item)
                                <option value="{{ $item->id }}" data-divisi-id="{{ $item->divisi_id }}" @if(request('tim_id') == $item->id) selected @endif>{{ $item->nama_tim }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="jabatan_id" class="block font-medium text-gray-700">Berdasarkan Jabatan</label>
                            <select name="jabatan_id" id="jabatan_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full">
                                <option value="">Pilih Jabatan</option>
                                @foreach($jabatans as $item)
                                <option value="{{ $item->id }}" @if(request('jabatan_id') == $item->id) selected @endif>{{ $item->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end col-span-full lg:col-span-1">
                            <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg shadow transition duration-150 w-full">
                                Tampilkan Pegawai
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Daftar Pegawai --}}
                @if($pegawais->isNotEmpty())
                <form id="form-langkah-1" method="POST" action="{{ route('admin.gaji-massal.langkah2') }}">
                    @csrf
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="bulan" class="block font-medium text-gray-700">Bulan Penggajian</label>
                                <select name="bulan" id="bulan" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" required>
                                    @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="tahun" class="block font-medium text-gray-700">Tahun Penggajian</label>
                                <input type="number" name="tahun" id="tahun" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" value="{{ date('Y') }}" required>
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold border-t border-gray-200 pt-4 mt-4 text-gray-800">
                            Pilih Pegawai ({{ $pegawais->count() }} ditemukan)
                        </h3>
                        <div class="mt-2 border rounded-md max-h-96 overflow-y-auto divide-y divide-gray-100 bg-white">
                            @foreach($pegawais as $pegawai)
                            <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 transition">
                                <input type="checkbox" name="pegawai_ids[]" value="{{ $pegawai->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm" checked>
                                <div>
                                    <span class="font-medium text-gray-900">{{ $pegawai->nama }}</span>
                                    <span class="text-sm text-gray-500 block">{{ $pegawai->jabatan->nama_jabatan ?? 'N/A' }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <button type="submit" id="submit-button" class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg shadow transition">
                                Lanjutkan ke Langkah 2
                            </button>
                            <a href="{{ route('admin.gaji.index') }}" class="text-sm text-gray-600 hover:text-gray-800 hover:underline transition">Batal</a>
                        </div>
                    </div>
                </form>
                @elseif(request()->has('filter'))
                <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm p-6 text-center text-gray-500">
                    Tidak ada pegawai yang ditemukan dengan kriteria yang dipilih.
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi --}}
    <div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg space-y-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Lanjutkan</h3>
                    <p class="text-sm text-gray-600 mt-1" id="modal-message">Apakah Anda yakin ingin melanjutkan?</p>
                    <div id="warning-pegawai" class="hidden mt-3">
                        <p class="text-sm font-semibold text-red-700">Peringatan: Pegawai berikut sudah memiliki data gaji pada periode ini.</p>
                        <ul id="pegawai-list" class="mt-2 list-disc pl-5 text-sm text-red-600 max-h-32 overflow-y-auto"></ul>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-3 border-t">
                <button type="button" id="confirm-no" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="button" id="confirm-yes" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg shadow transition">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const divisiSelect = document.getElementById('divisi_id');
            const timSelect = document.getElementById('tim_id');
            const allTimOptions = Array.from(timSelect.options);

            divisiSelect.addEventListener('change', function() {
                const selectedDivisiId = this.value;
                timSelect.innerHTML = '';
                timSelect.appendChild(allTimOptions[0]);
                allTimOptions.forEach(option => {
                    if (!selectedDivisiId || option.dataset.divisiId === selectedDivisiId) {
                        timSelect.appendChild(option.cloneNode(true));
                    }
                });
            });
            divisiSelect.dispatchEvent(new Event('change'));
            timSelect.value = "{{ request('tim_id') }}";

            const form = document.getElementById('form-langkah-1');
            const modal = document.getElementById('confirmation-modal');
            const confirmYes = document.getElementById('confirm-yes');
            const confirmNo = document.getElementById('confirm-no');
            const modalMessage = document.getElementById('modal-message');
            const warningPegawai = document.getElementById('warning-pegawai');
            const pegawaiList = document.getElementById('pegawai-list');

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const pegawaiIds = formData.getAll('pegawai_ids[]');
                    if (pegawaiIds.length === 0) {
                        alert('Anda harus memilih setidaknya satu pegawai.');
                        return;
                    }
                    modalMessage.textContent = `Anda akan memproses gaji untuk ${pegawaiIds.length} pegawai. Apakah Anda yakin?`;
                    warningPegawai.classList.add('hidden');
                    pegawaiList.innerHTML = '';
                    modal.classList.remove('hidden');

                    try {
                        const response = await fetch("{{ route('admin.gaji-massal.cek') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                pegawai_ids: pegawaiIds,
                                bulan: formData.get('bulan'),
                                tahun: formData.get('tahun')
                            })
                        });
                        const result = await response.json();
                        if (result.data && result.data.length > 0) {
                            warningPegawai.classList.remove('hidden');
                            result.data.forEach(pegawai => {
                                const li = document.createElement('li');
                                li.textContent = `${pegawai.nama} (${pegawai.jabatan?.nama_jabatan || ''})`;
                                pegawaiList.appendChild(li);
                            });
                        }
                    } catch (error) {
                        console.error('Gagal melakukan pengecekan:', error);
                        alert('Terjadi kesalahan saat memeriksa data.');
                        modal.classList.add('hidden');
                    }
                });
            }

            confirmYes.addEventListener('click', () => form && form.submit());
            confirmNo.addEventListener('click', () => modal.classList.add('hidden'));
        });
    </script>
</x-app-layout>