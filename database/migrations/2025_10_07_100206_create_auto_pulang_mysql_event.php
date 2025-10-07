<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // --- PERBAIKAN: Hapus event lama terlebih dahulu ---
        DB::unprepared('DROP EVENT IF EXISTS `auto_pulang_trigger`');

        $sql = "
            CREATE EVENT `auto_pulang_trigger`
            ON SCHEDULE EVERY 1 DAY
            STARTS TIMESTAMP(CURRENT_DATE, '19:00:00')
            DO
            BEGIN
                IF DAYOFWEEK(CURRENT_DATE) NOT IN (1, 7) THEN
                    UPDATE kehadirans
                    SET jam_pulang = '19:00:00', updated_at = NOW()
                    WHERE 
                        tanggal = CURDATE()
                        AND status IN ('Hadir', 'Terlambat')
                        AND jam_pulang IS NULL;
                END IF;
            END
        ";
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP EVENT IF EXISTS `auto_pulang_trigger`');
    }
};