<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            StrukturOrganisasiSeeder::class,
            PegawaiSeeder::class, // <-- Membuat data pegawai (termasuk admin)
            SisaCutiSeeder::class,
            MasterGajiSeeder::class,
            
            // --- TAMBAHKAN PEMANGGILAN SEEDER BARU DI SINI ---
            BankDetailSeeder::class, // <-- Mengisi detail bank untuk semua pegawai
            
            AktivitasPegawaiSeeder::class,
        ]);
    }
}
