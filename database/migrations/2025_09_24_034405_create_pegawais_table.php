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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('jabatans')->onDelete('restrict');
            $table->foreignId('tim_id')->nullable()->constrained('tims')->onDelete('set null');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('no_hp', 30)->nullable();
            $table->string('nama_bank', 50)->nullable();
            $table->string('nomor_rekening', 50)->nullable(); 
            $table->text('alamat')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->tinyInteger('sisa_cuti_tahunan')->default(12);
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
