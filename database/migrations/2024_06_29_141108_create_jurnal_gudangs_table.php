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
        Schema::create('jurnal_gudangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('jenis_jurnal_gudang_id')->constrained();
            $table->foreignId('gudang_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_gudangs');
    }
};
