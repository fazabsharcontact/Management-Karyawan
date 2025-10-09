<x-app-layout>
    <div class="min-h-screen bg-white p-10">
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">✏️ Edit Pegawai: {{ $pegawai->nama }}</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Perbarui informasi pegawai dengan data terbaru secara lengkap dan akurat.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.pegawai.update', $pegawai->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('admin.pegawai._form', ['pegawai' => $pegawai])

                    <!-- Tombol -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.pegawai.index') }}"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-lg text-sm font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>