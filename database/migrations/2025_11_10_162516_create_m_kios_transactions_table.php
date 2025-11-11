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
        Schema::create('m_kios_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone_number', 20); // Nomor telepon pembeli
            $table->decimal('balance_deducted', 15, 2); // Nominal saldo terpotong dari wallet
            $table->decimal('cash_received', 15, 2); // Nominal uang dari pembeli
            $table->decimal('profit', 15, 2)->default(0); // Keuntungan (cash_received - balance_deducted)
            $table->string('provider', 50)->nullable(); // Provider pulsa (Telkomsel, Indosat, XL, dll)
            $table->foreignId('wallet_id')->nullable()->constrained()->onDelete('set null'); // Wallet yang digunakan
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->enum('status', ['completed', 'pending', 'failed'])->default('completed');
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kios_transactions');
    }
};
