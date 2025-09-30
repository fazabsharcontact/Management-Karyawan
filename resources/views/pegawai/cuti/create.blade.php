<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Form Pengajuan Cuti
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">
            <!-- Pengingat Sisa Cuti -->
            <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-400">
                <p class="font-bold text-blue-800">Sisa Cuti Tahunan Anda: {{ $pegawai->sisaCuti->sisa_cuti ?? 0 }} hari</p>
            </div>

             <!-- Notifikasi Error dari Controller -->
             @if(session('error'))
                 <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Menampilkan Error Validasi -->
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('pegawai.cuti.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_mulai" class="block font-medium text-sm text-gray-700">Tanggal Mulai Cuti</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('tanggal_mulai') }}" required>
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block font-medium text-sm text-gray-700">Tanggal Selesai Cuti</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ old('tanggal_selesai') }}" required>
                    </div>
                </div>
                <div>
                    <label for="keterangan" class="block font-medium text-sm text-gray-700">Alasan / Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="4" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>{{ old('keterangan') }}</textarea>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow">Ajukan Cuti</button>
                    <a href="{{ route('pegawai.cuti.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

