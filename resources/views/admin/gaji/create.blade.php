<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            ➕ Tambah Data Gaji
        </h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('admin.gaji.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Pilih Pegawai -->
            <div>
                <label class="block font-medium">Pegawai</label>
                <select name="id_pegawai" class="border rounded w-full p-2" required>
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($pegawai as $p)
                        <option value="{{ $p->id_pegawai }}">{{ $p->nama }} - {{ $p->jabatan->nama_jabatan ?? '' }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bulan -->
            <div>
                <label class="block font-medium">Bulan</label>
                <select name="bulan" class="border rounded w-full p-2" required>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $bulan_nama[$i] }}</option>
                    @endfor
                </select>
            </div>

            <!-- Tahun -->
            <div>
                <label class="block font-medium">Tahun</label>
                <input type="number" name="tahun" class="border rounded w-full p-2" value="{{ date('Y') }}" required>
            </div>

            <!-- Total Gaji -->
            <div>
                <label class="block font-medium">Total Gaji</label>
                <input type="number" name="total_gaji" class="border rounded w-full p-2" placeholder="Masukkan total gaji" required>
            </div>

            <!-- Submit -->
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                Simpan
            </button>
            <a href="{{ route('admin.gaji.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
        </form>
    </div>
</x-app-layout>