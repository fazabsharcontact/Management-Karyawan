<x-app-layout>
    <div class="p-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                Manajemen Data Pegawai
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data pegawai dengan mudah dan efisien</p>

            {{-- Filter Section --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-8">
                <form method="GET" action="{{ route('admin.pegawai.index') }}" class="flex flex-col md:flex-row gap-3 items-center">
                    <input type="text" name="search" placeholder="🔍 Cari nama pegawai..." 
                        class="border border-gray-300 rounded-xl p-2.5 w-full md:w-1/3 text-gray-700 focus:ring-2 focus:ring-gray-400 focus:outline-none"
                        value="{{ request('search') }}">
                    
                    <select name="jabatan" 
                            class="border border-gray-300 rounded-xl p-2.5 w-full md:w-1/3 text-gray-700 focus:ring-2 focus:ring-gray-400 focus:outline-none">
                        <option value="">Semua Jabatan</option>
                        @foreach($jabatans as $j)
                            <option value="{{ $j->nama_jabatan }}" 
                                    {{ request('jabatan') == $j->nama_jabatan ? 'selected' : '' }}>
                                {{ $j->nama_jabatan }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" 
                                class="bg-black hover:bg-gray-800 text-white px-5 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('admin.pegawai.index') }}" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-sm text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Data Table --}}
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Pegawai</h3>
                    <a href="{{ route('admin.pegawai.create') }}" 
                    class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow">
                        + Tambah Pegawai
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <tr>
                                <th class="border-b px-4 py-3 text-left">Nama</th>
                                <th class="border-b px-4 py-3 text-left">Jabatan</th>
                                <th class="border-b px-4 py-3 text-left">Tim / Divisi</th>
                                <th class="border-b px-4 py-3 text-left">Gaji Pokok</th>
                                <th class="border-b px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pegawais as $p)
                            <tr class="hover:bg-gray-50 transition-all">
                                <td class="px-4 py-3 text-gray-900 font-medium">{{ $p->nama }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $p->jabatan->nama_jabatan ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-700">
                                    @if ($p->tim)
                                        {{ $p->tim->nama_tim }}
                                        <span class="text-xs text-gray-500 block">{{ $p->tim->divisi->nama_divisi ?? 'N/A' }}</span>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-800 font-semibold">Rp {{ number_format($p->gaji_pokok, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.pegawai.edit', $p->id) }}" 
                                        class="bg-gray-800 hover:bg-black text-white px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.pegawai.destroy', $p->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    data-nama="{{ $p->nama }}"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm shadow">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-gray-500 italic">
                                    Tidak ada data pegawai yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
            </div>
            </div>

            <div class="mt-5">
                {{ $pegawais->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    </div>
                    <div class="ml-4 text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus Pegawai</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="delete-message">Apakah Anda yakin ingin melanjutkan?</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirm-delete-button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Hapus Pegawai
                    </button>
                    <button type="button" id="cancel-delete-button" class="mt-3 inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('delete-modal');
            const confirmButton = document.getElementById('confirm-delete-button');
            const cancelButton = document.getElementById('cancel-delete-button');
            const deleteMessage = document.getElementById('delete-message');
            let formToSubmit = null;

            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); 
                    formToSubmit = this; 
                    const namaPegawai = this.querySelector('button[type="submit"]').dataset.nama;
                    deleteMessage.textContent = `Apakah Anda yakin ingin menghapus (memecat) pegawai bernama "${namaPegawai}"? Aksi ini tidak dapat dibatalkan.`;
                    modal.classList.remove('hidden');
                });
            });

            confirmButton.addEventListener('click', function () {
                if (formToSubmit) {
                    formToSubmit.submit();
                }
            });

            const closeModal = () => {
                modal.classList.add('hidden');
                formToSubmit = null;
            }

            cancelButton.addEventListener('click', closeModal);

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>