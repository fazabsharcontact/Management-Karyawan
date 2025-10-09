<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">

                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Edit Divisi
                        </h2>
                        <p class="text-gray-500 mt-1 text-sm">
                            Ubah informasi divisi berikut sesuai kebutuhan.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.divisi.update', $divisi->id) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nama_divisi" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Divisi
                            </label>
                            <input type="text" name="nama_divisi" id="nama_divisi"
                                value="{{ old('nama_divisi', $divisi->nama_divisi) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition"
                                placeholder="Masukkan nama divisi" required autofocus>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('admin.tim-divisi.index') }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg border border-gray-300 transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg shadow transition">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>