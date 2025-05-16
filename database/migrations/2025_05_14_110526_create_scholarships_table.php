<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipsTable extends Migration
{
    public function up()
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('discount', 8, 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes(); // For soft delete feature
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scholarships');
    }
}
