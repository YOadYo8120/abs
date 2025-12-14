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
        Schema::table('modules', function (Blueprint $table) {
            $table->enum('specialization', ['GI', 'GRT', 'GInd', 'GM', 'GA', 'GPM'])
                ->nullable()
                ->after('year')
                ->comment('Required for years 3-5 modules, null for years 1-2 common modules');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('specialization');
        });
    }
};
