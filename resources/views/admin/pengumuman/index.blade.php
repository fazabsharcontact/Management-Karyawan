<x-app-layout>
    <div class="p-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                Manajemen Pengumuman
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan arsipkan pengumuman penting dengan mudah</p>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-xl border border-red-200 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Bagian Daftar Pengumuman --}}
            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-900">Arsip Pengumuman</h3>
                    <a href="{{ route('admin.pengumuman.create') }}" 
                        class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow">
                        + Buat Pengumuman Baru
                    </a>
                </div>

                <div class="space-y-5">
                    @forelse($pengumumans as $pengumuman)
                        <div class="border border-gray-200 rounded-xl p-5 bg-white shadow-sm hover:shadow transition-all duration-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-lg text-gray-900">{{ $pengumuman->judul }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Dipublikasikan oleh <span class="font-medium text-gray-700">{{ $pengumuman->pembuat->username ?? 'N/A' }}</span>
                                        pada {{ $pengumuman->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}" method="POST" 
                                      onsubmit="return confirm('Hapus pengumuman {{ $pengumuman->judul }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1.5 rounded-lg text-sm transition-all duration-200">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                            <div class="mt-3 text-gray-700 leading-relaxed">
                                {!! $pengumuman->isi !!}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 italic border border-dashed border-gray-300 rounded-xl">
                            Belum ada pengumuman yang dibuat.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $pengumumans->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>