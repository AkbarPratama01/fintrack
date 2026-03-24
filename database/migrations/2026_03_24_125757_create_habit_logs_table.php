<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('habit_id')
                  ->constrained('habits')
                  ->cascadeOnDelete();

            $table->date('date');

            $table->boolean('status')->default(false); // 0 = belum, 1 = selesai

            $table->timestamp('created_at')->useCurrent();

            // unique constraint (habit_id + date)
            $table->unique(['habit_id', 'date'], 'unique_habit_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habit_logs');
    }
};