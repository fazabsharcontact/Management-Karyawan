<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // file: ..._create_pengumumen_table.php

    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            
            // --- DIUBAH ---
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                
            $table->string('judul');
            $table->text('isi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumumen');
    }
};
