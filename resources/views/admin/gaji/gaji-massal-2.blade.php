<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Gaji Massal (Langkah 2 dari 2): Tentukan Komponen Gaji
        </h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('admin.gaji-massal.simpan') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="bulan" value="{{ $bulan }}">
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold border-b pb-2 mb-4">Ringkasan Pegawai Terpilih ({{ $pegawais->count() }} orang)</h3>
                <div class="max-h-40 overflow-y-auto">
                    <ul class="list-disc pl-5 text-sm text-gray-600">
                        @foreach($pegawais as $pegawai)
                            <li>{{ $pegawai->nama }} (Gaji Pokok: Rp {{ number_format($pegawai->gaji_pokok, 0, ',', '.') }})</li>
                            {{-- Hidden input untuk mengirim data gaji setiap pegawai --}}
                            <input type="hidden" name="pegawai_gaji[{{ $loop->index }}][pegawai_id]" value="{{ $pegawai->id }}">
                            <input type="hidden" name="pegawai_gaji[{{ $loop->index }}][gaji_pokok]" value="{{ $pegawai->gaji_pokok }}" class="gaji-pokok-pegawai">
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center border-b pb-2 mb-4">
                    <h3 class="text-lg font-semibold">Tunjangan (Berlaku untuk semua)</h3>
                    <button type="button" id="add-tunjangan" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Tambah Tunjangan</button>
                </div>
                <div id="tunjangan-container" class="space-y-3"></div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center border-b pb-2 mb-4">
                    <h3 class="text-lg font-semibold">Potongan (Berlaku untuk semua)</h3>
                    <button type="button" id="add-potongan" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Tambah Potongan</button>
                </div>
                <div id="potongan-container" class="space-y-3"></div>
            </div>

            {{-- --- BAGIAN BARU: RINGKASAN REAL-TIME --- --}}
            <div class="bg-gray-50 p-6 rounded-lg shadow border border-gray-200">
                 <h3 class="text-lg font-semibold border-b pb-2 mb-4">Estimasi Total Pengeluaran Gaji</h3>
                 <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Total Gaji Pokok ({{ $pegawais->count() }} orang):</span> <span id="summary-total-pokok" class="font-medium">Rp 0</span></div>
                    <div class="flex justify-between"><span>Total Tunjangan Keseluruhan:</span> <span id="summary-total-tunjangan" class="text-green-600 font-medium">Rp 0</span></div>
                    <div class="flex justify-between"><span>Total Potongan Keseluruhan:</span> <span id="summary-total-potongan" class="text-red-600 font-medium">Rp 0</span></div>
                    <div class="flex justify-between font-bold text-base border-t pt-2 mt-2"><span>Total Gaji Bersih Dikeluarkan:</span> <span id="summary-grand-total" class="text-blue-700">Rp 0</span></div>
                 </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">Simpan Semua Gaji</button>
                <a href="{{ route('admin.gaji-massal.langkah1') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">Kembali</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const masterTunjangans = @json($masterTunjangans);
            const masterPotongans = @json($masterPotongans);
            const jumlahPegawai = {{ $pegawais->count() }};
            
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
                newRow.classList.add('grid', 'grid-cols-1', 'md:grid-cols-3', 'gap-4', 'items-center', 'p-2', 'border', 'rounded-md');
                let optionsHtml = masterData.map(item => `<option value="${item.id}" data-default="${item.jumlah_default || ''}">${item[masterName]}</option>`).join('');
                newRow.innerHTML = `
                    <div>
                        <select name="${namePrefix}[${index}][${masterIdName}]" class="${selectClass} border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                            <option value="">-- Pilih ${isTunjangan ? 'Tunjangan' : 'Potongan'} --</option>
                            ${optionsHtml}
                        </select>
                    </div>
                    <div>
                        <input type="number" name="${namePrefix}[${index}][jumlah]" class="jumlah-input border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" placeholder="Jumlah (Rp)" required>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" name="${namePrefix}[${index}][keterangan]" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" placeholder="Keterangan">
                        <button type="button" class="remove-row bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">&times;</button>
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

            // Kalkulasi awal saat halaman dimuat
            calculateMassalTotals();
        });
    </script>
</x-app-layout>