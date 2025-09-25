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
        Schema::create('master_tunjangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tunjangan', 100)->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('jumlah_default', 15, 2)->nullable()->comment('Nilai default untuk tunjangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_tunjangans');
    }
};
