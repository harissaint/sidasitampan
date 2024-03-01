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
        //
        Schema::table('sosmeds', function ($table) {
            // foreign to uuid groups
            $table->enum('category', ['SIDASI TAMPAN', 'SIPD PENGANGGARAN', 'SIPD-RI PENGANGGARAN', 'SIPD-RI PENATAUSAHAAN', 'Lain-lain']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
