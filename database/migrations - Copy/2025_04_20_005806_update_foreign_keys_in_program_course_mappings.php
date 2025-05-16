<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['year_level_id']);
            $table->dropForeign(['semester_id']);
    
            $table->foreign('program_id')->references('id')->on('programs');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('year_level_id')->references('id')->on('year_levels');
            $table->foreign('semester_id')->references('id')->on('semesters');
        });
    }
    
    public function down()
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['course_id']);
            $table->dropForeign(['year_level_id']);
            $table->dropForeign(['semester_id']);
    
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('year_level_id')->references('id')->on('year_levels')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        });
    }
    
};
