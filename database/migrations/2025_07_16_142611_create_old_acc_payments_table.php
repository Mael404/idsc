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
        Schema::create('old_acc_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('or_number')->unique();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('particulars');
            $table->timestamp('payment_date');
            $table->boolean('is_void')->default(false);   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('old_acc_payments');
    }
};
