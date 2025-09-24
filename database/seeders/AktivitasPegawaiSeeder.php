<?php

namespace Database\Seeders;

use App\Models\Cuti;
use App\Models\Gaji;
use App\Models\GajiPotonganDetail;
use App\Models\GajiTunjanganDetail;
use App\Models\Kehadiran;
use App\Models\MasterPotongan;
use App\Models\MasterTunjangan;
use App\Models\Pegawai;
use App\Models\Tugas;
use Illuminate\Database\Seeder;

class AktivitasPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawais = Pegawai::all();
        $manager = Pegawai::whereHas('jabatan', function ($query) {
            $query->where('nama_jabatan', 'Manager');
        })->first();

        foreach ($pegawais as $pegawai) {
            // 1. Buat data kehadiran 30 hari ke belakang
            for ($i = 0; $i < 30; $i++) {
                Kehadiran::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => now()->subDays($i),
                    'jam_masuk' => '08:'. fake()->numberBetween(0, 59) .':00',
                    'jam_pulang' => '17:'. fake()->numberBetween(0, 59) .':00',
                    'status' => 'Hadir',
                ]);
            }

            // 2. Buat data Gaji bulan lalu
            $gajiPokok = $pegawai->gaji_pokok;
            $tunjanganMakan = MasterTunjangan::where('nama_tunjangan', 'Tunjangan Makan')->first();
            $potonganBpjs = MasterPotongan::where('nama_potongan', 'Potongan BPJS Kesehatan')->first();

            $gaji = Gaji::create([
                'pegawai_id' => $pegawai->id,
                'bulan' => now()->subMonth()->month,
                'tahun' => now()->subMonth()->year,
                'gaji_pokok' => $gajiPokok,
                'total_tunjangan' => 500000,
                'total_potongan' => 150000,
                'gaji_bersih' => $gajiPokok + 500000 - 150000,
            ]);

            GajiTunjanganDetail::create([
                'gaji_id' => $gaji->id,
                'master_tunjangan_id' => $tunjanganMakan->id,
                'jumlah' => 500000
            ]);

            GajiPotonganDetail::create([
                'gaji_id' => $gaji->id,
                'master_potongan_id' => $potonganBpjs->id,
                'jumlah' => 150000
            ]);

            // 3. Buat data Cuti
            Cuti::create([
                'pegawai_id' => $pegawai->id,
                'tanggal_mulai' => now()->subMonths(2),
                'tanggal_selesai' => now()->subMonths(2)->addDays(2),
                'status' => 'Disetujui',
                'keterangan' => 'Acara keluarga',
            ]);

            // 4. Buat data Tugas (jika bukan manager)
            if ($manager && $pegawai->id !== $manager->id) {
                Tugas::create([
                    'judul_tugas' => 'Selesaikan laporan bulanan',
                    'pemberi_id' => $manager->id,
                    'penerima_id' => $pegawai->id,
                    'status' => 'Dikerjakan',
                    'tenggat_waktu' => now()->addDays(5),
                ]);
            }
        }
    }
}
