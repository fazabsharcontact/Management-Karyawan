<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kehadiran Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-md bg-green-50 text-green-700 border border-green-200 flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 p-4 rounded-md bg-red-50 text-red-700 border border-red-200 flex items-start">
                         <svg class="w-5 h-5 mr-3 mt-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @php
                    $now = \Carbon\Carbon::now('Asia/Jakarta');
                    $today = $now->copy()->startOfDay();
                    $absensiHariIni = $kehadiran->firstWhere('tanggal', $today->toDateString());
                    $startPulang = $today->copy()->addHours(17);
                    $isBeforePulangTime = $now->lt($startPulang);
                    $isWeekend = $now->isWeekend();
                @endphp

                <div class="mb-8 p-6 rounded-lg bg-gray-50 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Presensi Hari Ini</h3>
                            <p class="text-sm text-gray-500">{{ $now->isoFormat('dddd, D MMMM Y') }}</p>
                        </div>
                        <div class="text-right">
                             <p id="realtime-clock" class="text-2xl font-mono font-bold text-gray-700">{{ $now->format('H:i:s') }}</p>
                             <p class="text-xs text-gray-500">WIB</p>
                        </div>
                    </div>

                    @if ($isWeekend)
                        <div class="text-center p-6 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-bold text-blue-800">Selamat Berakhir Pekan!</h4>
                            <p class="text-sm text-blue-700 mt-2">Tidak ada presensi yang perlu dilakukan hari ini. Nikmati waktu istirahat Anda.</p>
                        </div>
                    @elseif (!$absensiHariIni)
                         <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 text-center flex flex-col justify-between">
                                <h4 class="font-semibold text-blue-800">Presensi Masuk</h4>
                                <p class="text-xs text-blue-600 mt-1 mb-3">Waktu: 08:50 - 13:00</p>
                                <form action="{{ route('pegawai.kehadiran.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="Hadir">
                                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold shadow-sm transition duration-150">PRESENSI MASUK</button>
                                </form>
                            </div>
                            <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200 text-center flex flex-col justify-between">
                                <h4 class="font-semibold text-indigo-800">Ajukan Izin</h4>
                                <p class="text-xs text-indigo-600 mt-1 mb-3">Sertakan bukti jika ada</p>
                                <button onclick="openIzinModal('Izin')" class="w-full px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 font-semibold shadow-sm transition duration-150">Input Izin</button>
                            </div>
                            <div class="p-4 bg-pink-50 rounded-lg border border-pink-200 text-center flex flex-col justify-between">
                                <h4 class="font-semibold text-pink-800">Ajukan Sakit</h4>
                                <p class="text-xs text-pink-600 mt-1 mb-3">Sertakan surat dokter jika ada</p>
                                <button onclick="openIzinModal('Sakit')" class="w-full px-4 py-2 bg-pink-500 text-white rounded-md hover:bg-pink-600 font-semibold shadow-sm transition duration-150">Input Sakit</button>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                           <div class="p-4 rounded-lg flex items-center @if($absensiHariIni->status == 'Hadir') bg-green-50 border border-green-200 @elseif($absensiHariIni->status == 'Terlambat') bg-yellow-50 border border-yellow-200 @else bg-gray-100 border border-gray-200 @endif">
                                <div class="mr-4">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-full @if($absensiHariIni->status == 'Hadir') bg-green-100 text-green-600 @elseif($absensiHariIni->status == 'Terlambat') bg-yellow-100 text-yellow-600 @else bg-gray-200 text-gray-600 @endif">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold @if($absensiHariIni->status == 'Hadir') text-green-800 @elseif($absensiHariIni->status == 'Terlambat') text-yellow-800 @else text-gray-800 @endif">Presensi Masuk</h4>
                                    @if(in_array($absensiHariIni->status, ['Hadir', 'Terlambat']))
                                    <p class="text-2xl font-bold">{{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }}</p>
                                    <p class="text-sm font-medium">{{ $absensiHariIni->status }}</p>
                                    @else
                                    <p class="text-2xl font-bold">{{ $absensiHariIni->status }}</p>
                                    <p class="text-sm">Tidak perlu presensi</p>
                                    @endif
                                </div>
                            </div>
                            <div class="p-4 rounded-lg flex items-center bg-gray-100 border border-gray-200">
                                 <div class="mr-4">
                                    <div class="flex items-center justify-center h-12 w-12 rounded-full @if($absensiHariIni->jam_pulang) bg-green-100 text-green-600 @else bg-gray-200 text-gray-600 @endif">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m5 4v1a3 3 0 003 3h4a3 3 0 003-3V7a3 3 0 00-3-3H12a3 3 0 00-3 3v1" /></svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Presensi Pulang</h4>
                                    @if($absensiHariIni->jam_pulang)
                                        <p class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') }}</p>
                                        <p class="text-sm font-medium text-green-600">Sudah Selesai</p>
                                    @elseif(in_array($absensiHariIni->status, ['Hadir', 'Terlambat']))
                                        <form action="{{ route('pegawai.kehadiran.store') }}" method="POST" id="pulang-form">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 font-semibold shadow-sm transition duration-150">PRESENSI PULANG</button>
                                        </form>
                                    @else
                                        <p class="text-2xl font-bold text-gray-400">-</p>
                                        <p class="text-sm text-gray-500">Tidak Perlu</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Modal Izin/Sakit --}}
                <div id="izinSakitModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                     <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg text-center leading-6 font-medium text-gray-900" id="modal-title"></h3>
                            <div class="mt-2 px-7 py-3">
                                <form action="{{ route('pegawai.kehadiran.store') }}" method="POST" enctype="multipart/form-data" id="izinSakitForm">
                                    @csrf
                                    <input type="hidden" name="status" id="modal-status" required>
                                    <p id="modal-text" class="text-sm text-gray-500 mb-4 text-center"></p>
                                    <div class="mb-4 text-left">
                                        <label for="bukti" class="block text-sm font-medium text-gray-700">Upload Bukti (Opsional)</label>
                                        <input type="file" name="bukti" id="bukti" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                                    </div>
                                    <div class="mb-4 text-left">
                                        <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Tulis alasan Anda..."></textarea>
                                    </div>
                                    <div class="items-center px-4 py-3">
                                        <button type="submit" id="modal-submit-btn" class="px-4 py-2 w-full bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700"></button>
                                    </div>
                                </form>
                            </div>
                            <div class="text-center px-4 py-2"><button id="cancel-izin-modal-button" class="text-sm text-gray-500 hover:text-gray-700">Batal</button></div>
                        </div>
                    </div>
                </div>

                {{-- Modal Konfirmasi Pulang Awal --}}
                <div id="early-clockout-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0">
                                    <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                </div>
                                <div class="ml-4 text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Pulang Awal</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Anda akan melakukan presensi pulang sebelum jam 17:00. Aksi ini akan tercatat dan dapat mempengaruhi rekapitulasi kehadiran atau perhitungan gaji. Apakah Anda yakin?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="button" id="confirm-early-button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-500 text-white text-base font-medium hover:bg-yellow-600 sm:ml-3 sm:w-auto sm:text-sm">Ya, Tetap Pulang</button>
                                <button type="button" id="cancel-early-button" class="mt-3 inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Riwayat Presensi</h3>
                         <form method="GET" class="flex flex-wrap gap-3">
                            <select name="bulan" class="border-gray-300 rounded-md shadow-sm w-full md:w-auto text-sm">
                                @foreach (['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $key => $val)
                                    <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>{{ $val }}</option>
                                @endforeach
                            </select>
                            <select name="tahun" class="border-gray-300 rounded-md shadow-sm w-full md:w-auto text-sm">
                                @for ($i = now()->year; $i >= now()->year - 5; $i--)
                                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md shadow text-sm">Tampilkan</button>
                        </form>
                    </div>
                    <div class="overflow-x-auto bg-white rounded-lg shadow border">
                        <table class="min-w-full text-sm">
                           <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 border-b text-left font-medium text-gray-600">Tanggal</th>
                                    <th class="px-4 py-3 border-b text-center font-medium text-gray-600">Status</th>
                                    <th class="px-4 py-3 border-b text-center font-medium text-gray-600">Masuk</th>
                                    <th class="px-4 py-3 border-b text-center font-medium text-gray-600">Pulang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($kehadiran as $row)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tanggal)->isoFormat('dddd, D MMMM Y') }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($row->status == 'Hadir') bg-green-100 text-green-800 @elseif($row->status == 'Terlambat') bg-yellow-100 text-yellow-800 @elseif($row->status == 'Sakit') bg-blue-100 text-blue-800 @elseif($row->status == 'Izin') bg-indigo-100 text-indigo-800 @else bg-red-100 text-red-800 @endif">
                                                {{ $row->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center font-mono">{{ $row->jam_masuk ? \Carbon\Carbon::parse($row->jam_masuk)->format('H:i') : '-' }}</td>
                                        <td class="px-4 py-3 text-center font-mono">{{ $row->jam_pulang ? \Carbon\Carbon::parse($row->jam_pulang)->format('H:i') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data presensi pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">{{ $kehadiran->links() }}</div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function openIzinModal(status) {
            document.getElementById('modal-status').value = status;
            document.getElementById('modal-title').innerText = 'Pengajuan ' + status;
            document.getElementById('modal-text').innerText = 'Anda akan mengajukan status ' + status + ' hari ini. Mohon sertakan keterangan dan bukti jika ada.';
            document.getElementById('modal-submit-btn').innerText = 'Kirim Pengajuan ' + status;
            document.getElementById('izinSakitModal').classList.remove('hidden');
        }
        function closeIzinModal() {
            document.getElementById('izinSakitModal').classList.add('hidden');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const clockElement = document.getElementById('realtime-clock');
            if (clockElement) {
                setInterval(() => {
                    const now = new Date();
                    const timeString = now.toLocaleTimeString('en-GB', { timeZone: 'Asia/Jakarta' });
                    clockElement.textContent = timeString;
                }, 1000);
            }

            const izinSakitModal = document.getElementById('izinSakitModal');
            const cancelIzinModalButton = document.getElementById('cancel-izin-modal-button');
            const pulangForm = document.getElementById('pulang-form');
            const earlyClockoutModal = document.getElementById('early-clockout-modal');

            if (cancelIzinModalButton) {
                cancelIzinModalButton.addEventListener('click', closeIzinModal);
            }

            if (pulangForm) {
                const isEarly = {{ $isBeforePulangTime ? 'true' : 'false' }};
                const confirmEarlyBtn = document.getElementById('confirm-early-button');
                const cancelEarlyBtn = document.getElementById('cancel-early-button');

                const openEarlyClockoutModal = () => earlyClockoutModal.classList.remove('hidden');
                const closeEarlyClockoutModal = () => earlyClockoutModal.classList.add('hidden');

                pulangForm.addEventListener('submit', function(e) {
                    if (isEarly) {
                        e.preventDefault();
                        openEarlyClockoutModal();
                    }
                });

                if(confirmEarlyBtn) {
                    confirmEarlyBtn.addEventListener('click', () => pulangForm.submit());
                }
                if(cancelEarlyBtn) {
                    cancelEarlyBtn.addEventListener('click', closeEarlyClockoutModal);
                }
            }
            
            window.addEventListener('click', (event) => {
                if (event.target === izinSakitModal) closeIzinModal();
                if (event.target === earlyClockoutModal) {
                    const closeEarlyClockoutModal = () => earlyClockoutModal.classList.add('hidden');
                    closeEarlyClockoutModal();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>