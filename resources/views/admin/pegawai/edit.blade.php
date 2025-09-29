<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- PERBAIKAN: Judul yang sesuai --}}
            ✏️ Edit Pegawai: {{ $pegawai->nama }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- PERBAIKAN 1: Action route menunjuk ke 'update' dan menyertakan ID pegawai --}}
                <form action="{{ route('admin.pegawai.update', $pegawai->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Method untuk update adalah PUT/PATCH --}}

                    {{-- PERBAIKAN 2: Kirim variabel $pegawai yang ada, bukan membuat yang baru --}}
                    @include('admin.pegawai._form', ['pegawai' => $pegawai])

                    <div class="mt-6 flex gap-2">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Update
                        </button>
                        <a href="{{ route('admin.pegawai.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Kembali
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>