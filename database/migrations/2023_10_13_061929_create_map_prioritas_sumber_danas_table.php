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
        Schema::create('map_prioritas_sumber_danas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('prioritas_sumber_dana_id');
            $table->foreign('prioritas_sumber_dana_id')->references('id')->on('prioritas_sumber_danas');
            $table->string('kode_skpd');
            $table->foreign('kode_skpd')->references('kode')->on('skpds');
            $table->string('kode_sub_kegiatan');
            $table->string('kode_rekening');
            $table->double('nilai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_prioritas_sumber_danas');
    }
};
