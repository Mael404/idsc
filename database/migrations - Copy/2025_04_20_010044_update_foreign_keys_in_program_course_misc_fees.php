<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_course_misc_fees', function (Blueprint $table) {
            // Drop existing foreign key constraints
            $table->dropForeign(['program_course_mapping_id']);
            $table->dropForeign(['misc_fee_id']);

            // Recreate without cascade
            $table->foreign('program_course_mapping_id')->references('id')->on('program_course_mappings');
            $table->foreign('misc_fee_id')->references('id')->on('misc_fees');
        });
    }

    public function down(): void
    {
        Schema::table('program_course_misc_fees', function (Blueprint $table) {
            // Drop modified foreign keys
            $table->dropForeign(['program_course_mapping_id']);
            $table->dropForeign(['misc_fee_id']);

            // Restore the original cascading behavior
            $table->foreign('program_course_mapping_id')
                ->references('id')
                ->on('program_course_mappings')
                ->onDelete('cascade');

            $table->foreign('misc_fee_id')
                ->references('id')
                ->on('misc_fees')
                ->onDelete('cascade');
        });
    }
};
