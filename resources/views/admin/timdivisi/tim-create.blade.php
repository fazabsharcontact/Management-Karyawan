<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">+ Tambah Tim Baru</h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6 max-w-lg mx-auto">
            <form method="POST" action="{{ route('admin.tim.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="nama_tim" class="block font-medium text-sm text-gray-700">Nama Tim</label>
                    <input type="text" name="nama_tim" id="nama_tim" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('nama_tim') }}" required autofocus>
                </div>
                 <div>
                    <label for="divisi_id" class="block font-medium text-sm text-gray-700">Pilih Divisi</label>
                    <select name="divisi_id" id="divisi_id" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                        <option value="">-- Pilih Divisi Induk --</option>
                        @foreach($divisis as $divisi)
                            <option value="{{ $divisi->id }}" {{ old('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                {{ $divisi->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Simpan</button>
                    <a href="{{ route('admin.tim-divisi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
