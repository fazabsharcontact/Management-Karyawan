<?php

// database/migrations/xxxx_xx_xx_add_cuti_status_to_kehadiran_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- Pastikan ini di-import

return new class extends Migration
{
    public function up(): void
    {
        // Daftar ENUM lama
        $oldEnum = "'Terlambat', 'Absen', 'Hadir', 'Izin', 'Sakit'";

        // Daftar ENUM baru: ditambahkan 'Cuti'
        $newEnum = "'Terlambat', 'Absen', 'Hadir', 'Izin', 'Sakit', 'Cuti'";

        // Perintah SQL untuk mengubah definisi kolom ENUM
        DB::statement("ALTER TABLE kehadirans CHANGE COLUMN status status ENUM({$newEnum}) NOT NULL");
    }

    public function down(): void
    {
        // Mengembalikan ENUM ke kondisi awal (jika perlu)
        $oldEnum = "'Terlambat', 'Absen', 'Hadir', 'Izin', 'Sakit'";
        DB::statement("ALTER TABLE kehadirans CHANGE COLUMN status status ENUM({$oldEnum}) NOT NULL");
    }
};
