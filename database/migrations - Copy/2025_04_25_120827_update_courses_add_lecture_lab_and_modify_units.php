<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCoursesAddLectureLabAndModifyUnits extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Add lecture and lab columns
            $table->decimal('lecture_hours', 3, 1)->nullable()->after('units');
            $table->decimal('lab_hours', 3, 1)->nullable()->after('lecture_hours');

            // Modify units column
            $table->decimal('units', 3, 1)->change();
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['lecture_hours', 'lab_hours']);

            // Revert units to original
            $table->decimal('units', 5, 2)->change();
        });
    }
}
