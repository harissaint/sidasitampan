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
        Schema::create('map_sk_prioritas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('prioritas_id');
            $table->foreign('prioritas_id')->references('id')->on('prioritas');
            $table->string('kode_skpd');
            $table->foreign('kode_skpd')->references('kode')->on('skpds');
            $table->string('kode_sub_kegiatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_sk_prioritas');
    }
};
