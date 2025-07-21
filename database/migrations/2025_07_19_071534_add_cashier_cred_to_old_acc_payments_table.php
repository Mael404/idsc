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
        Schema::table('old_acc_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->unsignedInteger('voided_by')->nullable();
            $table->boolean('is_void')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('old_acc_payments', function (Blueprint $table) {
            //
        });
    }
};
