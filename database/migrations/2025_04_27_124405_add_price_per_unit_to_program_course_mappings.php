<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricePerUnitToProgramCourseMappings extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('program_course_mappings', 'price_per_unit')) {
            Schema::table('program_course_mappings', function (Blueprint $table) {
                $table->decimal('price_per_unit', 8, 2)->default(0)->after('semester_id');
            });
        }
    }
    

    public function down()
    {
        Schema::table('program_course_mappings', function (Blueprint $table) {
            // Rollback by removing the column
            $table->dropColumn('price_per_unit');
        });
    }
}
