<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLectureHoursToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add lecture_hours column to courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->decimal('lecture_hours', 5, 2)->nullable()->after('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the lecture_hours column
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('lecture_hours');
        });
    }
}
