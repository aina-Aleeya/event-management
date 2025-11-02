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
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penuh');
            $table->string('nama_panggilan')->nullable();
            $table->string('kelas')->nullable();
            $table->string('gambar')->nullable();
            $table->string('email')->nullable();
            $table->enum('jantina', ['Lelaki', 'Perempuan'])->nullable();
            $table->string('ic')->nullable();
            $table->date('tarikh_lahir')->nullable();
            $table->string('ip_address')->nullable(); // untuk track user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};