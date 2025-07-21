<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->string('remarks')->nullable()->after('finals_due');
        });
    }

    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
};
