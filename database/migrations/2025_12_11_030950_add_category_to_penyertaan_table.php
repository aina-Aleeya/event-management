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
            $table->string('category_id')->nullable();
            $table->string('category_type')->nullable();
            $table->unsignedBigInteger('category_reference_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyertaan', function (Blueprint $table) {
            //
            $table->dropColumn(['category_id', 'category_type', 'category_reference_id']);
        });
    }
};
