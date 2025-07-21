<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();  // Primary key (auto-incrementing)
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('zip_code');
            $table->string('contact_number');
            $table->string('email');
            $table->string('father_last_name');
            $table->string('father_first_name');
            $table->string('father_middle_name')->nullable();
            $table->string('father_contact');
            $table->string('father_profession')->nullable();
            $table->string('father_industry')->nullable();
            $table->string('mother_last_name');
            $table->string('mother_first_name');
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_contact');
            $table->string('mother_profession')->nullable();
            $table->string('mother_industry')->nullable();
            $table->string('gender');
            $table->date('birthdate');
            $table->string('birthplace');
            $table->string('citizenship');
            $table->string('religion');
            $table->string('civil_status');
            $table->foreignId('course_mapping_id')->constrained();  // Assuming there's a CourseMapping model and table
            $table->string('major');
            $table->string('admission_status');
            $table->string('student_no');
            $table->year('admission_year');
            $table->string('scholarship')->nullable();
            $table->string('previous_school');
            $table->string('previous_school_address');
            $table->string('elementary_school');
            $table->string('elementary_address');
            $table->string('secondary_school');
            $table->string('secondary_address');
            $table->string('honors')->nullable();
            $table->string('school_year');
            $table->string('semester');
            $table->timestamps();  // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admissions');
    }
}
