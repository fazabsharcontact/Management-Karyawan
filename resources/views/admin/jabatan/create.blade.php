<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">+ Tambah Jabatan</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.jabatan.store') }}" class="space-y-4">
            @csrf
            <div>
                <label>Nama Jabatan</label>
                <input type="text" name="nama_jabatan" class="border rounded w-full p-2" required>
            </div>
            <div>
                <label>Tunjangan</label>
                <input type="number" name="tunjangan" class="border rounded w-full p-2" required>
            </div>
            <div>
                <label>Gaji Awal</label>
                <input type="number" name="gaji_awal" class="border rounded w-full p-2" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</x-app-layout>