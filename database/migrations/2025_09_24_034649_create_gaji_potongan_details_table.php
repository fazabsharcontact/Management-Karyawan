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
        Schema::create('gaji_potongan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gaji_id')->constrained('gajis')->onDelete('cascade');
            $table->foreignId('master_potongan_id')->constrained('master_potongans')->onDelete('restrict');
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_potongan_details');
    }
};
