<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePrerequisiteRelationshipTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('course_prerequisite')) {
            Schema::create('course_prerequisite', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
                $table->foreignId('prerequisite_id')->constrained('courses')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('course_prerequisite');
    }
}
