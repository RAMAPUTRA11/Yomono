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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('order_number')->unique();
        $table->decimal('total_amount', 12, 2);
        
        // Status Order: Pengemasan/Pengiriman
        $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
        
        // Status Pembayaran: Sudah bayar atau belum
        $table->enum('payment_status', ['unpaid', 'paid', 'expired'])->default('unpaid');
        
        $table->text('shipping_address');
        $table->string('tracking_number')->nullable(); // Nomor Resi
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
