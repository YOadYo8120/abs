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
        // First, add the code and email columns if they don't exist
        Schema::table('students', function (Blueprint $table) {
            $table->string('code')->nullable();
            $table->string('email')->nullable();
        });

        // Populate empty code and email fields (PostgreSQL syntax)
        DB::statement("UPDATE students SET code = 'STU' || LPAD(id::text, 6, '0') WHERE code = '' OR code IS NULL");
        DB::statement("UPDATE students s SET email = u.email FROM users u WHERE s.user_id = u.id AND (s.email = '' OR s.email IS NULL)");

        // Now add unique constraints
        Schema::table('students', function (Blueprint $table) {
            $table->unique('code');
            $table->unique('email');
        });

        // Update specialization check constraint for PostgreSQL
        DB::statement("ALTER TABLE students DROP CONSTRAINT IF EXISTS students_specialization_check");
        DB::statement("ALTER TABLE students ADD CONSTRAINT students_specialization_check CHECK (specialization::text = ANY (ARRAY['GI'::character varying, 'GC'::character varying, 'GE'::character varying, 'GM'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_code_unique');
            $table->dropUnique('students_email_unique');
        });

        DB::statement("ALTER TABLE students DROP CONSTRAINT IF EXISTS students_specialization_check");
        DB::statement("ALTER TABLE students ADD CONSTRAINT students_specialization_check CHECK (specialization::text = ANY (ARRAY['GI'::character varying,'GRT'::character varying,'GInd'::character varying,'GM'::character varying,'GA'::character varying,'GPM'::character varying]::text[]))");
    }
};
