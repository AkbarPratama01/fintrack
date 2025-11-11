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
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'icon')) {
                $table->string('icon')->nullable()->after('type');
            }
            if (!Schema::hasColumn('categories', 'color')) {
                $table->string('color', 7)->default('#3B82F6')->after('type');
            }
            if (!Schema::hasColumn('categories', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color']);
        });
    }
};
