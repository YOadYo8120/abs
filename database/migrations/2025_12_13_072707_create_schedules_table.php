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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('year'); // 1-5
            $table->enum('semester', ['1', '2']); // Semester 1 or 2
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
            $table->time('start_time'); // e.g., 08:30
            $table->time('end_time'); // e.g., 09:30
            $table->foreignId('module_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            // Ensure no overlapping schedules for the same year, semester, day, and time
            $table->unique(['year', 'semester', 'day', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
