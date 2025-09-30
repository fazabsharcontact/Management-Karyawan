<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\SisaCuti;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SisaCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder ini harus dijalankan SETELAH PegawaiSeeder.
     */
    public function run(): void
    {
        // Ambil semua pegawai yang sudah ada
        $pegawais = Pegawai::all();

        foreach ($pegawais as $pegawai) {
            // Buat entri sisa cuti untuk setiap pegawai
            SisaCuti::create([
                'pegawai_id' => $pegawai->id,
                'sisa_cuti'  => 12, // Nilai awal
            ]);
        }
    }
}
