<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('billing_histories', function (Blueprint $table) {
            $table->id();
            
            // Match the data type with your billing table (unsigned bigint)
            $table->unsignedBigInteger('billing_id');
            
            // Make user_id nullable since student_id in billing is varchar
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->string('action', 50); // e.g., 'update', 'payment', 'adjustment'
            $table->text('description')->nullable();
            
            // Using same decimal precision as your billing table
            $table->decimal('old_amount', 10, 2)->nullable();
            $table->decimal('new_amount', 10, 2)->nullable();
            
            $table->json('changes')->nullable(); // Stores all field changes
            $table->timestamps();

            // Add foreign key constraint properly
            $table->foreign('billing_id')
                  ->references('id')
                  ->on('billings')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Indexes for better performance
            $table->index('billing_id');
            $table->index('action');
        });
    }

    public function down()
    {
        Schema::dropIfExists('billing_histories');
    }
};