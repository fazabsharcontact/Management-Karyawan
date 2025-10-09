<x-app-layout>
    <div class="py-12 bg-white">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- === HEADER HALAMAN === -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <div>
                    <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                        Gaji Pegawai
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Lihat dan unduh slip gaji Anda di sini 
                    </p>
                </div>
                <div class="flex items-center bg-gray-100 px-4 py-2 rounded-lg shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h18M9 3v18m6-18v18M4.5 6h15m-15 4.5h15m-15 4.5h15m-15 4.5h15" />
                    </svg>
                    <span class="text-gray-700 text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>

            <!-- === KONTEN UTAMA === -->
            <div class="bg-white shadow-md rounded-lg p-8 space-y-8">

                <!-- Ringkasan Gaji -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Ringkasan Gaji Periode Saat Ini</h3>
                    <div class="mt-3 flex flex-wrap gap-3 items-center">
                        @if ($detail)
                            <button
                                class="px-5 py-2 bg-blue-600 text-white font-medium rounded-md shadow hover:bg-blue-700 transition"
                                id="btnDetailGaji">
                                Lihat Detail
                                ({{ is_numeric($bulan) ? $bulanNama[$bulan] ?? 'Bulan Error' : 'Bulan Error' }}
                                {{ $tahun }})
                            </button>

                            <a href="{{ route('pegawai.gaji.unduh', $detail->id) }}" target="_blank"
                                class="px-5 py-2 bg-red-600 text-white font-medium rounded-md shadow hover:bg-red-700 transition">
                                Unduh Slip Gaji PDF
                            </a>
                        @else
                            <p class="text-gray-500">
                                Data gaji untuk periode
                                {{ is_numeric($bulan) ? $bulanNama[$bulan] ?? 'Bulan Error' : 'Bulan Error' }}
                                {{ $tahun }} belum tersedia.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Popup Detail Gaji -->
                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50"
                    id="popupDetailGaji">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                        <div class="flex justify-between items-center border-b pb-3">
                            <h3 class="text-xl font-semibold text-gray-800">
                                Detail Gaji:
                                {{ is_numeric($bulan) ? $bulanNama[$bulan] ?? 'Bulan Error' : 'Bulan Error' }}
                                {{ $tahun }}
                            </h3>
                            <button class="text-gray-400 hover:text-gray-600 text-2xl leading-none"
                                id="closePopup">&times;</button>
                        </div>

                        <div class="mt-4 space-y-2 text-gray-700">
                            @if ($detail)
                                <div class="space-y-1">
                                    <p><span class="font-medium text-gray-600">Gaji Pokok:</span>
                                        <span class="float-right font-medium">
                                            Rp {{ number_format($detail->gaji_pokok, 0, ',', '.') }}
                                        </span>
                                    </p>

                                    <hr class="my-1 border-gray-200">

                                    <p class="font-bold text-blue-600">PENERIMAAN</p>
                                    @foreach ($detail->tunjanganDetails as $tunjangan)
                                        <p class="text-sm ml-2">
                                            <span class="text-gray-600">{{ $tunjangan->masterTunjangan->nama_tunjangan }}</span>
                                            <span class="float-right">Rp
                                                {{ number_format($tunjangan->jumlah, 0, ',', '.') }}</span>
                                        </p>
                                    @endforeach

                                    <p class="font-medium pt-1 border-t border-gray-300">
                                        <span class="text-gray-600">Total Tunjangan:</span>
                                        <span class="float-right text-green-600">Rp
                                            {{ number_format($detail->total_tunjangan, 0, ',', '.') }}</span>
                                    </p>

                                    <hr class="my-1 border-gray-200">

                                    <p class="font-bold text-red-600">POTONGAN</p>
                                    @foreach ($detail->potonganDetails as $potongan)
                                        <p class="text-sm ml-2">
                                            <span class="text-gray-600">{{ $potongan->masterPotongan->nama_potongan }}</span>
                                            <span class="float-right">Rp
                                                {{ number_format($potongan->jumlah, 0, ',', '.') }}</span>
                                        </p>
                                    @endforeach

                                    <p class="font-medium pt-1 border-t border-gray-300">
                                        <span class="text-gray-600">Total Potongan:</span>
                                        <span class="float-right text-red-600">Rp
                                            {{ number_format($detail->total_potongan, 0, ',', '.') }}</span>
                                    </p>
                                </div>

                                <p
                                    class="text-2xl font-extrabold text-green-700 pt-4 border-t-2 border-green-200 mt-4">
                                    <span>Gaji Bersih:</span>
                                    <span class="float-right">Rp
                                        {{ number_format($detail->gaji_bersih, 0, ',', '.') }}
                                    </span>
                                </p>
                            @else
                                <p>Data gaji belum tersedia.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Filter Tahun & Bulan -->
                <form method="GET" class="flex flex-wrap gap-6 items-end">
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-600 mb-1">Tahun</label>
                        <select name="tahun"
                            class="border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @for ($i = now()->year; $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="bulan" class="block text-sm font-medium text-gray-600 mb-1">Bulan</label>
                        <select name="bulan"
                            class="border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @foreach ($bulanNama as $key => $value)
                                <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="px-5 py-2 bg-gray-900 text-white font-medium rounded-md shadow hover:bg-gray-800 transition">
                        Lihat Data
                    </button>
                </form>

                <!-- Riwayat Gaji -->
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
                                        <td class="px-4 py-3">
                                            {{ is_numeric($row->bulan) ? $bulanNama[$row->bulan] ?? 'Bulan Error' : 'Bulan Error' }}
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-800">
                                            Rp {{ number_format($row->gaji_bersih, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('pegawai.gaji.unduh', $row->id) }}" target="_blank"
                                                class="inline-flex items-center px-3 py-1 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition">
                                                Unduh PDF
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                            Data riwayat gaji tidak ditemukan
                                        </td>
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
        document.getElementById("btnDetailGaji")?.addEventListener("click", () => {
            document.getElementById("popupDetailGaji").classList.remove("hidden");
        });
        document.getElementById("closePopup")?.addEventListener("click", () => {
            document.getElementById("popupDetailGaji").classList.add("hidden");
        });
    </script>
</x-app-layout>