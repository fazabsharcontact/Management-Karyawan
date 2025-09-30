<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sisa_cutis', function (Blueprint $table) {
            $table->id();
            // Relasi one-to-one ke tabel pegawais. Unik agar satu pegawai hanya punya satu data sisa cuti.
            $table->foreignId('pegawai_id')->unique()->constrained('pegawais')->onDelete('cascade');
            // Kolom untuk menyimpan sisa cuti tahunan
            $table->tinyInteger('sisa_cuti')->default(12);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sisa_cutis');
    }
};