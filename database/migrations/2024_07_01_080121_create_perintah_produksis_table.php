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
        Schema::create('perintah_produksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->integer('batch');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('material_id')->constrained();
            $table->float('jumlah_dalam_satuan_besar');
            $table->float('jumlah_dalam_satuan_kecil');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perintah_produksis');
    }
};
