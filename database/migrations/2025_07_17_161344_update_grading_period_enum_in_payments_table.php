<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        DB::statement("ALTER TABLE payments MODIFY grading_period ENUM('prelims','midterms','prefinals','finals','Initial Payment') NOT NULL;");
    }

    public function down()
    {
        DB::statement("ALTER TABLE payments MODIFY grading_period ENUM('prelims','midterms','prefinals','finals') NOT NULL;");
    }
};
