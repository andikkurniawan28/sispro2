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
        Schema::create('mutasi_antar_gudang_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mutasi_antar_gudang_id')->constrained();
            $table->foreignId('material_id')->constrained();
            $table->float('jumlah_dalam_satuan_kecil');
            $table->float('jumlah_dalam_satuan_besar');
            $table->double('harga');
            $table->double('total');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_antar_gudang_items');
    }
};
