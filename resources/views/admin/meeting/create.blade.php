<x-app-layout>
    <div class="min-h-screen bg-white p-10">
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Jadwalkan Meeting Baru</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Isi detail meeting dan tentukan peserta serta penanggung jawabnya dengan jelas.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.meeting.store') }}" class="space-y-6">
                    @csrf

                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
                            Judul Meeting
                        </label>
                        <input type="text" name="judul" id="judul"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                            value="{{ old('judul') }}" placeholder="Masukkan judul meeting..." required autofocus>
                    </div>

                    <!-- Waktu -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-1">
                                Waktu Mulai
                            </label>
                            <input type="datetime-local" name="waktu_mulai" id="waktu_mulai"
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                                value="{{ old('waktu_mulai') }}" required>
                        </div>
                        <div>
                            <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-1">
                                Waktu Selesai
                            </label>
                            <input type="datetime-local" name="waktu_selesai" id="waktu_selesai"
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                                value="{{ old('waktu_selesai') }}" required>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">
                            Lokasi
                        </label>
                        <input type="text" name="lokasi" id="lokasi"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                            placeholder="Masukkan lokasi meeting..." value="{{ old('lokasi') }}" required>
                    </div>

                    <!-- Penanggung Jawab -->
                    <div>
                        <label for="pembuat_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Penanggung Jawab
                        </label>
                        <select name="pembuat_id" id="pembuat_id"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                            required>
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            @foreach($pegawais as $pegawai)
                                <option value="{{ $pegawai->id }}" {{ old('pembuat_id') == $pegawai->id ? 'selected' : '' }}>
                                    {{ $pegawai->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                            Deskripsi (Opsional)
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="5"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                            placeholder="Tulis deskripsi meeting...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- Peserta -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peserta Meeting</label>
                        <input type="search" id="peserta-search"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2 mb-3"
                            placeholder="Cari pegawai...">
                        <div id="peserta-list"
                            class="border border-gray-200 rounded-lg max-h-60 overflow-y-auto">
                            @foreach($pegawais as $pegawai)
                                <label class="peserta-item flex items-center gap-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                                    <input type="checkbox" name="peserta_ids[]" value="{{ $pegawai->id }}"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm text-gray-700">{{ $pegawai->nama }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.meeting.index') }}"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-lg text-sm font-medium">
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
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
                    checkbox.disabled = false;
                    if (checkbox.value === pembuatId) {
                        checkbox.checked = true;
                        checkbox.disabled = true;
                    }
                });
            }

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                pesertaItems.forEach(item => {
                    const nama = item.querySelector('span').textContent.toLowerCase();
                    item.style.display = nama.includes(searchTerm) ? 'flex' : 'none';
                });
            });

            pembuatSelect.addEventListener('change', syncPembuatToPeserta);
            syncPembuatToPeserta();
        });
    </script>
</x-app-layout>