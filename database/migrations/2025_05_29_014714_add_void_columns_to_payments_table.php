<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoidColumnsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
     
            $table->timestamp('voided_at')->nullable()->after('is_void');
            $table->unsignedBigInteger('voided_by')->nullable()->after('voided_at');
            
            // Add foreign key constraint if you want to track which user voided the payment
            $table->foreign('voided_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['voided_by']);
            
            // Then drop the columns
            $table->dropColumn(['is_void', 'voided_at', 'voided_by']);
        });
    }
}