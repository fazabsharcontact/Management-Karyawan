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
        // Memanggil seeder lain secara berurutan
        // Urutan ini penting untuk menjaga integritas relasi antar tabel
        $this->call([
            UserSeeder::class,
            StrukturOrganisasiSeeder::class,
            PegawaiSeeder::class,
            MasterGajiSeeder::class,
            AktivitasPegawaiSeeder::class,
        ]);
    }
}
