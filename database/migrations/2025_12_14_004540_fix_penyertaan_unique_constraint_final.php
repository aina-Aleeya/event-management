<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            // Try to drop old unique constraint (might not exist)
            DB::statement('ALTER TABLE penyertaan DROP INDEX penyertaan_event_id_unique_id_unique');
        } catch (\Exception $e) {
            // Index doesn't exist, that's fine
        }
        
        try {
            // Add new unique constraint
            DB::statement('
                ALTER TABLE penyertaan 
                ADD UNIQUE INDEX penyertaan_event_category_unique_id_unique 
                (event_id, categorizable_type, categorizable_id, unique_id)
            ');
        } catch (\Exception $e) {
            // Might already exist
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Recreate foreign keys
        try {
            Schema::table('ranking_reports', function ($table) {
                $table->foreign('event_id')
                    ->references('id')
                    ->on('events')
                    ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist
        }
        
        try {
            Schema::table('ranking_reports', function ($table) {
                $table->foreign('penyertaan_id')
                    ->references('id')
                    ->on('penyertaan')
                    ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist
        }
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            DB::statement('ALTER TABLE penyertaan DROP INDEX penyertaan_event_category_unique_id_unique');
        } catch (\Exception $e) {
            //
        }
        
        try {
            DB::statement('
                ALTER TABLE penyertaan 
                ADD UNIQUE INDEX penyertaan_event_id_unique_id_unique 
                (event_id, unique_id)
            ');
        } catch (\Exception $e) {
            //
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};