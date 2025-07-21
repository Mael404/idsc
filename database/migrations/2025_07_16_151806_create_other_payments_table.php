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
        Schema::create('other_payments', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20)->nullable()->index();
            $table->string('lrn_number', 255)->nullable()->index();
            $table->string('school_year');
            $table->string('semester');
            $table->enum('grading_period', ['prelims', 'midterms', 'prefinals', 'finals']);
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('remaining_balance', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->timestamp('payment_date')->useCurrent();
            $table->timestamps();
            $table->string('or_number')->nullable()->index();
            $table->boolean('is_void')->default(0);
            $table->string('status', 50)->default('completed');
            $table->timestamp('voided_at')->nullable();
            $table->unsignedBigInteger('voided_by')->nullable()->index();
            $table->string('payment_method', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_payments');
    }
};
