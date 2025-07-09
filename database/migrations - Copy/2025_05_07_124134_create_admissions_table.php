<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('contact_number');
            $table->string('email');
            $table->string('father_last_name')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_middle_name')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('father_profession')->nullable();
            $table->string('father_industry')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('mother_first_name')->nullable();
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('mother_profession')->nullable();
            $table->string('mother_industry')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('religion')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('course');
            $table->string('major')->nullable();
            $table->enum('admission_status', ['highschool', 'transferee', 'returnee'])->nullable();
            $table->string('student_no')->nullable();
            $table->string('admission_year')->nullable();
            $table->string('scholarship')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('previous_school_address')->nullable();
            $table->string('elementary_school')->nullable();
            $table->string('elementary_address')->nullable();
            $table->string('secondary_school')->nullable();
            $table->string('secondary_address')->nullable();
            $table->string('honors')->nullable();
            // Add the following fields
            $table->string('school_year');
            $table->string('semester');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admissions');
    }
}
