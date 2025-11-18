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
            $table->string('status_bayaran', 20)->default('pending')->after('kategori');
            //
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
