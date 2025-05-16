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
        Schema::table('courses', function (Blueprint $table) {
            // Modify the existing 'units' column to be a decimal and nullable
            $table->decimal('units', 5, 2)->nullable()->change();  

            // Add the 'active' column with a default value of true
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Reverse the changes (drop 'active' and revert 'units' back to integer)
            $table->dropColumn('active');  // Drop the 'active' column
            $table->integer('units')->change();  // Revert 'units' back to integer (you can adjust this as needed)
        });
    }
};
