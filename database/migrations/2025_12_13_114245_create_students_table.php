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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('year')->comment('1=CP1, 2=CP2, 3-5=Cycle Ingénieur');

            // Specialization for years 3-5
            $table->enum('specialization', [
                'GI',    // Génie Informatique
                'GRT',   // Génie Réseaux & Télécommunications
                'GInd',  // Génie Industriel
                'GM',    // Génie Mécatronique
                'GA',    // Génie Aéronautique
                'GPM'    // Génie Procédés & Matériaux
            ])->nullable()->comment('Required for years 3-5');

            // Track for 4th & 5th year GI students only
            $table->enum('track', [
                'DEV',   // Software Development
                'AI'     // AI & Data Engineering
            ])->nullable()->comment('Only for GI students in years 4-5');

            $table->timestamps();

            // Ensure user_id is unique (one student record per user)
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
