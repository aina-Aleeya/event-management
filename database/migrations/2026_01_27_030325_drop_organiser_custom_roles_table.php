<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('organiser_custom_roles');
    }

    public function down(): void
    {
        Schema::create('organiser_custom_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role_name');
            $table->timestamps();
            $table->unique(['user_id', 'role_name']);
        });
    }
};