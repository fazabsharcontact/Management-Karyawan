<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Tambah Data Gaji
        </h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('admin.gaji.store') }}" method="POST" class="space-y-6">
            @csrf
            {{-- Bagian Informasi Utama --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold border-b pb-2 mb-4">Informasi Utama</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Pilih Pegawai -->
                    <div>
                        <label for="pegawai_id" class="block font-medium text-sm text-gray-700">Pegawai</label>
                        <select name="pegawai_id" id="pegawai_id" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($pegawais as $p)
                            <option value="{{ $p->id }}" data-gaji-pokok="{{ $p->gaji_pokok }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bulan -->
                    <div>
                        <label for="bulan" class="block font-medium text-sm text-gray-700">Bulan</label>
                        <select name="bulan" id="bulan" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" required>
                            @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label for="tahun" class="block font-medium text-sm text-gray-700">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" value="{{ date('Y') }}" required>
                    </div>

                    <!-- Gaji Pokok -->
                    <div>
                        <label for="gaji_pokok" class="block font-medium text-sm text-gray-700">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" id="gaji_pokok" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" placeholder="Gaji Pokok" required>
                    </div>
                </div>
            </div>

            <!-- Tunjangan Dinamis -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center border-b pb-2 mb-4">
                    <h3 class="text-lg font-semibold">Tunjangan</h3>
                    <button type="button" id="add-tunjangan" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Tambah Tunjangan</button>
                </div>
                <div id="tunjangan-container" class="space-y-3">
                    <!-- Baris tunjangan akan ditambahkan di sini oleh JavaScript -->
                </div>
            </div>

            <!-- Potongan Dinamis -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center border-b pb-2 mb-4">
                    <h3 class="text-lg font-semibold">Potongan</h3>
                    <button type="button" id="add-potongan" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Tambah Potongan</button>
                </div>
                <div id="potongan-container" class="space-y-3">
                    <!-- Baris potongan akan ditambahkan di sini oleh JavaScript -->
                </div>
            </div>
            
            <!-- Ringkasan Gaji -->
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                 <h3 class="text-lg font-semibold border-b pb-2 mb-4">Ringkasan Gaji</h3>
                 <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Gaji Pokok:</span> <span id="summary-gaji-pokok">Rp 0</span></div>
                    <div class="flex justify-between"><span>Total Tunjangan:</span> <span id="summary-total-tunjangan" class="text-green-600">Rp 0</span></div>
                    <div class="flex justify-between"><span>Total Potongan:</span> <span id="summary-total-potongan" class="text-red-600">Rp 0</span></div>
                    <div class="flex justify-between font-bold text-base border-t pt-2 mt-2"><span>Gaji Bersih (Estimasi):</span> <span id="summary-gaji-bersih">Rp 0</span></div>
                 </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">Simpan</button>
                <a href="{{ route('admin.gaji.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">Batal</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const masterTunjangans = @json($masterTunjangans);
            const masterPotongans = @json($masterPotongans);
            const form = document.querySelector('form');

            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency', currency: 'IDR', minimumFractionDigits: 0
            });

            function calculateTotals() {
                const gajiPokok = parseFloat(document.getElementById('gaji_pokok').value) || 0;
                let totalTunjangan = 0;
                let totalPotongan = 0;
                document.querySelectorAll('#tunjangan-container .jumlah-input').forEach(input => totalTunjangan += parseFloat(input.value) || 0);
                document.querySelectorAll('#potongan-container .jumlah-input').forEach(input => totalPotongan += parseFloat(input.value) || 0);
                const gajiBersih = gajiPokok + totalTunjangan - totalPotongan;
                document.getElementById('summary-gaji-pokok').textContent = formatter.format(gajiPokok);
                document.getElementById('summary-total-tunjangan').textContent = formatter.format(totalTunjangan);
                document.getElementById('summary-total-potongan').textContent = formatter.format(totalPotongan);
                document.getElementById('summary-gaji-bersih').textContent = formatter.format(gajiBersih);
            }

            form.addEventListener('input', calculateTotals);
            form.addEventListener('change', calculateTotals);

            document.getElementById('pegawai_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const gajiPokok = selectedOption.getAttribute('data-gaji-pokok');
                document.getElementById('gaji_pokok').value = gajiPokok || '';
                calculateTotals();
            });

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
                calculateTotals();
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
                
                // --- PERBAIKAN: Menambahkan event listener langsung ke tombol hapus ---
                newRow.querySelector('.remove-row').addEventListener('click', function() {
                    newRow.remove(); // Langsung hapus elemen baris ini
                    calculateTotals(); // Hitung ulang totalnya
                });
                
                calculateTotals();
            }
            
            document.getElementById('add-tunjangan').addEventListener('click', () => addNewRow('tunjangan'));
            document.getElementById('add-potongan').addEventListener('click', () => addNewRow('potongan'));
            
            // --- DIHAPUS: Event listener yang didelegasikan ke form tidak lagi diperlukan untuk tombol hapus ---
            /*
            form.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('.grid').remove();
                    calculateTotals();
                }
            });
            */

            calculateTotals();
        });
    </script>
</x-app-layout>

