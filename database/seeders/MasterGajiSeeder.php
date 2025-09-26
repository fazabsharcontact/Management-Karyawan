<?php

namespace Database\Seeders;

use App\Models\MasterPotongan;
use App\Models\MasterTunjangan;
use Illuminate\Database\Seeder;

class MasterGajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Master Tunjangan dengan nilai default
        MasterTunjangan::create([
            'nama_tunjangan' => 'Tunjangan Transportasi',
            'deskripsi' => 'Tunjangan untuk biaya transportasi bulanan.',
            'jumlah_default' => 500000
        ]);
        MasterTunjangan::create([
            'nama_tunjangan' => 'Tunjangan Makan',
            'deskripsi' => 'Tunjangan untuk biaya makan harian.',
            'jumlah_default' => 440000 // Contoh: 20.000/hari * 22 hari kerja
        ]);
        MasterTunjangan::create([
            'nama_tunjangan' => 'Tunjangan Komunikasi',
            'deskripsi' => 'Tunjangan untuk pulsa atau paket data.',
            'jumlah_default' => 150000
        ]);
        MasterTunjangan::create([
            'nama_tunjangan' => 'Bonus Kinerja',
            'deskripsi' => 'Bonus yang diberikan berdasarkan pencapaian target.',
            'jumlah_default' => null // Tidak ada nilai default, diisi manual
        ]);

        // Master Potongan dengan nilai default
        MasterPotongan::create([
            'nama_potongan' => 'Potongan Keterlambatan',
            'deskripsi' => 'Denda per kejadian keterlambatan masuk kerja.',
            'jumlah_default' => 50000
        ]);
        MasterPotongan::create([
            'nama_potongan' => 'Potongan BPJS Kesehatan',
            'deskripsi' => 'Iuran bulanan untuk BPJS Kesehatan (1% dari gaji).',
            'jumlah_default' => 50000 // Contoh nilai default
        ]);
        MasterPotongan::create([
            'nama_potongan' => 'Potongan PPh 21',
            'deskripsi' => 'Pajak penghasilan sesuai peraturan pemerintah.',
            'jumlah_default' => null // Tidak ada nilai default, dihitung manual
        ]);
    }
}

