<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">

                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Tambah Jenis Tunjangan
                        </h2>
                        <p class="text-gray-500 mt-1 text-sm">
                            Isi data tunjangan baru yang akan ditambahkan ke dalam sistem.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.master-tunjangan.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="nama_tunjangan" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Tunjangan
                            </label>
                            <input type="text" name="nama_tunjangan" id="nama_tunjangan"
                                value="{{ old('nama_tunjangan') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition"
                                placeholder="Contoh: Tunjangan Kehadiran" required autofocus>
                        </div>

                        <div>
                            <label for="jumlah_default" class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Default (Rp)
                            </label>
                            <input type="number" name="jumlah_default" id="jumlah_default"
                                value="{{ old('jumlah_default') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition"
                                placeholder="Contoh: 500000">
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                                Deskripsi (Opsional)
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition"
                                placeholder="Tuliskan deskripsi singkat jika diperlukan">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('admin.tunjangan-potongan.index') }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg border border-gray-300 transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg shadow transition">
                                Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>