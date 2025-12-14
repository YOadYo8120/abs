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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->default('pdf');
            $table->integer('file_size'); // in bytes
            $table->enum('scope', ['module', 'class'])->default('module');

            // For module scope
            $table->foreignId('module_id')->nullable()->constrained()->onDelete('cascade');

            // For class scope
            $table->integer('year')->nullable();
            $table->string('specialization')->nullable();
            $table->string('track')->nullable();
            $table->integer('semester')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('scope');
            $table->index(['year', 'specialization', 'track', 'semester']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
