<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('token', 64)->nullable()->unique()->after('capacity');
        });

        // Generate tokens for existing groups
        DB::table('groups')->whereNull('token')->get()->each(function ($group) {
            DB::table('groups')
                ->where('id', $group->id)
                ->update(['token' => Str::random(32)]);
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};