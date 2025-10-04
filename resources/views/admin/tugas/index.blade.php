<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Tugas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                {{-- Form tambah tugas --}}
                <form action="{{ route('admin.tugas.store') }}" method="POST" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Judul Tugas</label>
                        <input type="text" name="judul_tugas" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Deskripsi</label>
                        <textarea name="deskripsi" class="w-full border rounded p-2" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Penerima</label>
                        <select name="penerima_id" class="w-full border rounded p-2" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($pegawai as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Tenggat Waktu</label>
                        <input type="datetime-local" name="tenggat_waktu" class="w-full border rounded p-2" required>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Tambah Tugas
                    </button>
                </form>

                {{-- Daftar tugas --}}
                <h3 class="text-lg font-semibold mb-3">Daftar Tugas</h3>
                <table class="w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2">Judul</th>
                            <th class="border px-3 py-2">Penerima</th>
                            <th class="border px-3 py-2">Tenggat</th>
                            <th class="border px-3 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tugas as $t)
                            <tr>
                                <td class="border px-3 py-2">{{ $t->judul_tugas }}</td>
                                <td class="border px-3 py-2">{{ $t->penerima->nama ?? '-' }}</td>
                                <td class="border px-3 py-2">
                                    {{ \Carbon\Carbon::parse($t->tenggat_waktu)->format('d M Y H:i') }}</td>
                                <td class="border px-3 py-2">{{ $t->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border px-3 py-2 text-center text-gray-500">
                                    Belum ada tugas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
