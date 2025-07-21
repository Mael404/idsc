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
        Schema::create('old_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('course_strand');
            $table->string('year_graduated');
            $table->decimal('balance', 10, 2);  
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('old_accounts');
    }
};
