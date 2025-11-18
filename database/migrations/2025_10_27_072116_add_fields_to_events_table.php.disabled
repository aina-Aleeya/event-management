<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('events', function (Blueprint $table) {
        $table->string('event_type')->nullable();
        $table->string('venue')->nullable();
        $table->string('address')->nullable();
        $table->string('city')->nullable();
        $table->string('contact_email')->nullable();
        $table->string('contact_phone')->nullable();
        $table->time('start_time')->nullable();
        $table->time('end_time')->nullable();
        $table->date('registration_deadline')->nullable();
        $table->decimal('entry_fee', 10, 2)->nullable();
        $table->integer(column: 'max_participants')->nullable();
    });
}

public function down()
{
    Schema::table('events', function (Blueprint $table) {
        $table->dropColumn([
            'event_type', 'venue','address', 'city', 'contact_email', 'contact_phone',
            'start_time', 'end_time', 'registration_deadline', 'entry_fee', 'max_participants'
        ]);
    });
}

};
