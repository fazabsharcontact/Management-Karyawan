<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Edit Pegawai
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

                <form action="{{ route('admin.pegawai.update', $pegawai->id_pegawai) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('admin.pegawai._form', ['pegawai' => $pegawai])

                    <div class="mt-4 flex gap-2">
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                            Update
                        </button>
                        <a href="{{ route('admin.pegawai.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Kembali
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>