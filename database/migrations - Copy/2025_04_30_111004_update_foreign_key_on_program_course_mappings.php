<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['course_id']);
            
            // Recreate it with ON DELETE CASCADE
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            // Revert back to ON DELETE RESTRICT (or default)
            $table->dropForeign(['course_id']);
            
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('restrict');
        });
    }
};
