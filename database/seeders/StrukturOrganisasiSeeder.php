<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Tim;
use Illuminate\Database\Seeder;

class StrukturOrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Jabatan
        $manager = Jabatan::create(['nama_jabatan' => 'Manager']);
        $supervisor = Jabatan::create(['nama_jabatan' => 'Supervisor']);
        $staff = Jabatan::create(['nama_jabatan' => 'Staff']);

        // Membuat Divisi
        $it = Divisi::create(['nama_divisi' => 'Teknologi Informasi']);
        $hrd = Divisi::create(['nama_divisi' => 'Sumber Daya Manusia']);
        $marketing = Divisi::create(['nama_divisi' => 'Pemasaran']);

        // Membuat Tim
        Tim::create(['divisi_id' => $it->id, 'nama_tim' => 'Software Development']);
        Tim::create(['divisi_id' => $it->id, 'nama_tim' => 'IT Support']);
        Tim::create(['divisi_id' => $marketing->id, 'nama_tim' => 'Digital Marketing']);
        Tim::create(['divisi_id' => $hrd->id, 'nama_tim' => 'Rekrutmen']);
    }
}
