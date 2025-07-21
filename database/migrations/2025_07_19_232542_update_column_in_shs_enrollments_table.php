<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shs_enrollments', function (Blueprint $table) {
            $table->string('semester')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shs_enrollments', function (Blueprint $table) {
            DB::statement("ALTER TABLE your_table MODIFY COLUMN semester ENUM('1st', '2nd', 'Summer') NOT NULL");
        });
    }
};
