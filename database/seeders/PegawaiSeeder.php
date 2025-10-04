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
        // --- 1. MEMBUAT DATA PEGAWAI KHUSUS UNTUK ADMIN ---
        $adminUser = User::where('role', 'admin')->first();
        $jabatanManager = Jabatan::where('nama_jabatan', 'Manager')->first();
        $timRekrutmen = Tim::where('nama_tim', 'Rekrutmen')->first();

        // Pastikan user admin dan data master (jabatan, tim) ditemukan
        if ($adminUser && $jabatanManager && $timRekrutmen) {
            Pegawai::create([
                'user_id'       => $adminUser->id,
                'jabatan_id'    => $jabatanManager->id,
                'tim_id'        => $timRekrutmen->id,
                'nama'          => 'Administrator',
                'email'         => $adminUser->email,
                'no_hp'         => fake()->phoneNumber(),
                'alamat'        => fake()->address(),
                'tanggal_masuk' => now()->subYears(3), // Diasumsikan admin sudah lama bergabung
                'gaji_pokok'    => 15000000,
            ]);
        }
        
        // --- 2. MEMBUAT DATA PEGAWAI LAINNYA (LOGIKA LAMA ANDA) ---
        $jabatans = Jabatan::all();
        $tims = Tim::all();
        $usersPegawai = User::where('role', 'pegawai')->get();

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
