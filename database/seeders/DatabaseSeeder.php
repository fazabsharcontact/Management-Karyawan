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
            PegawaiSeeder::class,
            SisaCutiSeeder::class,
            MasterGajiSeeder::class,
            AktivitasPegawaiSeeder::class,
        ]);
    }
}