<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyertaan', function (Blueprint $table) {
            $table->string('payment_receipt')->nullable()->after('status_bayaran');
            $table->timestamp('payment_date')->nullable()->after('payment_receipt');
        });
    }

    public function down(): void
    {
        Schema::table('penyertaan', function (Blueprint $table) {
            $table->dropColumn(['payment_receipt', 'payment_date']);
        });
    }
};