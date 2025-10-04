<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Tambah Data Gaji
        </h2>
    </x-slot>

    <div class="p-6">
        <form id="form-gaji" action="{{ route('admin.gaji.store') }}" method="POST" class="space-y-6">
            @csrf
            {{-- Bagian Informasi Utama --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold border-b pb-2 mb-4">Informasi Utama</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="pegawai_id" class="block font-medium text-sm text-gray-700">Pegawai</label>
                        <select name="pegawai_id" id="pegawai_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($pegawais as $p)
                                {{-- PERUBAHAN 1: Tambahkan data- atibut untuk bank --}}
                                <option value="{{ $p->id }}" 
                                        data-gaji-pokok="{{ $p->gaji_pokok }}"
                                        data-nama-bank="{{ $p->nama_bank ?? '' }}"
                                        data-nomor-rekening="{{ $p->nomor_rekening ?? '' }}">
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="bulan" class="block font-medium text-sm text-gray-700">Bulan</label>
                        <select name="bulan" id="bulan" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" required>
                            @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="tahun" class="block font-medium text-sm text-gray-700">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" value="{{ date('Y') }}" required>
                    </div>
                    <div>
                        <label for="gaji_pokok" class="block font-medium text-sm text-gray-700">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" id="gaji_pokok" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Pilih pegawai..." required>
                    </div>
                </div>

                {{-- TAMBAHAN BARU: INFORMASI BANK (READONLY) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t">
                     <div>
                        <label for="nama_bank" class="block font-medium text-sm text-gray-700">Nama Bank</label>
                        <input type="text" id="nama_bank" class="bg-gray-100 border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Pilih pegawai untuk melihat" readonly>
                    </div>
                     <div>
                        <label for="nomor_rekening" class="block font-medium text-sm text-gray-700">Nomor Rekening</label>
                        <input type="text" id="nomor_rekening" class="bg-gray-100 border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Pilih pegawai untuk melihat" readonly>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center border-b pb-2 mb-4">
                    <h3 class="text-lg font-semibold">Tunjangan</h3>
                    <button type="button" id="add-tunjangan" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Tambah Tunjangan</button>
                </div>
                <div id="tunjangan-container" class="space-y-3"></div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center border-b pb-2 mb-4">
                    <h3 class="text-lg font-semibold">Potongan</h3>
                    <button type="button" id="add-potongan" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Tambah Potongan</button>
                </div>
                <div id="potongan-container" class="space-y-3"></div>
            </div>
            
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold border-b pb-2 mb-4">Ringkasan Gaji</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Gaji Pokok:</span> <span id="summary-gaji-pokok">Rp 0</span></div>
                    <div class="flex justify-between"><span>Total Tunjangan:</span> <span id="summary-total-tunjangan" class="text-green-600">Rp 0</span></div>
                    <div class="flex justify-between"><span>Total Potongan:</span> <span id="summary-total-potongan" class="text-red-600">Rp 0</span></div>
                    <div class="flex justify-between font-bold text-base border-t pt-2 mt-2"><span>Gaji Bersih (Estimasi):</span> <span id="summary-gaji-bersih">Rp 0</span></div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">Simpan</button>
                <a href="{{ route('admin.gaji.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow">Batal</a>
            </div>
        </form>
    </div>

    {{-- Modal Konfirmasi --}}
    <div id="confirmation-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0">
                        <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    </div>
                    <div class="ml-4 text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Konfirmasi Penyimpanan</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="modal-message">Apakah Anda yakin ingin menyimpan data gaji ini?</p>
                            <div id="warning-pegawai" class="hidden mt-4">
                                <p class="text-sm font-bold text-red-700">Peringatan: Data gaji untuk pegawai ini sudah ada pada periode yang dipilih.</p>
                                <ul id="pegawai-list" class="mt-2 list-disc pl-5 text-sm text-red-600">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirm-yes" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Simpan
                    </button>
                    <button type="button" id="confirm-no" class="mt-3 inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const masterTunjangans = @json($masterTunjangans);
            const masterPotongans = @json($masterPotongans);
            const form = document.getElementById('form-gaji');
            
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

            document.getElementById('gaji_pokok').addEventListener('input', calculateTotals);

            // PERUBAHAN 2: Sempurnakan event listener
            document.getElementById('pegawai_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                // Ambil semua data dari atribut
                const gajiPokok = selectedOption.getAttribute('data-gaji-pokok');
                const namaBank = selectedOption.getAttribute('data-nama-bank');
                const nomorRekening = selectedOption.getAttribute('data-nomor-rekening');
                
                // Isi field Gaji Pokok
                document.getElementById('gaji_pokok').value = gajiPokok || '';

                // Isi field Informasi Bank
                document.getElementById('nama_bank').value = namaBank || '';
                document.getElementById('nomor_rekening').value = nomorRekening || '';

                // Hitung ulang total
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
                        <select name="${namePrefix}[${index}][${masterIdName}]" class="${selectClass} border-gray-300 rounded-md shadow-sm mt-1 w-full" required>
                            <option value="">-- Pilih ${isTunjangan ? 'Tunjangan' : 'Potongan'} --</option>
                            ${optionsHtml}
                        </select>
                    </div>
                    <div>
                        <input type="number" name="${namePrefix}[${index}][jumlah]" class="jumlah-input border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Jumlah (Rp)" required>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" name="${namePrefix}[${index}][keterangan]" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Keterangan">
                        <button type="button" class="remove-row bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">&times;</button>
                    </div>
                `;
                container.appendChild(newRow);
                
                newRow.querySelector(`.${selectClass}`).addEventListener('change', handleDropdownChange);
                newRow.querySelector('.jumlah-input').addEventListener('input', calculateTotals);
                newRow.querySelector('.remove-row').addEventListener('click', function() {
                    newRow.remove(); 
                    calculateTotals();
                });
                
                calculateTotals();
            }
            
            document.getElementById('add-tunjangan').addEventListener('click', () => addNewRow('tunjangan'));
            document.getElementById('add-potongan').addEventListener('click', () => addNewRow('potongan'));

            // Logika untuk popup konfirmasi
            const modal = document.getElementById('confirmation-modal');
            const confirmYes = document.getElementById('confirm-yes');
            const confirmNo = document.getElementById('confirm-no');
            const modalTitle = document.getElementById('modal-title');
            const modalMessage = document.getElementById('modal-message');
            const warningPegawai = document.getElementById('warning-pegawai');
            const pegawaiList = document.getElementById('pegawai-list');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const pegawaiId = formData.get('pegawai_id');

                if (!pegawaiId) {
                    // Simple browser alert for quick feedback, can be replaced with a nicer modal
                    const customAlert = document.createElement('div');
                    customAlert.innerHTML = `
                        <div style="position: fixed; top: 1rem; right: 1rem; background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.25rem; border: 1px solid #f5c6cb; z-index: 1000;">
                            Silakan pilih pegawai terlebih dahulu.
                        </div>
                    `;
                    document.body.appendChild(customAlert);
                    setTimeout(() => customAlert.remove(), 3000);
                    return;
                }
                
                modalTitle.textContent = 'Konfirmasi Penyimpanan';
                modalMessage.textContent = 'Apakah Anda yakin ingin menyimpan data gaji ini?';
                warningPegawai.classList.add('hidden');
                pegawaiList.innerHTML = '';
                modal.classList.remove('hidden');
                
                try {
                    const response = await fetch("{{ route('admin.gaji.cek') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            pegawai_id: pegawaiId,
                            bulan: formData.get('bulan'),
                            tahun: formData.get('tahun')
                        })
                    });
                    
                    const result = await response.json();

                    if (result.exists && result.pegawai) {
                        modalTitle.textContent = 'Peringatan: Data Gaji Ganda';
                        modalMessage.textContent = 'Pegawai berikut sudah memiliki data gaji pada periode ini. Apakah Anda yakin ingin membuat data gaji baru?';
                        warningPegawai.classList.remove('hidden');
                        pegawaiList.innerHTML = `<li>${result.pegawai.nama} (${result.pegawai.jabatan?.nama_jabatan || ''})</li>`;
                    }
                } catch (error) {
                    console.error('Gagal melakukan pengecekan:', error);
                }
            });

            confirmYes.addEventListener('click', () => form.submit());
            confirmNo.addEventListener('click', () => modal.classList.add('hidden'));

            calculateTotals();
        });
    </script>
</x-app-layout>

