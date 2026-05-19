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
    Schema::table('products', function (Blueprint $table) {
        // Kita tambahkan kolom collection_name setelah kolom 'name' atau sesuai keinginan
        $table->string('collection_name')->nullable()->after('name');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('collection_name');
    });
    }
};
