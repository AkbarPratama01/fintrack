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
        Schema::create('quran_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('surah_number'); // 1-114
            $table->integer('from_ayah'); // Starting ayah
            $table->integer('to_ayah'); // Ending ayah
            $table->integer('total_ayahs_read'); // Total ayahs read in this session
            $table->date('reading_date');
            $table->time('duration')->nullable(); // Duration of reading
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'reading_date']);
            $table->index('surah_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_readings');
    }
};
