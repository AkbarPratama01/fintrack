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
            $table->enum('transaction_type', ['pulsa', 'dana', 'gopay', 'token_listrik'])->default('pulsa')->after('user_id');
            $table->string('product_code', 50)->nullable()->after('transaction_type'); // Kode produk seperti nominal pulsa, token listrik
            $table->string('customer_id', 100)->nullable()->after('phone_number'); // ID customer untuk token listrik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_kios_transactions', function (Blueprint $table) {
            $table->dropColumn(['transaction_type', 'product_code', 'customer_id']);
        });
    }
};
