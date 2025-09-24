<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat Admin Utama
        User::create([
            'username' => 'admin',
            'email' => 'ad@contoh.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // 2. Membuat User lain yang akan menjadi Manager
        User::create([
            'username' => 'budi_manager',
            'email' => 'budi.manager@contoh.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'email_verified_at' => now(),
        ]);

        // 3. Membuat beberapa user dummy untuk pegawai
        User::factory(10)->create();
    }
}
