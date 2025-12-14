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
        Schema::create('academic_calendar', function (Blueprint $table) {
            $table->id();
            $table->integer('year'); // Academic year (e.g., 2025)
            $table->integer('semester'); // 1 or 2
            $table->integer('week_number'); // 1-13 for S1, 1-14 for S2
            $table->date('start_date'); // Monday of that week
            $table->date('end_date'); // Sunday of that week
            $table->string('description')->nullable(); // e.g., "DS1", "FORUM", etc.
            $table->timestamps();

            $table->unique(['year', 'semester', 'week_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendar');
    }
};
