<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert a single user with the accountant role
        DB::table('users')->insert([
            [
                'name' => 'President',
                'role' => 'president', // Role for the new user
                'email' => 'president@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'), // Use a secure password
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
