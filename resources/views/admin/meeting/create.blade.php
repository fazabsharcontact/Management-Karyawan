<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">+ Jadwalkan Meeting Baru</h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.meeting.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="judul" class="block font-medium text-sm text-gray-700">Judul Meeting</label>
                    <input type="text" name="judul" id="judul" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('judul') }}" required autofocus>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="waktu_mulai" class="block font-medium text-sm text-gray-700">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('waktu_mulai') }}" required>
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block font-medium text-sm text-gray-700">Waktu Selesai</label>
                        <input type="datetime-local" name="waktu_selesai" id="waktu_selesai" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('waktu_selesai') }}" required>
                    </div>
                </div>

                 <div>
                    <label for="lokasi" class="block font-medium text-sm text-gray-700">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('lokasi') }}" required>
                </div>
                
                <div>
                    <label for="pembuat_id" class="block font-medium text-sm text-gray-700">Penanggung Jawab</label>
                    <select name="pembuat_id" id="pembuat_id" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                        <option value="">-- Pilih Penanggung Jawab --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}" {{ old('pembuat_id') == $pegawai->id ? 'selected' : '' }}>
                                {{ $pegawai->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="deskripsi" class="block font-medium text-sm text-gray-700">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full h-24">{{ old('deskripsi') }}</textarea>
                </div>
                
                <div>
                    <label class="block font-medium text-sm text-gray-700">Peserta Meeting</label>
                    <div class="mt-1">
                        <input type="search" id="peserta-search" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" placeholder="Ketik untuk mencari pegawai...">
                    </div>
                    <div id="peserta-list" class="mt-2 border border-gray-300 rounded-md max-h-60 overflow-y-auto">
                        @foreach($pegawais as $pegawai)
                        <label class="peserta-item flex items-center space-x-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                            <input type="checkbox" name="peserta_ids[]" value="{{ $pegawai->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span>{{ $pegawai->nama }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Simpan Jadwal</button>
                    <a href="{{ route('admin.meeting.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('peserta-search');
            const pesertaItems = document.querySelectorAll('.peserta-item');
            const pembuatSelect = document.getElementById('pembuat_id');
            const pesertaCheckboxes = document.querySelectorAll('input[name="peserta_ids[]"]');
            function syncPembuatToPeserta() {
                const pembuatId = pembuatSelect.value;

                pesertaCheckboxes.forEach(checkbox => {
                    if (checkbox.value !== pembuatId) {
                        checkbox.disabled = false;
                    }
                });

                if (pembuatId) {
                    const correspondingCheckbox = document.querySelector(`input[name="peserta_ids[]"][value="${pembuatId}"]`);
                    if (correspondingCheckbox) {
                        correspondingCheckbox.checked = true; 
                        correspondingCheckbox.disabled = true; 
                    }
                }
            }
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                pesertaItems.forEach(function(item) {
                    const nama = item.querySelector('span').textContent.toLowerCase();
                    if (nama.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
            pembuatSelect.addEventListener('change', syncPembuatToPeserta);
            syncPembuatToPeserta();
        });
    </script>
</x-app-layout>

