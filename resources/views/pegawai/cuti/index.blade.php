<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Ajukan Cuti</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded">
        <form action="{{ route('pegawai.cuti.store') }}" method="POST">
            @csrf
            <div>
                <label class="block font-medium">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="w-full border rounded p-2" required>
            </div>
            <div class="mt-3">
                <label class="block font-medium">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="w-full border rounded p-2" required>
            </div>
            <div class="mt-3">
                <label class="block font-medium">Keterangan</label>
                <textarea name="keterangan" class="w-full border rounded p-2"></textarea>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Ajukan</button>
            </div>
        </form>
    </div>
</x-app-layout>
