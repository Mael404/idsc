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
        // Drop the old foreign key constraint
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });
    
        // Add the new foreign key constraint with cascade delete
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        // Drop the new foreign key constraint
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });
    
        // Revert to the original constraint (if needed)
        Schema::table('program_course_mappings', function (Blueprint $table) {
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('restrict');
        });
    }
    
};
