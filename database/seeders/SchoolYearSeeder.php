<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert the default school year entry
        DB::table('school_years')->insert([
            'name' => '2024-2025', // Name of the school year
            'default_unit_price' => 1000.00, // Set a default value for unit price
            'semester' => '1st Semester', // Default to 1st Semester
            'is_active' => true, // Set to active
            'created_at' => Carbon::now(), // Current timestamp
            'updated_at' => Carbon::now(), // Current timestamp
        ]);
    }
}
