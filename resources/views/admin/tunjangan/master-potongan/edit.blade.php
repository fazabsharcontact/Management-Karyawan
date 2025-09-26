<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">✏️ Edit Jenis Potongan</h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6 max-w-lg mx-auto">
            <form method="POST" action="{{ route('admin.master-potongan.update', $masterPotongan->id) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="nama_potongan" class="block font-medium text-sm text-gray-700">Nama Potongan</label>
                    <input type="text" name="nama_potongan" id="nama_potongan" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('nama_potongan', $masterPotongan->nama_potongan) }}" required autofocus>
                </div>

                {{-- INPUT BARU DITAMBAHKAN --}}
                <div>
                    <label for="jumlah_default" class="block font-medium text-sm text-gray-700">Jumlah Default (Rp)</label>
                    <input type="number" name="jumlah_default" id="jumlah_default" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('jumlah_default', $masterPotongan->jumlah_default) }}" placeholder="Contoh: 50000">
                </div>

                <div>
                    <label for="deskripsi" class="block font-medium text-sm text-gray-700">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full">{{ old('deskripsi', $masterPotongan->deskripsi) }}</textarea>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md shadow">Update</button>
                    <a href="{{ route('admin.tunjangan-potongan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
