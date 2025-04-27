<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPricePerUnitDefaultValueInProgramCourseMappings extends Migration
{
    public function up()
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            // Modify the existing 'price_per_unit' column to set the default value to 0
            $table->decimal('price_per_unit', 8, 2)->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            // Optionally, rollback by removing the default value
            $table->decimal('price_per_unit', 8, 2)->change();
        });
    }
}
