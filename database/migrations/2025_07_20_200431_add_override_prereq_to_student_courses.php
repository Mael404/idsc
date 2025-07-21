<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->boolean('override_prereq')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->dropColumn('override_prereq');
        });
    }
};