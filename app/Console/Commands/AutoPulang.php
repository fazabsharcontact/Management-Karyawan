<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kehadiran;
use Carbon\Carbon;

class AutoPulang extends Command
{
    protected $signature = 'kehadiran:auto-pulang';
    protected $description = 'Set jam pulang otomatis jam 19:00 untuk yang belum absen pulang.';

    public function handle()
    {
        $tanggal = Carbon::today('Asia/Jakarta')->toDateString();
        $waktuAutoPulang = '19:00:00';

        // Cari record yang statusnya Hadir/Terlambat, sudah punya jam_masuk, tapi jam_pulang masih NULL
        $kehadiranBelumPulang = Kehadiran::whereDate('tanggal', $tanggal)
            ->whereIn('status', ['Hadir', 'Terlambat'])
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->get();

        $count = 0;
        foreach ($kehadiranBelumPulang as $kehadiran) {
            $kehadiran->update([
                'jam_pulang' => $waktuAutoPulang,
            ]);
            $count++;
        }

        $this->info("Berhasil mencatat pulang otomatis jam 19:00 untuk {$count} pegawai.");
        return 0;
    }
}
