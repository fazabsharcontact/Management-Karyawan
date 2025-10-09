<x-app-layout>
    <div class="min-h-screen bg-white p-10">
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Buat Pengumuman Baru</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Isi detail pengumuman dan tentukan target penerimanya dengan jelas.
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

                <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="space-y-6">
                    @csrf

                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
                            Judul Pengumuman
                        </label>
                        <input type="text" name="judul" id="judul"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                            value="{{ old('judul') }}" placeholder="Masukkan judul pengumuman..." required>
                    </div>

                    <!-- Isi -->
                    <div>
                        <label for="isi" class="block text-sm font-medium text-gray-700 mb-1">
                            Isi Pengumuman
                        </label>
                        <textarea name="isi" id="isi" rows="8"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                            placeholder="Tulis isi pengumuman di sini...">{{ old('isi') }}</textarea>
                    </div>

                    <!-- Target Type -->
                    <div>
                        <label for="target_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Tujukan Kepada
                        </label>
                        <select name="target_type" id="target_type"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                            required>
                            <option value="semua" {{ old('target_type') == 'semua' ? 'selected' : '' }}>Semua Pegawai</option>
                            <option value="divisi" {{ old('target_type') == 'divisi' ? 'selected' : '' }}>Divisi Tertentu</option>
                            <option value="tim" {{ old('target_type') == 'tim' ? 'selected' : '' }}>Tim Tertentu</option>
                            <option value="jabatan" {{ old('target_type') == 'jabatan' ? 'selected' : '' }}>Jabatan Tertentu</option>
                            <option value="pegawai" {{ old('target_type') == 'pegawai' ? 'selected' : '' }}>Pegawai Tertentu</option>
                        </select>
                    </div>

                    <!-- Target Spesifik -->
                    <div id="target-spesifik-wrapper" class="hidden space-y-5">
                        <!-- Divisi -->
                        <div id="divisi-container" class="target-container hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Divisi</label>
                            <input type="search" id="divisi-search"
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                                placeholder="Cari divisi...">
                            <div class="mt-3 border border-gray-200 rounded-lg max-h-48 overflow-y-auto">
                                @foreach($divisis as $item)
                                    <label class="target-item flex items-center gap-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                                        <input type="checkbox" name="target_ids[]" value="{{ $item->id }}"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">{{ $item->nama_divisi }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tim -->
                        <div id="tim-container" class="target-container hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Tim</label>
                            <input type="search" id="tim-search"
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                                placeholder="Cari tim...">
                            <div class="mt-3 border border-gray-200 rounded-lg max-h-48 overflow-y-auto">
                                @foreach($tims as $item)
                                    <label class="target-item flex items-center gap-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                                        <input type="checkbox" name="target_ids[]" value="{{ $item->id }}"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">{{ $item->nama_tim }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Jabatan -->
                        <div id="jabatan-container" class="target-container hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Jabatan</label>
                            <input type="search" id="jabatan-search"
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                                placeholder="Cari jabatan...">
                            <div class="mt-3 border border-gray-200 rounded-lg max-h-48 overflow-y-auto">
                                @foreach($jabatans as $item)
                                    <label class="target-item flex items-center gap-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                                        <input type="checkbox" name="target_ids[]" value="{{ $item->id }}"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">{{ $item->nama_jabatan }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pegawai -->
                        <div id="pegawai-container" class="target-container hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pegawai</label>
                            <input type="search" id="pegawai-search"
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg shadow-sm text-sm px-3 py-2"
                                placeholder="Cari pegawai...">
                            <div class="mt-3 border border-gray-200 rounded-lg max-h-48 overflow-y-auto">
                                @foreach($pegawais as $item)
                                    <label class="target-item flex items-center gap-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                                        <input type="checkbox" name="target_ids[]" value="{{ $item->id }}"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">{{ $item->nama }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.pengumuman.index') }}"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-lg text-sm font-medium">
                            Publikasikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const targetTypeSelect = document.getElementById('target_type');
            const wrapper = document.getElementById('target-spesifik-wrapper');
            const containers = {
                divisi: document.getElementById('divisi-container'),
                tim: document.getElementById('tim-container'),
                jabatan: document.getElementById('jabatan-container'),
                pegawai: document.getElementById('pegawai-container'),
            };

            function showCorrectContainer() {
                const selectedType = targetTypeSelect.value;
                Object.values(containers).forEach(container => container.classList.add('hidden'));
                if (selectedType !== 'semua') {
                    wrapper.classList.remove('hidden');
                    if (containers[selectedType]) {
                        containers[selectedType].classList.remove('hidden');
                    }
                } else {
                    wrapper.classList.add('hidden');
                }
            }

            function setupSearch(inputId, containerId) {
                const searchInput = document.getElementById(inputId);
                const items = document.querySelectorAll(`#${containerId} .target-item`);
                searchInput?.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    items.forEach(item => {
                        const text = item.querySelector('span').textContent.toLowerCase();
                        item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
                    });
                });
            }

            targetTypeSelect.addEventListener('change', showCorrectContainer);
            setupSearch('divisi-search', 'divisi-container');
            setupSearch('tim-search', 'tim-container');
            setupSearch('jabatan-search', 'jabatan-container');
            setupSearch('pegawai-search', 'pegawai-container');
            showCorrectContainer();
        });
    </script>
</x-app-layout>