<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToSchoolYearsTable extends Migration
{
    public function up()
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->softDeletes(); // Adds the `deleted_at` column
        });
    }

    public function down()
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->dropColumn('deleted_at'); // Drops the `deleted_at` column if rolling back
        });
    }
}
