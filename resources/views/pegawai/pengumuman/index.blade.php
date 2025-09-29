<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengumuman Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Daftar Pengumuman</h1>

                @if ($pengumumans->isEmpty())
                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg">
                        Belum ada pengumuman untuk Anda.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($pengumumans as $p)
                            <div class="bg-white shadow rounded-lg p-4 border">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    {{ $p->pengumuman->judul ?? '-' }}
                                </h2>
                                <p class="text-gray-600 mt-2">
                                    {{ $p->pengumuman->isi ?? '-' }}
                                </p>
                                <div class="text-sm text-gray-500 mt-3">
                                    {{ $p->pengumuman->created_at?->format('d M Y H:i') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
