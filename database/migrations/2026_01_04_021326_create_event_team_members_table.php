<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['clerk', 'judge', 'scorekeeper', 'coordinator', 'custom'])->default('clerk');
            $table->string('custom_role_name')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });

        if (!Schema::hasColumn('events', 'user_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('event_team_members');
        
        if (Schema::hasColumn('events', 'user_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};