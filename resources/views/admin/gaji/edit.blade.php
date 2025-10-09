<x-app-layout>
    <div class="min-h-screen bg-white p-10">
        <div class="max-w-5xl mx-auto space-y-8">
            <!-- Header -->
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Data Gaji untuk: {{ $gaji->pegawai->nama }}</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Perbarui detail gaji pegawai termasuk tunjangan dan potongan secara lengkap.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <form id="form-gaji-edit" action="{{ route('admin.gaji.update', $gaji->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Utama -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Informasi Utama</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="pegawai_id" class="block font-medium text-sm text-gray-700">Pegawai</label>
                                <select name="pegawai_id" id="pegawai_id" class="border-gray-300 rounded-md shadow-sm mt-1 w-full bg-gray-100" required disabled>
                                    @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}" data-gaji-pokok="{{ $p->gaji_pokok }}" {{ $gaji->pegawai_id == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="pegawai_id" value="{{ $gaji->pegawai_id }}">
                            </div>

                            <div>
                                <label for="bulan" class="block font-medium text-sm text-gray-700">Bulan</label>
                                <select name="bulan" id="bulan" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" required>
                                    @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('bulan', $gaji->bulan) == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                    </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="tahun" class="block font-medium text-sm text-gray-700">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" value="{{ old('tahun', $gaji->tahun) }}" required>
                            </div>

                            <div>
                                <label for="gaji_pokok" class="block font-medium text-sm text-gray-700">Gaji Pokok (Rp)</label>
                                <input type="number" name="gaji_pokok" id="gaji_pokok" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" value="{{ old('gaji_pokok', $gaji->gaji_pokok) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Tunjangan -->
                    <div>
                        <div class="flex justify-between items-center border-b pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Tunjangan</h3>
                            <button type="button" id="add-tunjangan" class="bg-gray-200 hover:bg-gray-300 text-black px-3 py-1.5 rounded-md text-sm shadow-sm">
                                + Tambah Tunjangan
                            </button>
                        </div>
                        <div id="tunjangan-container" class="space-y-3">
                            @foreach($gaji->tunjanganDetails as $index => $detail)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-3 border border-gray-200 rounded-md bg-gray-50">
                                <div>
                                    <select name="tunjangans[{{ $index }}][master_tunjangan_id]" class="tunjangan-select border-gray-300 rounded-md shadow-sm mt-1 w-full" required>
                                        <option value="">-- Pilih Tunjangan --</option>
                                        @foreach($masterTunjangans as $t)
                                        <option value="{{ $t->id }}" data-default="{{ $t->jumlah_default ?? '' }}" {{ $detail->master_tunjangan_id == $t->id ? 'selected' : '' }}>
                                            {{ $t->nama_tunjangan }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <input type="number" name="tunjangans[{{ $index }}][jumlah]" class="jumlah-input border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Jumlah (Rp)" value="{{ $detail->jumlah }}" required>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="text" name="tunjangans[{{ $index }}][keterangan]" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Keterangan (Opsional)" value="{{ $detail->keterangan }}">
                                    <button type="button" class="remove-row bg-gray-900 hover:bg-gray-800 text-white px-3 py-1 rounded text-sm shadow-sm">&times;</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Potongan -->
                    <div>
                        <div class="flex justify-between items-center border-b pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Potongan</h3>
                            <button type="button" id="add-potongan" class="bg-gray-200 hover:bg-gray-300 text-black px-3 py-1.5 rounded-md text-sm shadow-sm">
                                + Tambah Potongan
                            </button>
                        </div>
                        <div id="potongan-container" class="space-y-3">
                            @foreach($gaji->potonganDetails as $index => $detail)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-3 border border-gray-200 rounded-md bg-gray-50">
                                <div>
                                    <select name="potongans[{{ $index }}][master_potongan_id]" class="potongan-select border-gray-300 rounded-md shadow-sm mt-1 w-full" required>
                                        <option value="">-- Pilih Potongan --</option>
                                        @foreach($masterPotongans as $p)
                                        <option value="{{ $p->id }}" data-default="{{ $p->jumlah_default ?? '' }}" {{ $detail->master_potongan_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_potongan }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <input type="number" name="potongans[{{ $index }}][jumlah]" class="jumlah-input border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Jumlah (Rp)" value="{{ $detail->jumlah }}" required>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="text" name="potongans[{{ $index }}][keterangan]" class="border-gray-300 rounded-md shadow-sm mt-1 w-full" placeholder="Keterangan (Opsional)" value="{{ $detail->keterangan }}">
                                    <button type="button" class="remove-row bg-gray-900 hover:bg-gray-800 text-white px-3 py-1 rounded text-sm shadow-sm">&times;</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ringkasan -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Ringkasan Gaji</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span>Gaji Pokok:</span> <span id="summary-gaji-pokok">Rp 0</span></div>
                            <div class="flex justify-between"><span>Total Tunjangan:</span> <span id="summary-total-tunjangan" class="text-green-600">Rp 0</span></div>
                            <div class="flex justify-between"><span>Total Potongan:</span> <span id="summary-total-potongan" class="text-red-600">Rp 0</span></div>
                            <div class="flex justify-between font-semibold text-base border-t pt-3 mt-2"><span>Gaji Bersih (Estimasi):</span> <span id="summary-gaji-bersih">Rp 0</span></div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.gaji.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-sm font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script perhitungan otomatis tetap sama --}}
    <script>
        // (salin seluruh script JavaScript dari versi sebelumnya, tanpa diubah)
        document.addEventListener('DOMContentLoaded', function() {
            const masterTunjangans = @json($masterTunjangans);
            const masterPotongans = @json($masterPotongans);
            const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

            function calculateTotals() {
                const gajiPokok = parseFloat(document.getElementById('gaji_pokok').value) || 0;
                let totalTunjangan = 0;
                let totalPotongan = 0;
                document.querySelectorAll('#tunjangan-container .jumlah-input').forEach(i => totalTunjangan += parseFloat(i.value) || 0);
                document.querySelectorAll('#potongan-container .jumlah-input').forEach(i => totalPotongan += parseFloat(i.value) || 0);
                const bersih = gajiPokok + totalTunjangan - totalPotongan;
                document.getElementById('summary-gaji-pokok').textContent = formatter.format(gajiPokok);
                document.getElementById('summary-total-tunjangan').textContent = formatter.format(totalTunjangan);
                document.getElementById('summary-total-potongan').textContent = formatter.format(totalPotongan);
                document.getElementById('summary-gaji-bersih').textContent = formatter.format(bersih);
            }

            function setupRow(el) {
                el.querySelector('.jumlah-input')?.addEventListener('input', calculateTotals);
                el.querySelector('.remove-row')?.addEventListener('click', () => { el.remove(); calculateTotals(); });
            }

            document.querySelectorAll('#tunjangan-container > div, #potongan-container > div').forEach(setupRow);
            document.getElementById('gaji_pokok').addEventListener('input', calculateTotals);
            calculateTotals();
        });
    </script>
</x-app-layout>