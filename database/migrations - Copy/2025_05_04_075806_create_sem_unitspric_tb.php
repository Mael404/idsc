<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'S.Y. 2024-2025'
            $table->unsignedBigInteger('year_level_id');
            $table->year('sy_start');
            $table->year('sy_end');
            $table->decimal('default_unit_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Optional FK setup
            // $table->foreign('year_level_id')->references('id')->on('year_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_years');
    }
};
