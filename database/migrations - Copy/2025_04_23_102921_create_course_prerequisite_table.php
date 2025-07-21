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
            $table->id();  // Auto-incrementing ID for the pivot table (optional)
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('prerequisite_id')->constrained('courses')->onDelete('cascade');
            $table->timestamps();

            // Ensure a course can't have itself as a prerequisite
            $table->unique(['course_id', 'prerequisite_id']);
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
