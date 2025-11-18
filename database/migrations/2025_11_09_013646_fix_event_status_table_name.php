<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('event_status')) {
            Schema::rename('event_status', 'event_statuses');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('event_statuses')) {
            Schema::rename('event_statuses', 'event_status');
        }
    }
};
