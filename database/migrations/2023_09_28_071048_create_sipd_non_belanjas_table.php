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
        Schema::create('sipd_non_belanjas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_skpd');
            $table->foreign('kode_skpd')->references('kode')->on('skpds');
            $table->string('nama_skpd');
            $table->string('kode_akun');
            $table->string('nama_akun');
            $table->bigInteger('nilai_rincian');
            $table->uuid('tahapan_id');
            $table->foreign('tahapan_id')->references('id')->on('tahapans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sipd_non_belanjas');
    }
};
