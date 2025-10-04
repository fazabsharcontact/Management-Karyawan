<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Database\Seeder;

class BankDetailSeeder extends Seeder
{
    public function run(): void
    {
        $banks = ['BCA', 'Bank Mandiri', 'BNI', 'BRI', 'CIMB Niaga'];
        $pegawais = Pegawai::all();

        foreach ($pegawais as $pegawai) {
            $pegawai->update([
                'nama_bank' => fake()->randomElement($banks),
                'nomor_rekening' => fake()->numerify('##########'),
            ]);
        }
    }
}