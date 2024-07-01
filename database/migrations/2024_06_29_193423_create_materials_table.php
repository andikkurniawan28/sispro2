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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->foreignId('fungsi_material_id')->constrained();
            $table->foreignId('jenis_material_id')->constrained();
            $table->foreignId('satuan_besar_id')->constrained('satuans');
            $table->foreignId('satuan_kecil_id')->constrained('satuans');
            $table->float('sejumlah');
            $table->float('hasil_per_batch_dalam_satuan_besar')->nullable();
            $table->float('hasil_per_batch_dalam_satuan_kecil')->nullable();
            $table->float('harga_beli')->nullable();
            $table->float('harga_jual')->nullable();
            $table->float('harga_pokok')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
