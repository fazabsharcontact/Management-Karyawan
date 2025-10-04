<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kehadiran Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-red shadow-sm sm:rounded-lg p-6">

                {{-- Alert --}}
                @if (session('success'))
                    <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @php
                    // ⏰ Definisi Waktu Sesuai Skenario BARU
                    $now = Carbon\Carbon::now('Asia/Jakarta');
                    $startAbsen = Carbon\Carbon::createFromTime(8, 50, 0, 'Asia/Jakarta');
                    // Batas Akhir HADIR (09:10:00)
                    $endHadir = Carbon\Carbon::createFromTime(9, 10, 0, 'Asia/Jakarta');
                    // Batas Akhir TERLAMBAT/IZIN/SAKIT (13:00:00)
                    $endTerlambat = Carbon\Carbon::createFromTime(13, 0, 0, 'Asia/Jakarta');

                    $startAbsenPulang = Carbon\Carbon::createFromTime(17, 0, 0, 'Asia/Jakarta');
                    $endAbsenPulang = Carbon\Carbon::createFromTime(19, 0, 0, 'Asia/Jakarta');

                    // Cek kondisi
                    // Absen Masuk/Terlambat/Izin/Sakit sekarang berlaku sampai 13:00
                    $bolehAbsenMasukTerlambat = $now->between($startAbsen, $endTerlambat);
                    $bolehInputIzinSakit = $now->between($startAbsen, $endTerlambat);
                    $bolehAbsenPulang = $now->between($startAbsenPulang, $endAbsenPulang);

                    $today = Carbon\Carbon::today('Asia/Jakarta')->toDateString();
                    $absensiHariIni = $kehadiran->firstWhere('tanggal', $today);
                @endphp

                {{-- Tampilkan Form Absensi --}}
                @if (!$absensiHariIni)
                    {{-- Belum ada record absensi hari ini --}}
                    @if ($bolehAbsenMasukTerlambat)
                        <div
                            class="mb-6 p-4 rounded bg-gray-50 border border-gray-200 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                            <div class="text-sm text-gray-700">
                                Waktu sekarang **{{ $now->format('H:i:s') }}** WIB.
                                <br>
                                @if ($now->lessThanOrEqualTo($endHadir))
                                    <span class="text-blue-600 font-semibold">Anda berada dalam periode Absen Masuk
                                        (**Hadir**) hingga **09:10** WIB.</span>
                                @else
                                    <span class="text-orange-600 font-semibold">Anda berada dalam periode Absen
                                        (**Terlambat**) hingga **13:00** WIB.</span>
                                @endif
                                <br>
                                Pengajuan Izin/Sakit juga berlaku hingga 13:00 WIB.
                            </div>

                            {{-- TOMBOL ABSEN MASUK/TERLAMBAT --}}
                            {{-- Tombol ini muncul selama periode 08:50 hingga 13:00 --}}
                            <form action="{{ route('pegawai.kehadiran.store') }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="status" value="Hadir">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-red rounded hover:bg-blue-700 font-semibold">
                                    ABSEN MASUK
                                </button>
                            </form>

                        </div>

                        {{-- TOMBOL POPUP IZIN/SAKIT (Muncul selama periode 08:50 hingga 13:00) --}}
                        <div class="flex gap-4 mb-6">
                            <button onclick="openIzinModal('Izin')"
                                class="px-4 py-2 bg-blue-500 text-red rounded hover:bg-blue-600 font-semibold">
                                Input Izin
                            </button>
                            {{-- Ubah warna tombol Sakit di modal jika perlu --}}
                            <button onclick="openIzinModal('Sakit')"
                                class="px-4 py-2 bg-pink-500 text-black rounded hover:bg-pink-600 font-semibold">
                                Input Sakit
                            </button>
                        </div>
                    @else
                        {{-- Di luar semua waktu absensi (Setelah jam 13:00) --}}
                        <div class="mb-6 p-3 rounded bg-red-100 text-red-800">
                            Waktu absensi sudah lewat (melebihi 13:00 WIB). Status kehadiran Anda akan otomatis
                            **Absen** oleh sistem.
                        </div>
                    @endif
                @else
                    {{-- Sudah ada data absensi hari ini --}}

                    @if (in_array($absensiHariIni->status, ['Hadir', 'Terlambat']) && !$absensiHariIni->jam_pulang)
                        {{-- Belum absen pulang --}}
                        @if ($bolehAbsenPulang)
                            <form action="{{ route('pegawai.kehadiran.store') }}" method="POST"
                                class="flex gap-3 mb-6">
                                @csrf
                                <div class="p-3 rounded bg-blue-100 text-blue-800">
                                    Waktu absen pulang: **17:00 - 19:00** WIB. Sekarang **{{ $now->format('H:i') }}**.
                                </div>
                                <button type="submit"
                                    class="px-4 py-2 bg-purple-600 text-red rounded hover:bg-purple-700">
                                    Absen Pulang
                                </button>
                            </form>
                        @else
                            <div class="mb-6 p-3 rounded bg-blue-100 text-blue-800">
                                Anda sudah absen masuk **({{ $absensiHariIni->status }} Jam:
                                {{ $absensiHariIni->jam_masuk }})**.
                                Silakan lakukan absen pulang mulai jam **17:00** WIB. Jika terlewat **19:00**, sistem
                                akan otomatis mencatat pulang jam 19:00.
                            </div>
                        @endif
                    @elseif ($absensiHariIni->jam_pulang)
                        {{-- Sudah absen pulang --}}
                        <div class="mb-6 p-3 rounded bg-green-100 text-green-800">
                            Anda sudah melakukan absen masuk ({{ $absensiHariIni->status }} Jam:
                            {{ $absensiHariIni->jam_masuk }}) dan absen pulang (Jam:
                            {{ $absensiHariIni->jam_pulang }}) hari ini. ✅
                        </div>
                    @else
                        {{-- Status: Izin, Sakit, atau Absen --}}
                        <div class="mb-6 p-3 rounded bg-yellow-100 text-yellow-800">
                            Status kehadiran hari ini: **{{ $absensiHariIni->status }}**. Anda tidak perlu melakukan
                            absen pulang.
                        </div>
                    @endif
                @endif

                {{-- POPUP MODAL UNTUK IZIN/SAKIT (Hanya modifikasi style di modal jika diperlukan) --}}
                <div id="izinSakitModal"
                    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    {{-- Ubah bg-black menjadi bg-white --}}
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-red">
                        <div class="mt-3 text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Pengajuan
                                Izin/Sakit</h3>
                            <div class="mt-2 px-7 py-3">
                                <form action="{{ route('pegawai.kehadiran.store') }}" method="POST"
                                    enctype="multipart/form-data" id="izinSakitForm">
                                    @csrf
                                    <input type="hidden" name="status" id="modal-status" required>

                                    <p id="modal-text" class="text-sm text-gray-500 mb-4"></p>

                                    <div class="mb-4 text-left">
                                        <label for="bukti" class="block text-sm font-medium text-gray-700">
                                            Upload Bukti <span class="text-red-500">*Wajib</span>
                                        </label>
                                        <input type="file" name="bukti" id="bukti"
                                            accept=".pdf,.jpg,.jpeg,.png" required
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                                    </div>

                                    <div class="mb-4 text-left">
                                        <label for="keterangan" class="block text-sm font-medium text-gray-700">
                                            Keterangan (Opsional)
                                        </label>
                                        <textarea name="keterangan" id="keterangan" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                    </div>

                                    <div class="items-center px-4 py-3">
                                        <button type="submit" id="modal-submit-btn"
                                            class="px-4 py-2 w-full bg-indigo-600 text-red text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            Kirim Pengajuan
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="px-4 py-2">
                                <button id="closeModalBtn" onclick="closeIzinModal()"
                                    class="text-sm text-gray-500 hover:text-gray-700">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function openIzinModal(status) {
                        document.getElementById('modal-status').value = status;
                        document.getElementById('modal-title').innerText = 'Pengajuan ' + status;
                        document.getElementById('modal-text').innerText = 'Anda mengajukan status ' + status +
                            ' hari ini. Mohon lampirkan bukti yang relevan.';
                        document.getElementById('modal-submit-btn').innerText = 'Kirim Pengajuan ' + status;
                        document.getElementById('izinSakitModal').classList.remove('hidden');
                    }

                    function closeIzinModal() {
                        document.getElementById('izinSakitModal').classList.add('hidden');
                    }

                    // Tutup modal jika klik di luar area modal
                    window.onclick = function(event) {
                        const modal = document.getElementById('izinSakitModal');
                        if (event.target === modal) {
                            closeIzinModal();
                        }
                    }
                </script>


                {{-- Filter dan Tabel Riwayat (Tidak diubah) --}}

                <h3 class="text-lg font-semibold mb-2 mt-8">Riwayat Absensi</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Tanggal</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Masuk</th>
                                <th class="px-4 py-2 border">Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kehadiran as $row)
                                <tr>
                                    <td class="px-4 py-2 border">
                                        {{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        @if ($row->status == 'Hadir')
                                            <span class="text-green-600 font-semibold">{{ $row->status }}</span>
                                        @elseif($row->status == 'Izin' || $row->status == 'Sakit')
                                            <span class="text-blue-600 font-semibold">{{ $row->status }}</span>
                                        @elseif($row->status == 'Terlambat')
                                            <span class="text-orange-600 font-semibold">{{ $row->status }}</span>
                                        @else
                                            <span class="text-red-600 font-semibold">{{ $row->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ $row->jam_masuk ? \Carbon\Carbon::parse($row->jam_masuk)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ $row->jam_pulang ? \Carbon\Carbon::parse($row->jam_pulang)->format('H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 border text-center">Tidak ada data absensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
