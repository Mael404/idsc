<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            // Safely drop foreign keys if they exist
            DB::statement('ALTER TABLE program_course_mappings DROP FOREIGN KEY IF EXISTS program_course_mappings_program_id_foreign');
            DB::statement('ALTER TABLE program_course_mappings DROP FOREIGN KEY IF EXISTS program_course_mappings_year_level_id_foreign');
            DB::statement('ALTER TABLE program_course_mappings DROP FOREIGN KEY IF EXISTS program_course_mappings_semester_id_foreign');
        });

        // Now you can safely drop the columns
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->dropColumn(['program_id', 'year_level_id', 'semester_id', 'effective_sy']);

            $table->foreignId('group_id')
                ->after('id')
                ->constrained('course_mapping_groups')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');

            $table->foreignId('program_id')->constrained();
            $table->foreignId('year_level_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->string('effective_sy');
        });
    }
};
