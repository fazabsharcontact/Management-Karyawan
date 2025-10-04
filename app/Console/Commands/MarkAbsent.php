<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pegawai;
use App\Models\Kehadiran;
use Carbon\Carbon;

class MarkAbsent extends Command
{
    // Ubah deskripsi
    protected $signature = 'kehadiran:mark-absent';
    protected $description = 'Tandai pegawai yang belum absen sebagai Absen setelah jam 13:00 WIB.';

    public function handle()
    {
        $tanggal = Carbon::today('Asia/Jakarta')->toDateString();
        $now = Carbon::now('Asia/Jakarta');

        // Batas akhir Izin/Sakit/Absen = 13:00
        $batasWaktu = Carbon::createFromTime(13, 0, 0, 'Asia/Jakarta');

        if ($now->lessThanOrEqualTo($batasWaktu)) {
            $this->info('Belum waktunya menjalankan MarkAbsent (batas waktu 13:00).');
            return 0;
        }

        // Ambil semua pegawai
        $pegawaiList = Pegawai::all();

        foreach ($pegawaiList as $pegawai) {
            $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id)
                ->whereDate('tanggal', $tanggal)
                ->first();

            // Cek jika belum ada record sama sekali
            if (!$kehadiran) {
                Kehadiran::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal'  => $tanggal,
                    'status'   => 'Absen',
                ]);

                $this->info("Pegawai {$pegawai->nama} ditandai Absen otomatis.");
            }
        }

        return 0;
    }
}
