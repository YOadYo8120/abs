<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change the enum to include 'exam'
        DB::statement("ALTER TABLE schedules MODIFY COLUMN schedule_type ENUM('course', 'TD', 'TP', 'exam') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE schedules MODIFY COLUMN schedule_type ENUM('course', 'TD', 'TP') NULL");
    }
};
