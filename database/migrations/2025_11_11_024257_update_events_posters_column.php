<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Rename column first
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('poster', 'posters');
        });

        // Convert existing string values to JSON array
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            $poster = $event->posters;
            $json = $poster ? json_encode([$poster]) : null; // wrap existing value in array
            DB::table('events')->where('id', $event->id)->update(['posters' => $json]);
        }

        // Change column type to JSON
        Schema::table('events', function (Blueprint $table) {
            $table->json('posters')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Convert JSON back to string (take first image)
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            $posters = json_decode($event->posters, true);
            $first = !empty($posters) ? $posters[0] : null;
            DB::table('events')->where('id', $event->id)->update(['posters' => $first]);
        }

        // Change column back to varchar
        Schema::table('events', function (Blueprint $table) {
            $table->string('posters', 255)->nullable()->change();
        });

        // Rename column back to poster
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('posters', 'poster');
        });
    }
};
