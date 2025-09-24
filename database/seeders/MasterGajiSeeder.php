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
        // Master Tunjangan
        MasterTunjangan::create(['nama_tunjangan' => 'Tunjangan Transportasi']);
        MasterTunjangan::create(['nama_tunjangan' => 'Tunjangan Makan']);
        MasterTunjangan::create(['nama_tunjangan' => 'Tunjangan Komunikasi']);
        MasterTunjangan::create(['nama_tunjangan' => 'Tunjangan Lembur']);

        // Master Potongan
        MasterPotongan::create(['nama_potongan' => 'Potongan Keterlambatan']);
        MasterPotongan::create(['nama_potongan' => 'Potongan BPJS Kesehatan']);
        MasterPotongan::create(['nama_potongan' => 'Potongan PPh 21']);
    }
}
