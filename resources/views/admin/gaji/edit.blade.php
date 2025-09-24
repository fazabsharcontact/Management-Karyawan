<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Edit Data Gaji untuk: {{ $gaji->pegawai->nama }}
        </h2>
    </x-slot>

    <div class="p-6">
        {{-- PERBAIKAN: Action route menggunakan $gaji->id --}}
        <form action="{{ route('admin.gaji.update', $gaji->id) }}" method="POST" class="space-y-4 bg-white p-6 rounded-lg shadow">
            @csrf
            @method('PUT')

            <!-- Pegawai -->
            <div>
                <label for="pegawai_id" class="block font-medium text-sm text-gray-700">Pegawai</label>
                {{-- PERBAIKAN: Gunakan $pegawais (plural) untuk loop dan name="pegawai_id" --}}
                <select name="pegawai_id" id="pegawai_id" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                    @foreach($pegawais as $p)
                        {{-- PERBAIKAN: Bandingkan $gaji->pegawai_id dengan $p->id --}}
                        <option value="{{ $p->id }}" {{ $gaji->pegawai_id == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} - {{ $p->jabatan->nama_jabatan ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>

             <!-- Gaji Pokok -->
            <div>
                <label for="gaji_pokok" class="block font-medium text-sm text-gray-700">Gaji Pokok</label>
                <input type="number" name="gaji_pokok" id="gaji_pokok" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ $gaji->gaji_pokok }}" required>
            </div>

            <!-- Tunjangan & Potongan -->
             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="total_tunjangan" class="block font-medium text-sm text-gray-700">Total Tunjangan</label>
                    <input type="number" name="total_tunjangan" id="total_tunjangan" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ $gaji->total_tunjangan }}" required>
                </div>
                <div>
                    <label for="total_potongan" class="block font-medium text-sm text-gray-700">Total Potongan</label>
                    <input type="number" name="total_potongan" id="total_potongan" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ $gaji->total_potongan }}" required>
                </div>
            </div>

            <!-- Bulan & Tahun -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="bulan" class="block font-medium text-sm text-gray-700">Bulan</label>
                    <select name="bulan" id="bulan" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                        @php
                            $bulan_nama = [1=>"Januari", 2=>"Februari", 3=>"Maret", 4=>"April", 5=>"Mei", 6=>"Juni", 7=>"Juli", 8=>"Agustus", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Desember"];
                        @endphp
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $gaji->bulan == $i ? 'selected' : '' }}>
                                {{ $bulan_nama[$i] }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block font-medium text-sm text-gray-700">Tahun</label>
                    <input type="number" name="tahun" id="tahun" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ $gaji->tahun }}" required>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Update
                </button>
                <a href="{{ route('admin.gaji.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
