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
        Schema::table('m_kios_transactions', function (Blueprint $table) {
            $table->string('pln_customer_id', 100)->nullable()->after('phone_number')->comment('Nomor ID Pelanggan PLN / Nomor Meter Listrik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_kios_transactions', function (Blueprint $table) {
            $table->dropColumn('pln_customer_id');
        });
    }
};
