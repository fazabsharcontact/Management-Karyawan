<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kehadiran;
use Carbon\Carbon;

class AutoPulang extends Command
{
    protected $signature = 'kehadiran:auto-pulang';
    protected $description = 'Set jam pulang otomatis jam 19:00 untuk yang belum presensi pulang.';

    public function handle()
    {
        $today = Carbon::today('Asia/Jakarta');

        // Jangan jalankan di akhir pekan
        if ($today->isWeekend()) {
            $this->info('Hari ini akhir pekan, tidak ada proses auto-pulang.');
            return 0;
        }

        $waktuAutoPulang = '19:00:00';

        // Query untuk mencari record yang akan diupdate
        $query = Kehadiran::whereDate('tanggal', $today->toDateString())
            ->whereIn('status', ['Hadir', 'Terlambat'])
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang');

        // Hitung jumlah record yang akan diupdate SEBELUM melakukan update
        $count = $query->count();

        if ($count > 0) {
            // Lakukan update massal dalam satu perintah
            $query->update(['jam_pulang' => $waktuAutoPulang]);
            $this->info("Berhasil mencatat pulang otomatis jam 19:00 untuk {$count} pegawai.");
        } else {
            $this->info("Tidak ada pegawai yang perlu diupdate jam pulangnya hari ini.");
        }

        return 0;
    }
}