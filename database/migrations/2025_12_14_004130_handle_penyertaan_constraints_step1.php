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
        // Drop foreign keys from ranking_reports
        Schema::table('ranking_reports', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropForeign(['penyertaan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        // Recreate foreign keys
        Schema::table('ranking_reports', function (Blueprint $table) {
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
                
            $table->foreign('penyertaan_id')
                ->references('id')
                ->on('penyertaan')
                ->onDelete('cascade');
        });
    }
};
