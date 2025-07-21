<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();  // Primary key (auto-incrementing)
            $table->string('name');  // The name of the school year (e.g., "2025-2026")
            $table->decimal('default_unit_price', 8, 2);  // Default unit price (max 8 digits, 2 decimal places)
            $table->string('semester');  // The semester for the school year (e.g., "First Semester")
            $table->boolean('is_active')->default(true);  // Indicates if the school year is active (default is true)
            $table->softDeletes();  // Adds a deleted_at column for soft deletes
            $table->timestamps();  // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_years');
    }
}
