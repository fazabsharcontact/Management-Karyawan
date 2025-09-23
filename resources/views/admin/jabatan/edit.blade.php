<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">✏️ Edit Jabatan</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.jabatan.update', $jabatan->id_jabatan) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label>Nama Jabatan</label>
                <input type="text" name="nama_jabatan" value="{{ $jabatan->nama_jabatan }}" 
                       class="border rounded w-full p-2" required>
            </div>
            <div>
                <label>Tunjangan</label>
                <input type="number" name="tunjangan" value="{{ $jabatan->tunjangan }}" 
                       class="border rounded w-full p-2" required>
            </div>
            <div>
                <label>Gaji Awal</label>
                <input type="number" name="gaji_awal" value="{{ $jabatan->gaji_awal }}" 
                       class="border rounded w-full p-2" required>
            </div>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-app-layout>