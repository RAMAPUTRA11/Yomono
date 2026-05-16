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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // Opsional: berelasi ke order_item jika ingin ulasan per transaksi spesifik
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); 
            
            $table->integer('rating')->default(5); // Skala 1-5
            $table->text('comment')->nullable();
            $table->string('image')->nullable(); // User bisa upload foto produk yang datang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
