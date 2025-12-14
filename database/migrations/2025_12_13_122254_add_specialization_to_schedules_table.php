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
            // Specialization for years 3-5 (nullable for years 1-2)
            $table->enum('specialization', [
                'GI',    // Génie Informatique
                'GRT',   // Génie Réseaux & Télécommunications
                'GInd',  // Génie Industriel
                'GM',    // Génie Mécatronique
                'GA',    // Génie Aéronautique
                'GPM'    // Génie Procédés & Matériaux
            ])->nullable()->after('semester')->comment('Required for years 3-5, null for years 1-2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('specialization');
        });
    }
};
