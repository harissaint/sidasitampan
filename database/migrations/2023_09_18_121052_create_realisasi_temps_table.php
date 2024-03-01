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
        Schema::create('realisasi_temps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_urusan');
            $table->string('nama_urusan');
            $table->string('kode_bidang_urusan');
            $table->string('nama_bidang_urusan');
            $table->string('kode_skpd');
            // $table->foreign('kode_skpd')->references('kode')->on('skpds');
            $table->string('nama_skpd');
            $table->string('kode_sub_skpd');
            $table->string('nama_sub_skpd');
            $table->string('kode_program');
            $table->text('nama_program');
            $table->string('kode_kegiatan');
            $table->text('nama_kegiatan');
            $table->string('kode_sub_kegiatan');
            $table->text('nama_sub_kegiatan');
            $table->string('kode_akun');
            $table->string('nama_akun');
            $table->bigInteger('nilai_rincian');
            $table->year('tahun');
            // $table->string('nomor_spd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisasi_temps');
    }
};
