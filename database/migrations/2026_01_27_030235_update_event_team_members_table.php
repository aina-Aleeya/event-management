<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_team_members', function (Blueprint $table) {
            // Add role_id
            $table->foreignId('role_id')->after('email')->constrained('roles')->onDelete('cascade');
            
            // Drop old columns if they exist
            if (Schema::hasColumn('event_team_members', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('event_team_members', 'permissions')) {
                $table->dropColumn('permissions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_team_members', function (Blueprint $table) {
            $table->string('role')->after('email')->nullable();
            $table->json('permissions')->nullable();
            
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};