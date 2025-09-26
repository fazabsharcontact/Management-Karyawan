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
        Schema::create('pengumuman_penerimas', function (Blueprint $table) {
            $table->id();
            
            // Kolom untuk menghubungkan ke tabel 'pengumuman'
            $table->foreignId('pengumuman_id')->constrained('pengumuman')->onDelete('cascade');
            
            // KOLOM YANG HILANG: untuk menentukan tipe target
            $table->enum('target_type', ['semua', 'divisi', 'tim', 'jabatan', 'pegawai']);
            
            // Kolom untuk menyimpan ID dari target
            $table->unsignedBigInteger('target_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman_penerimas');
    }
};