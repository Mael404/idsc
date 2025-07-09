<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEffectiveSyToProgramCourseMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add effective_sy column to program_course_mappings table
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->string('effective_sy', 9)->nullable()->after('semester_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the effective_sy column if rolling back the migration
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->dropColumn('effective_sy');
        });
    }
}
