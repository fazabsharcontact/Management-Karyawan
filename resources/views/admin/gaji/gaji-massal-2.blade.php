<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">

                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Tambah Gaji Massal (Langkah 2 dari 2)
                        </h2>
                        <p class="text-gray-500 mt-1">
                            Tentukan komponen gaji dan periksa data pegawai sebelum menyimpan.
                        </p>
                    </div>

                    <form id="form-langkah-2" action="{{ route('admin.gaji-massal.simpan') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                        <input type="hidden" name="tahun" value="{{ $tahun }}">

                        {{-- ===================== RINGKASAN PEGAWAI ===================== --}}
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">
                                Ringkasan Pegawai Terpilih ({{ $pegawais->count() }} orang)
                            </h3>
                            <div class="max-h-60 overflow-y-auto">
                                <ul class="space-y-3 text-sm">
                                    @foreach($pegawais as $pegawai)
                                        <li class="p-3 bg-white border border-gray-100 rounded-lg hover:shadow-sm transition">
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                                                <div>
                                                    <span class="font-medium text-gray-800">{{ $pegawai->nama }}</span>
                                                    <span class="text-gray-600 text-sm">
                                                        (Gaji Pokok: Rp {{ number_format($pegawai->gaji_pokok, 0, ',', '.') }})
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 ml-1">
                                                @if($pegawai->nama_bank && $pegawai->nomor_rekening)
                                                    <span class="font-semibold text-gray-700">Transfer ke:</span>
                                                    {{ $pegawai->nama_bank }} - {{ $pegawai->nomor_rekening }}
                                                @else
                                                    <span class="text-red-500 font-semibold">Peringatan: Informasi bank belum diisi.</span>
                                                @endif
                                            </div>

                                            {{-- Hidden input --}}
                                            <input type="hidden" name="pegawai_gaji[{{ $loop->index }}][pegawai_id]" value="{{ $pegawai->id }}">
                                            <input type="hidden" name="pegawai_gaji[{{ $loop->index }}][gaji_pokok]" value="{{ $pegawai->gaji_pokok }}" class="gaji-pokok-pegawai">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- ===================== TUNJANGAN ===================== --}}
                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center border-b pb-2 mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Tunjangan (Berlaku untuk semua)</h3>
                                <button type="button" id="add-tunjangan" class="bg-gray-900 hover:bg-gray-800 text-white px-3 py-1 rounded-md text-sm shadow transition">
                                    + Tambah Tunjangan
                                </button>
                            </div>
                            <div id="tunjangan-container" class="space-y-3"></div>
                        </div>

                        {{-- ===================== POTONGAN ===================== --}}
                        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center border-b pb-2 mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Potongan (Berlaku untuk semua)</h3>
                                <button type="button" id="add-potongan" class="bg-gray-900 hover:bg-gray-800 text-white px-3 py-1 rounded-md text-sm shadow transition">
                                    + Tambah Potongan
                                </button>
                            </div>
                            <div id="potongan-container" class="space-y-3"></div>
                        </div>

                        {{-- ===================== ESTIMASI TOTAL ===================== --}}
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Estimasi Total Pengeluaran Gaji</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Total Gaji Pokok ({{ $pegawais->count() }} orang):</span>
                                    <span id="summary-total-pokok" class="font-medium text-gray-800">Rp 0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Tunjangan Keseluruhan:</span>
                                    <span id="summary-total-tunjangan" class="text-green-600 font-medium">Rp 0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Potongan Keseluruhan:</span>
                                    <span id="summary-total-potongan" class="text-red-600 font-medium">Rp 0</span>
                                </div>
                                <div class="flex justify-between font-semibold text-base border-t pt-2 mt-2">
                                    <span>Total Gaji Bersih Dikeluarkan:</span>
                                    <span id="summary-grand-total" class="text-gray-900">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        {{-- ===================== TOMBOL AKSI ===================== --}}
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.gaji-massal.langkah1') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg border border-gray-300 transition shadow-sm">
                                Kembali
                            </a>
                            <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg shadow transition">
                                Simpan Semua Gaji
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- ===================== MODAL KONFIRMASI ===================== --}}
    <div id="confirmation-modal-save" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Penyimpanan</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Anda akan menyimpan data gaji untuk {{ $pegawais->count() }} pegawai. Apakah Anda yakin ingin melanjutkan?
                </p>

                <div class="mt-6 flex flex-col gap-2">
                    <button id="confirm-save-yes"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium shadow-sm transition">
                        Ya, Simpan Semua
                    </button>
                    <button id="confirm-save-no"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md border border-gray-300 font-medium shadow-sm transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== SCRIPT ===================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- (kode JS asli kamu tetap dipertahankan tanpa perubahan) ---
            const masterTunjangans = @json($masterTunjangans);
            const masterPotongans = @json($masterPotongans);
            const jumlahPegawai = {{ $pegawais->count() }};
            const form = document.getElementById('form-langkah-2');
            const modal = document.getElementById('confirmation-modal-save');
            const confirmYes = document.getElementById('confirm-save-yes');
            const confirmNo = document.getElementById('confirm-save-no');
            
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency', currency: 'IDR', minimumFractionDigits: 0
            });

            function calculateMassalTotals() {
                let totalGajiPokok = 0;
                document.querySelectorAll('.gaji-pokok-pegawai').forEach(input => {
                    totalGajiPokok += parseFloat(input.value) || 0;
                });

                let totalTunjanganPerOrang = 0;
                document.querySelectorAll('#tunjangan-container .jumlah-input').forEach(input => {
                    totalTunjanganPerOrang += parseFloat(input.value) || 0;
                });

                let totalPotonganPerOrang = 0;
                document.querySelectorAll('#potongan-container .jumlah-input').forEach(input => {
                    totalPotonganPerOrang += parseFloat(input.value) || 0;
                });

                const totalTunjanganKeseluruhan = totalTunjanganPerOrang * jumlahPegawai;
                const totalPotonganKeseluruhan = totalPotonganPerOrang * jumlahPegawai;
                const grandTotal = totalGajiPokok + totalTunjanganKeseluruhan - totalPotonganKeseluruhan;

                document.getElementById('summary-total-pokok').textContent = formatter.format(totalGajiPokok);
                document.getElementById('summary-total-tunjangan').textContent = formatter.format(totalTunjanganKeseluruhan);
                document.getElementById('summary-total-potongan').textContent = formatter.format(totalPotonganKeseluruhan);
                document.getElementById('summary-grand-total').textContent = formatter.format(grandTotal);
            }

            let tunjanganIndex = 0;
            let potonganIndex = 0;

            function handleDropdownChange(event) {
                const selectedOption = event.target.options[event.target.selectedIndex];
                const defaultValue = selectedOption.getAttribute('data-default');
                const jumlahInput = event.target.closest('.grid').querySelector('.jumlah-input');
                if (defaultValue) {
                    jumlahInput.value = defaultValue;
                } else {
                    jumlahInput.value = '';
                }
                calculateMassalTotals();
            }

            function addNewRow(type) {
                const isTunjangan = type === 'tunjangan';
                const container = document.getElementById(isTunjangan ? 'tunjangan-container' : 'potongan-container');
                const masterData = isTunjangan ? masterTunjangans : masterPotongans;
                const index = isTunjangan ? tunjanganIndex++ : potonganIndex++;
                const namePrefix = isTunjangan ? 'tunjangans' : 'potongans';
                const masterIdName = isTunjangan ? 'master_tunjangan_id' : 'master_potongan_id';
                const masterName = isTunjangan ? 'nama_tunjangan' : 'nama_potongan';
                const selectClass = isTunjangan ? 'tunjangan-select' : 'potongan-select';
                const newRow = document.createElement('div');
                newRow.classList.add('grid', 'grid-cols-1', 'md:grid-cols-3', 'gap-4', 'items-center', 'p-3', 'bg-gray-50', 'border', 'border-gray-200', 'rounded-lg', 'shadow-sm');

                let optionsHtml = masterData.map(item => `<option value="${item.id}" data-default="${item.jumlah_default || ''}">${item[masterName]}</option>`).join('');
                newRow.innerHTML = `
                    <div>
                        <select name="${namePrefix}[${index}][${masterIdName}]" class="${selectClass} border-gray-300 focus:border-gray-400 focus:ring focus:ring-gray-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                            <option value="">-- Pilih ${isTunjangan ? 'Tunjangan' : 'Potongan'} --</option>
                            ${optionsHtml}
                        </select>
                    </div>
                    <div>
                        <input type="number" name="${namePrefix}[${index}][jumlah]" class="jumlah-input border-gray-300 focus:border-gray-400 focus:ring focus:ring-gray-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" placeholder="Jumlah (Rp)" required>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" name="${namePrefix}[${index}][keterangan]" class="border-gray-300 focus:border-gray-400 focus:ring focus:ring-gray-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" placeholder="Keterangan">
                        <button type="button" class="remove-row bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm shadow-sm">&times;</button>
                    </div>
                `;
                container.appendChild(newRow);
                
                newRow.querySelector(`.${selectClass}`).addEventListener('change', handleDropdownChange);
                newRow.querySelector('.jumlah-input').addEventListener('input', calculateMassalTotals);
                newRow.querySelector('.remove-row').addEventListener('click', function() {
                    newRow.remove();
                    calculateMassalTotals();
                });
            }
            
            document.getElementById('add-tunjangan').addEventListener('click', () => addNewRow('tunjangan'));
            document.getElementById('add-potongan').addEventListener('click', () => addNewRow('potongan'));
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                modal.classList.remove('hidden');
            });
            confirmYes.addEventListener('click', function() {
                form.submit();
            });
            confirmNo.addEventListener('click', function() {
                modal.classList.add('hidden');
            });

            calculateMassalTotals();
        });
    </script>
</x-app-layout>