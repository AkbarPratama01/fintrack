<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->enum('frequency', ['daily','weekly','monthly'])
                  ->default('daily')
                  ->after('category');

            $table->json('days')
                  ->nullable()
                  ->after('frequency');
        });
    }

    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn(['frequency', 'days']);
        });
    }
};