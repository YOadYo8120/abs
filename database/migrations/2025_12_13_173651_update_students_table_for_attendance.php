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
        // First, populate empty code and email fields
        DB::statement('UPDATE students SET code = CONCAT("STU", LPAD(id, 6, "0")) WHERE code = "" OR code IS NULL');
        DB::statement('UPDATE students s JOIN users u ON s.user_id = u.id SET s.email = u.email WHERE s.email = "" OR s.email IS NULL');

        // Now add unique constraints
        Schema::table('students', function (Blueprint $table) {
            $table->unique('code');
            $table->unique('email');
        });

        // Update specialization enum to match schedule specializations
        DB::statement("ALTER TABLE students MODIFY COLUMN specialization ENUM('GI', 'GC', 'GE', 'GM') NULL");
    }    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_code_unique');
            $table->dropUnique('students_email_unique');
        });

        DB::statement("ALTER TABLE students MODIFY COLUMN specialization ENUM('GI','GRT','GInd','GM','GA','GPM') NULL");
    }
};
