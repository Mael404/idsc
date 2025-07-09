<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabHoursToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add lab_hours column to courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->decimal('lab_hours', 5, 2)->nullable()->after('lecture_hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the lab_hours column if rolling back the migration
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('lab_hours');
        });
    }
}
