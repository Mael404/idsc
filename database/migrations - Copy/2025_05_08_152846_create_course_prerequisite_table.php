<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePrerequisiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_prerequisite', function (Blueprint $table) {
            $table->id();  // Primary key (auto-incrementing)
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');  // Foreign key to courses table (course)
            $table->foreignId('prerequisite_id')->constrained('courses')->onDelete('cascade');  // Foreign key to courses table (prerequisite)
            $table->timestamps();  // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_prerequisite');
    }
}
