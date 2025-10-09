<x-app-layout>
    <div class="p-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                Manajemen Jabatan
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data jabatan dan gaji awal pegawai secara terstruktur</p>

            {{-- Filter Section --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-8">
                <form method="GET" action="{{ route('admin.jabatan.index') }}" class="flex flex-col md:flex-row gap-3 items-center flex-wrap">
                    <input type="text" name="search" placeholder="🔍 Cari nama jabatan..." 
                        class="border border-gray-300 rounded-xl p-2.5 w-full md:w-1/3 text-gray-700 focus:ring-2 focus:ring-gray-400 focus:outline-none"
                        value="{{ request('search') }}">
                    
                    <select name="jabatan" 
                        class="border border-gray-300 rounded-xl p-2.5 w-full md:w-1/4 text-gray-700 focus:ring-2 focus:ring-gray-400 focus:outline-none">
                        <option value="">Semua Jabatan</option>
                        @foreach($jabatans as $j)
                            <option value="{{ $j->nama_jabatan }}" {{ request('jabatan') == $j->nama_jabatan ? 'selected' : '' }}>
                                {{ $j->nama_jabatan }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" 
                            class="bg-black hover:bg-gray-800 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('admin.jabatan.index') }}" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-sm text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Data Table --}}
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Jabatan</h3>
                    <a href="{{ route('admin.jabatan.create') }}" 
                        class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow">
                        + Tambah Jabatan
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <tr>
                                <th class="border-b px-4 py-3 text-left">Nama Jabatan</th>
                                <th class="border-b px-4 py-3 text-left">Gaji Awal</th>
                                <th class="border-b px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($jabatans as $jabatan)
                                <tr class="hover:bg-gray-50 transition-all">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $jabatan->nama_jabatan }}</td>
                                    <td class="px-4 py-3 text-gray-800 font-semibold">Rp {{ number_format($jabatan->gaji_awal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.jabatan.edit', $jabatan->id) }}" 
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.jabatan.destroy', $jabatan->id) }}" method="POST" class="form-hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    data-nama="{{ $jabatan->nama_jabatan }}"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-gray-500 italic">
                                        Tidak ada data jabatan yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    </div>
                    <div class="ml-4 text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus Jabatan</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="delete-message">Apakah Anda yakin ingin melanjutkan?</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirm-delete-yes" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Hapus
                    </button>
                    <button type="button" id="confirm-delete-no" class="mt-3 inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('delete-confirmation-modal');
            const confirmYes = document.getElementById('confirm-delete-yes');
            const confirmNo = document.getElementById('confirm-delete-no');
            const deleteMessage = document.getElementById('delete-message');
            let formToSubmit = null;

            document.querySelectorAll('.form-hapus').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    formToSubmit = this;
                    const nama = this.querySelector('button').dataset.nama;
                    deleteMessage.textContent = `Apakah Anda yakin ingin menghapus jabatan "${nama}"? Aksi ini tidak dapat dibatalkan.`;
                    modal.classList.remove('hidden');
                });
            });

            confirmYes.addEventListener('click', function () {
                if (formToSubmit) formToSubmit.submit();
            });

            confirmNo.addEventListener('click', function () {
                modal.classList.add('hidden');
                formToSubmit = null;
            });

            // Klik area luar untuk menutup modal
            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                    formToSubmit = null;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>