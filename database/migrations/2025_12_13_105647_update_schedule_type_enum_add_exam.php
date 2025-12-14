<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Indicates if the migration should run in a transaction.
     *
     * @var bool
     */
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we need to use ALTER TYPE
        DB::statement("ALTER TABLE schedules DROP CONSTRAINT IF EXISTS schedules_schedule_type_check");
        DB::statement("ALTER TABLE schedules ADD CONSTRAINT schedules_schedule_type_check CHECK (schedule_type::text = ANY (ARRAY['course'::character varying, 'TD'::character varying, 'TP'::character varying, 'exam'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE schedules DROP CONSTRAINT IF EXISTS schedules_schedule_type_check");
        DB::statement("ALTER TABLE schedules ADD CONSTRAINT schedules_schedule_type_check CHECK (schedule_type::text = ANY (ARRAY['course'::character varying, 'TD'::character varying, 'TP'::character varying]::text[]))");
    }
};
