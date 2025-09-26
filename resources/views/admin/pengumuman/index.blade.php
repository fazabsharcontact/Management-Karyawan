<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Pengumuman
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded-lg shadow p-6">
             @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Arsip Pengumuman</h3>
                <a href="{{ route('admin.pengumuman.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow text-sm">
                    + Buat Pengumuman Baru
                </a>
            </div>

            <div class="space-y-4">
                @forelse($pengumumans as $pengumuman)
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-lg text-gray-800">{{ $pengumuman->judul }}</h4>
                            <p class="text-xs text-gray-500">
                                Dipublikasikan oleh {{ $pengumuman->pembuat->username ?? 'N/A' }} pada {{ $pengumuman->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <form action="{{ route('admin.pengumuman.destroy', $pengumuman->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">&times; Hapus</button>
                        </form>
                    </div>
                    <div class="prose max-w-none mt-2 text-gray-700">
                       {!! $pengumuman->isi !!}
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <p>Belum ada pengumuman yang dibuat.</p>
                </div>
                @endforelse
            </div>
             <div class="mt-4">
                {{ $pengumumans->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>