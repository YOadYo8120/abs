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
        Schema::table('schedules', function (Blueprint $table) {
            // Drop the old unique constraint
            $table->dropUnique('schedule_unique');

            // Add new unique constraint that includes specialization and track
            // This allows different specializations and tracks to have schedules at the same time
            $table->unique(
                ['year', 'semester', 'specialization', 'track', 'week_number', 'day', 'start_time', 'end_time'],
                'schedule_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Drop the new constraint
            $table->dropUnique('schedule_unique');

            // Restore the old constraint
            $table->unique(
                ['year', 'semester', 'week_number', 'day', 'start_time', 'end_time'],
                'schedule_unique'
            );
        });
    }
};
