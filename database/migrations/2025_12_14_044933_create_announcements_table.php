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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Creator (admin or teacher)
            $table->string('title');
            $table->text('content');
            $table->enum('scope', ['global', 'module', 'class', 'session']); // Scope of announcement

            // For module-specific announcements
            $table->foreignId('module_id')->nullable()->constrained()->onDelete('cascade');

            // For class-specific announcements (year + specialization + track)
            $table->integer('year')->nullable();
            $table->string('specialization', 10)->nullable();
            $table->string('track', 10)->nullable();
            $table->integer('semester')->nullable();

            // For session-specific announcements
            $table->foreignId('schedule_id')->nullable()->constrained()->onDelete('cascade');

            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['scope', 'published_at']);
            $table->index(['year', 'specialization', 'track', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
