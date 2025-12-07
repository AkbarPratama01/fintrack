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
        Schema::create('prayers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('prayer_name', ['fajr', 'dhuhr', 'asr', 'maghrib', 'isha']);
            $table->date('prayer_date');
            $table->enum('status', ['on_time', 'qadha', 'missed'])->default('on_time');
            $table->time('prayed_at')->nullable();
            $table->boolean('is_jamaah')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index untuk query yang sering digunakan
            $table->index(['user_id', 'prayer_date']);
            $table->unique(['user_id', 'prayer_name', 'prayer_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayers');
    }
};
