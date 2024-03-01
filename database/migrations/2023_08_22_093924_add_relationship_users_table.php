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
        Schema::table('users', function ($table) {
            // foreign to uuid groups
            $table->uuid('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups');
            $table->uuid('skpd_id')->nullable();
            $table->foreign('skpd_id')->references('id')->on('skpds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('users', function ($table) {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['skpd_id']);
        });
    }
};
