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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method'); // Misal: Manual Transfer, Midtrans, QRIS
            $table->decimal('amount', 12, 2);
            $table->string('payment_status')->default('pending'); // pending, success, failed
            $table->string('transaction_id')->nullable(); // Untuk ID dari payment gateway
            $table->string('proof_of_payment')->nullable(); // Untuk upload struk jika transfer manual
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
