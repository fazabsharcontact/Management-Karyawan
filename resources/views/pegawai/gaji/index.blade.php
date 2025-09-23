<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gaji Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-8 space-y-8">
                
                <!-- Ringkasan Gaji -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Gaji Bulan Ini</h3>
                    <div class="mt-3">
                        <button 
                            class="px-5 py-2 bg-blue-600 text-white font-medium rounded-md shadow hover:bg-blue-700 transition" 
                            id="btnDetailGaji">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- Pop-up -->
                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden" id="popupDetailGaji">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                        <div class="flex justify-between items-center border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-700">Detail Gaji</h3>
                            <button class="text-gray-400 hover:text-gray-600 text-2xl leading-none" id="closePopup">&times;</button>
                        </div>
                        <div class="mt-4 space-y-2 text-gray-700">
                            @if($detail)
                                <p><span class="font-medium">Gaji Pokok:</span> Rp {{ number_format($detail->gaji_pokok, 0, ',', '.') }}</p>
                                <p><span class="font-medium">Tunjangan:</span> Rp {{ number_format($detail->tunjangan, 0, ',', '.') }}</p>
                                <p><span class="font-medium">Potongan:</span> Rp {{ number_format($detail->potongan, 0, ',', '.') }}</p>
                                <p class="text-lg font-semibold"><span>Total Gaji:</span> Rp {{ number_format($detail->total_gaji, 0, ',', '.') }}</p>
                            @else
                                <p>Data gaji belum tersedia.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Filter -->
                <form method="GET" class="flex flex-wrap gap-6 items-end">
                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-600 mb-1">Tahun</label>
                        <select name="tahun" class="border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @for ($i = now()->year; $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="bulan" class="block text-sm font-medium text-gray-600 mb-1">Bulan</label>
                        <select name="bulan" class="border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @foreach($bulanNama as $key => $value)
                                <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button 
                        type="submit" 
                        class="px-5 py-2 bg-green-600 text-white font-medium rounded-md shadow hover:bg-green-700 transition">
                        Filter
                    </button>
                </form>

                <!-- Riwayat -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Riwayat Gaji</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 text-sm rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="px-4 py-3 border text-left">Tahun</th>
                                    <th class="px-4 py-3 border text-left">Bulan</th>
                                    <th class="px-4 py-3 border text-left">Gaji</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($riwayat as $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $row->tahun }}</td>
                                        <td class="px-4 py-3">{{ $bulanNama[$row->bulan] }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-800">Rp {{ number_format($row->total_gaji, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-center text-gray-500">Data tidak ditemukan</td>
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
        document.getElementById("btnDetailGaji").addEventListener("click", function() {
            document.getElementById("popupDetailGaji").classList.remove("hidden");
        });
        document.getElementById("closePopup").addEventListener("click", function() {
            document.getElementById("popupDetailGaji").classList.add("hidden");
        });
    </script>
</x-app-layout>