<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gaji Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-8 space-y-8">

                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Ringkasan Gaji Periode Saat Ini</h3>
                    <div class="mt-3 flex flex-wrap gap-3 items-center">
                        {{-- Tampilkan tombol Lihat Detail & Unduh hanya jika $detail ada --}}
                        @if ($detail)
                            <button
                                class="px-5 py-2 bg-blue-600 text-white font-medium rounded-md shadow hover:bg-blue-700 transition"
                                id="btnDetailGaji">
                                {{-- PERBAIKAN 1: Pastikan $bulan adalah angka --}}
                                Lihat Detail
                                ({{ is_numeric($bulan) ? $bulanNama[$bulan] ?? 'Bulan Error' : 'Bulan Error' }}
                                {{ $tahun }})
                            </button>
                            {{-- TOMBOL UNDUH SLIP GAJI --}}
                            {{-- Link ini sudah benar, asalkan route-nya ('pegawai.gaji.unduh') mengarah ke method unduhSlipGaji --}}
                            <a href="{{ route('pegawai.gaji.unduh', $detail->id) }}" target="_blank"
                                class="px-5 py-2 bg-red-600 text-white font-medium rounded-md shadow hover:bg-red-700 transition">
                                Unduh Slip Gaji PDF
                            </a>
                        @else
                            {{-- PERBAIKAN 2: Pastikan $bulan adalah angka saat menampilkan pesan --}}
                            <p class="text-gray-500">Data gaji untuk periode
                                {{ is_numeric($bulan) ? $bulanNama[$bulan] ?? 'Bulan Error' : 'Bulan Error' }}
                                {{ $tahun }} belum tersedia.</p>
                        @endif
                    </div>
                </div>

                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50"
                    id="popupDetailGaji">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                        <div class="flex justify-between items-center border-b pb-3">
                            <h3 class="text-xl font-semibold text-gray-800">Detail Gaji:
                                {{-- PERBAIKAN 3: Pastikan $bulan adalah angka di popup --}}
                                {{ is_numeric($bulan) ? $bulanNama[$bulan] ?? 'Bulan Error' : 'Bulan Error' }}
                                {{ $tahun }}</h3>
                            <button class="text-gray-400 hover:text-gray-600 text-2xl leading-none"
                                id="closePopup">&times;</button>
                        </div>
                        <div class="mt-4 space-y-2 text-gray-700">
                            @if ($detail)
                                <div class="space-y-1">
                                    <p><span class="font-medium text-gray-600">Gaji Pokok:</span> <span
                                            class="float-right font-medium">Rp
                                            {{ number_format($detail->gaji_pokok, 0, ',', '.') }}</span></p>
                                    <hr class="my-1 border-gray-200">
                                    <p class="font-bold text-blue-600">PENERIMAAN</p>
                                    @foreach ($detail->tunjanganDetails as $tunjangan)
                                        <p class="text-sm ml-2"><span
                                                class="text-gray-600">{{ $tunjangan->masterTunjangan->nama_tunjangan }}</span>:
                                            <span class="float-right">Rp
                                                {{ number_format($tunjangan->jumlah, 0, ',', '.') }}</span>
                                        </p>
                                    @endforeach
                                    <p class="font-medium pt-1 border-t border-gray-300"><span
                                            class="text-gray-600">Total Tunjangan:</span> <span
                                            class="float-right text-green-600">Rp
                                            {{ number_format($detail->total_tunjangan, 0, ',', '.') }}</span></p>
                                    <hr class="my-1 border-gray-200">
                                    <p class="font-bold text-red-600">POTONGAN</p>
                                    @foreach ($detail->potonganDetails as $potongan)
                                        <p class="text-sm ml-2"><span
                                                class="text-gray-600">{{ $potongan->masterPotongan->nama_potongan }}</span>:
                                            <span class="float-right">Rp
                                                {{ number_format($potongan->jumlah, 0, ',', '.') }}</span>
                                        </p>
                                    @endforeach
                                    <p class="font-medium pt-1 border-t border-gray-300"><span
                                            class="text-gray-600">Total Potongan:</span> <span
                                            class="float-right text-red-600">Rp
                                            {{ number_format($detail->total_potongan, 0, ',', '.') }}</span></p>
                                </div>

                                <p class="text-2xl font-extrabold text-green-700 pt-4 border-t-2 border-green-200 mt-4">
                                    <span>Gaji Bersih:</span> <span class="float-right">Rp
                                        {{ number_format($detail->gaji_bersih, 0, ',', '.') }}</span>
                                </p>
                            @else
                                <p>Data gaji belum tersedia.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Bagian Filter Tahun dan Bulan (Tidak perlu diubah) --}}
                <form method="GET" class="flex flex-wrap gap-6 items-end">
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-600 mb-1">Tahun</label>
                        <select name="tahun"
                            class="border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @for ($i = now()->year; $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="bulan" class="block text-sm font-medium text-gray-600 mb-1">Bulan</label>
                        <select name="bulan"
                            class="border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @foreach ($bulanNama as $key => $value)
                                <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="px-5 py-2 bg-green-600 text-white font-medium rounded-md shadow hover:bg-green-700 transition">
                        Lihat Data
                    </button>
                </form>

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Riwayat Gaji</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="px-4 py-3 border text-left">Tahun</th>
                                    <th class="px-4 py-3 border text-left">Bulan</th>
                                    <th class="px-4 py-3 border text-left">Gaji Bersih</th>
                                    <th class="px-4 py-3 border text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($riwayat as $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $row->tahun }}</td>
                                        {{-- PERBAIKAN 4: Pastikan $row->bulan adalah angka --}}
                                        <td class="px-4 py-3">
                                            {{ is_numeric($row->bulan) ? $bulanNama[$row->bulan] ?? 'Bulan Error' : 'Bulan Error' }}
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-800">Rp
                                            {{ number_format($row->gaji_bersih, 0, ',', '.') }}</td>
                                        {{-- KOLOM AKSI DENGAN TOMBOL UNDUH (Link sudah benar) --}}
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('pegawai.gaji.unduh', $row->id) }}" target="_blank"
                                                class="inline-flex items-center px-3 py-1 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-700 transition ease-in-out duration-150">
                                                Unduh PDF
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">Data riwayat gaji
                                            tidak
                                            ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById("btnDetailGaji")?.addEventListener("click", function() {
            document.getElementById("popupDetailGaji").classList.remove("hidden");
        });
        document.getElementById("closePopup")?.addEventListener("click", function() {
            document.getElementById("popupDetailGaji").classList.add("hidden");
        });
    </script>
</x-app-layout>
