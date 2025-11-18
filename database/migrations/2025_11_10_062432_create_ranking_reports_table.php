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
        Schema::create('ranking_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyertaan_id');
            $table->tinyInteger('ranking'); // 1, 2, 3
            $table->timestamps();

            $table->foreign('penyertaan_id')
                ->references('id')
                ->on('penyertaan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_reports');
    }
};
