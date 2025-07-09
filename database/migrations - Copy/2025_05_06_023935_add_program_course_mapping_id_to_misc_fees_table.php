<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('misc_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('program_course_mapping_id')->nullable()->after('id');

            $table->foreign('program_course_mapping_id')
                ->references('id')
                ->on('program_course_mappings')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('misc_fees', function (Blueprint $table) {
            $table->dropForeign(['program_course_mapping_id']);
            $table->dropColumn('program_course_mapping_id');
        });
    }


    /**
     * Reverse the migrations.
     */
   
};
