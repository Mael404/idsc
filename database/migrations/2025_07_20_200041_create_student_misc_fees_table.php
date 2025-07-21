<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_misc_fees', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');  // Links to admissions.student_id
            $table->unsignedBigInteger('billing_id')->nullable();  // Links to billings.id
            $table->string('school_year');
            $table->string('semester');
            $table->string('fee_name');
            $table->decimal('amount', 10, 2);
            $table->boolean('is_required')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')
                  ->references('student_id')
                  ->on('admissions')
                  ->onDelete('cascade');
                  
            $table->foreign('billing_id')
                  ->references('id')
                  ->on('billings')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_misc_fees');
    }
};