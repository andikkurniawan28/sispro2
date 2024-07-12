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
        Schema::create('mutasi_antar_gudangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('gudang_asal_id')->constrained('gudangs');
            $table->foreignId('gudang_tujuan_id')->constrained('gudangs');
            $table->foreignId('user_id')->constrained();
            $table->double('grand_total');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_antar_gudangs');
    }
};
