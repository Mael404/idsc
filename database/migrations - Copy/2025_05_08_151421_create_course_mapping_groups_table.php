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
        Schema::create('course_mapping_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained();
            $table->foreignId('year_level_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->string('effective_sy');
            $table->string('name')->nullable(); // optional, for custom naming like "BSIT 1st Year Sem 1"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_mapping_groups');
    }
};
