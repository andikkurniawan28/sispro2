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
        Schema::create('permintaan_produk_akhir_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained('permintaan_produk_akhirs')->onDelete('cascade');
            $table->foreignId('produk_akhir_id')->constrained();
            $table->float('jumlah');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_produk_akhir_details');
    }
};
