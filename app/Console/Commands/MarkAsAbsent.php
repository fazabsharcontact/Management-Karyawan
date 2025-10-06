<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pegawai;
use App\Models\Kehadiran;
use Carbon\Carbon;

// --- PERBAIKAN: Nama class disamakan dengan nama file ---
class MarkAsAbsent extends Command
{
    protected $signature = 'kehadiran:mark-absent';
    protected $description = 'Tandai pegawai yang belum presensi sebagai Absen setelah jam 13:00 WIB.';

    public function handle()
    {
        $today = Carbon::today('Asia/Jakarta');

        // Jangan jalankan perintah ini di akhir pekan
        if ($today->isWeekend()) {
            $this->info('Hari ini akhir pekan, tidak ada pengecekan absen.');
            return 0;
        }

        // 1. Ambil semua ID pegawai yang seharusnya masuk kerja
        $pegawaiIds = Pegawai::pluck('id');

        // 2. Ambil semua ID pegawai yang SUDAH memiliki record kehadiran hari ini
        $pegawaiSudahPresensiIds = Kehadiran::where('tanggal', $today->toDateString())
                                     ->pluck('pegawai_id');

        // 3. Cari ID pegawai yang BELUM presensi
        $pegawaiBelumPresensiIds = $pegawaiIds->diff($pegawaiSudahPresensiIds);
        
        if ($pegawaiBelumPresensiIds->isEmpty()) {
            $this->info('Semua pegawai sudah melakukan presensi hari ini.');
            return 0;
        }

        $this->info("Menemukan {$pegawaiBelumPresensiIds->count()} pegawai yang belum presensi. Menandai sebagai Absen...");
        
        // 4. Buat record 'Absen' untuk setiap pegawai yang belum presensi
        foreach ($pegawaiBelumPresensiIds as $pegawaiId) {
            Kehadiran::create([
                'pegawai_id' => $pegawaiId,
                'tanggal'    => $today->toDateString(),
                'status'     => 'Absen',
            ]);
        }

        $this->info('Selesai! Semua pegawai yang tidak hadir telah ditandai.');
        return 0;
    }
}
