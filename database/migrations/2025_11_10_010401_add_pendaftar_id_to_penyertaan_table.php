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
        Schema::table('penyertaan', function (Blueprint $table) {
            //
            $table->foreignId('pendaftar_id')->nullable()->constrained('users')->onDelete('cascade')->after('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyertaan', function (Blueprint $table) {
            //
        });
    }
};
