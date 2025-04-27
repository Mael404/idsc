<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricePerUnitToProgramCourseMappings extends Migration
{
    public function up()
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            // Modify the price_per_unit column to set a default value of 0
            $table->decimal('price_per_unit', 8, 2)->default(0)->after('semester_id'); // Adjust 'after' column position if needed
        });
    }

    public function down()
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            // Rollback by removing the column
            $table->dropColumn('price_per_unit');
        });
    }
}
