<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_pengumpulan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tugas_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->string('file_pengumpulan');
            $table->text('catatan')->nullable();
            $table->enum('status', ['Baru', 'Dikerjakan', 'Ditinjau','Selesai'])->default('Baru');
            $table->timestamps();

            $table->foreign('tugas_id')->references('id')->on('tugas')->onDelete('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_pengumpulan');
    }
};
