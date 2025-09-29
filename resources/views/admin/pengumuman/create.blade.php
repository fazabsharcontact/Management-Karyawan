<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">+ Buat Pengumuman Baru</h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6 max-w-3xl mx-auto">
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="judul" class="block font-medium text-sm text-gray-700">Judul Pengumuman</label>
                    <input type="text" name="judul" id="judul" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('judul') }}" required>
                </div>
                
                <div>
                    <label for="isi" class="block font-medium text-sm text-gray-700">Isi Pengumuman</label>
                    <textarea name="isi" id="isi" rows="10" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full">{{ old('isi') }}</textarea>
                </div>
                
                <div>
                    <label for="target_type" class="block font-medium text-sm text-gray-700">Tujukan Kepada</label>
                    <select name="target_type" id="target_type" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                        <option value="semua" {{ old('target_type') == 'semua' ? 'selected' : '' }}>Semua Pegawai</option>
                        <option value="divisi" {{ old('target_type') == 'divisi' ? 'selected' : '' }}>Divisi Tertentu</option>
                        <option value="tim" {{ old('target_type') == 'tim' ? 'selected' : '' }}>Tim Tertentu</option>
                        <option value="jabatan" {{ old('target_type') == 'jabatan' ? 'selected' : '' }}>Jabatan Tertentu</option>
                        <option value="pegawai" {{ old('target_type') == 'pegawai' ? 'selected' : '' }}>Pegawai Tertentu</option>
                    </select>
                </div>

                {{-- --- PERUBAHAN UTAMA: DESAIN BARU UNTUK TARGET SPESIFIK --- --}}
                <div id="target-spesifik-wrapper" class="hidden space-y-4">
                    
                    {{-- Kontainer untuk Divisi --}}
                    <div id="divisi-container" class="target-container hidden">
                        <label class="block font-medium text-sm text-gray-700">Pilih Divisi</label>
                        <input type="search" id="divisi-search" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full text-sm" placeholder="Ketik untuk mencari divisi...">
                        <div class="mt-2 border rounded-md max-h-48 overflow-y-auto">
                            @foreach($divisis as $item)
                            <label class="target-item flex items-center space-x-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                                <input type="checkbox" name="target_ids[]" value="{{ $item->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span>{{ $item->nama_divisi }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Kontainer untuk Tim --}}
                    <div id="tim-container" class="target-container hidden">
                         <label class="block font-medium text-sm text-gray-700">Pilih Tim</label>
                        <input type="search" id="tim-search" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full text-sm" placeholder="Ketik untuk mencari tim...">
                        <div class="mt-2 border rounded-md max-h-48 overflow-y-auto">
                            @foreach($tims as $item)
                            <label class="target-item flex items-center space-x-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                                <input type="checkbox" name="target_ids[]" value="{{ $item->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span>{{ $item->nama_tim }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kontainer untuk Jabatan --}}
                    <div id="jabatan-container" class="target-container hidden">
                         <label class="block font-medium text-sm text-gray-700">Pilih Jabatan</label>
                        <input type="search" id="jabatan-search" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full text-sm" placeholder="Ketik untuk mencari jabatan...">
                        <div class="mt-2 border rounded-md max-h-48 overflow-y-auto">
                            @foreach($jabatans as $item)
                            <label class="target-item flex items-center space-x-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                                <input type="checkbox" name="target_ids[]" value="{{ $item->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span>{{ $item->nama_jabatan }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kontainer untuk Pegawai --}}
                    <div id="pegawai-container" class="target-container hidden">
                         <label class="block font-medium text-sm text-gray-700">Pilih Pegawai</label>
                        <input type="search" id="pegawai-search" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full text-sm" placeholder="Ketik untuk mencari pegawai...">
                        <div class="mt-2 border rounded-md max-h-48 overflow-y-auto">
                            @foreach($pegawais as $item)
                            <label class="target-item flex items-center space-x-3 p-2 border-b last:border-b-0 hover:bg-gray-50">
                                <input type="checkbox" name="target_ids[]" value="{{ $item->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span>{{ $item->nama }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- -------------------------------------------------------------------------- --}}

                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Publikasikan</button>
                    <a href="{{ route('admin.pengumuman.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">Batal</a>
                </div>
            </form>
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
                
                // Sembunyikan semua kontainer dulu
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

                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    items.forEach(function(item) {
                        const text = item.querySelector('span').textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            targetTypeSelect.addEventListener('change', showCorrectContainer);
            
            // Siapkan fungsi pencarian untuk setiap kontainer
            setupSearch('divisi-search', 'divisi-container');
            setupSearch('tim-search', 'tim-container');
            setupSearch('jabatan-search', 'jabatan-container');
            setupSearch('pegawai-search', 'pegawai-container');

            // Panggil fungsi sekali saat halaman dimuat untuk set state awal
            showCorrectContainer();
        });
    </script>
</x-app-layout>
