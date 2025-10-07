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
        // --- PERBAIKAN: Hapus event lama terlebih dahulu untuk memastikan migrasi bisa berjalan berulang kali ---
        DB::unprepared('DROP EVENT IF EXISTS `mark_absent_trigger`');

        // SQL mentah untuk membuat event
        $sql = "
            CREATE EVENT `mark_absent_trigger`
            ON SCHEDULE EVERY 1 DAY
            STARTS TIMESTAMP(CURRENT_DATE, '13:01:00')
            DO
            BEGIN
                IF DAYOFWEEK(CURRENT_DATE) NOT IN (1, 7) THEN
                    INSERT INTO kehadirans (pegawai_id, tanggal, status, created_at, updated_at)
                    SELECT 
                        id, 
                        CURDATE(), 
                        'Absen',
                        NOW(),
                        NOW()
                    FROM pegawais
                    WHERE id NOT IN (
                        SELECT pegawai_id 
                        FROM kehadirans 
                        WHERE tanggal = CURDATE()
                    )
                    AND id != 1;
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
        DB::unprepared('DROP EVENT IF EXISTS `mark_absent_trigger`');
    }
};