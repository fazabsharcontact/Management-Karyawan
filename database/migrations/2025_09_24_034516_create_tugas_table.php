<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_tugas');
            $table->text('deskripsi')->nullable();
            $table->foreignId('pemberi_id')->constrained('pegawais')->onDelete('cascade');
            $table->foreignId('penerima_id')->constrained('pegawais')->onDelete('cascade');
            $table->dateTime('tenggat_waktu')->nullable();
            $table->enum('status', ['Baru', 'Dikerjakan', 'Selesai', 'Ditinjau'])->default('Baru');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
