<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;  // Import the Str facade
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a Faker instance
        $faker = Faker::create();

        // Create some sample users with different roles
        DB::table('users')->insert([
            [
                'name' => 'VP Admin',
                'role' => 'vp_admin',
                'email' => 'vp_admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // Hashed password
                'remember_token' => Str::random(10),  // Updated to use Str::random()
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Registrar',
                'role' => 'registrar',
                'email' => 'registrar@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),  // Updated to use Str::random()
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VP Academics',
                'role' => 'vp_academics',
                'email' => 'vp_academics@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),  // Updated to use Str::random()
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cashier',
                'role' => 'cashier',
                'email' => 'cashier@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),  // Updated to use Str::random()
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // You can add more users as needed
        ]);
    }
}
