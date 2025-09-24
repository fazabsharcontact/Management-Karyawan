<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Tim;
use App\Models\User;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data master
        $jabatans = Jabatan::all();
        $tims = Tim::all();
        $usersPegawai = User::where('role', 'pegawai')->get();

        // Membuat data pegawai dari user yang sudah ada
        foreach ($usersPegawai as $user) {
            // Khusus untuk user manager
            if (str_contains($user->username, 'manager')) {
                 Pegawai::create([
                    'user_id' => $user->id,
                    'jabatan_id' => $jabatans->where('nama_jabatan', 'Manager')->first()->id,
                    'tim_id' => $tims->random()->id,
                    'nama' => fake()->name(),
                    'email' => $user->email,
                    'no_hp' => fake()->phoneNumber(),
                    'alamat' => fake()->address(),
                    'tanggal_masuk' => fake()->dateTimeBetween('-2 years', 'now'),
                    'gaji_pokok' => 8000000,
                ]);
                continue; // Lanjut ke user berikutnya
            }

            // Untuk pegawai staff biasa
            Pegawai::create([
                'user_id' => $user->id,
                'jabatan_id' => $jabatans->where('nama_jabatan', '!=', 'Manager')->random()->id,
                'tim_id' => $tims->random()->id,
                'nama' => fake()->name(),
                'email' => $user->email,
                'no_hp' => fake()->phoneNumber(),
                'alamat' => fake()->address(),
                'tanggal_masuk' => fake()->dateTimeBetween('-1 years', '-1 month'),
                'gaji_pokok' => fake()->numberBetween(4500000, 6000000),
            ]);
        }
    }
}
