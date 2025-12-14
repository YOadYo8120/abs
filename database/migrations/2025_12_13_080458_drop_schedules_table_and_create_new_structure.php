<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        // Drop the old schedules table
        Schema::dropIfExists('schedules');

        // Create new schedules table with week number
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('year'); // 1-5
            $table->integer('semester'); // 1-2
            $table->integer('week_number'); // 1-16 (typical semester length)
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint: one module per time slot per week
            $table->unique(['year', 'semester', 'week_number', 'day', 'start_time', 'end_time'], 'schedule_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');

        // Recreate old structure if needed
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('semester');
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['year', 'semester', 'day', 'start_time', 'end_time']);
        });
    }
};
