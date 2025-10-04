<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">+ Tambah Jabatan</h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6 max-w-lg mx-auto">
            <form method="POST" action="{{ route('admin.jabatan.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="nama_jabatan" class="block font-medium text-sm text-gray-700">Nama Jabatan</label>
                    <input type="text" name="nama_jabatan" id="nama_jabatan" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('nama_jabatan') }}" required autofocus>
                </div>
                <div>
                    <label for="gaji_awal" class="block font-medium text-sm text-gray-700">Gaji Awal</label>
                    <input type="number" name="gaji_awal" id="gaji_awal" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('gaji_awal') }}" required>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Simpan</button>
                    <a href="{{ route('admin.jabatan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
