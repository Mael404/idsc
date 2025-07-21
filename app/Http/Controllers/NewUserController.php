<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NewUserController extends Controller
{
    public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:cashier,registrar,instructor',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ], [
            'email.unique' => 'This email already exists.',
        ]);

        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10),
        ]);

        return redirect()->back()->with('success', 'User created successfully!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Check for duplicate email and flash custom message
        if ($e->validator->errors()->has('email')) {
            return redirect()->back()->withInput()->with('error', $e->validator->errors()->first('email'));
        }

        // Default validation error fallback
        return redirect()->back()->withInput()->with('error', 'There was a problem creating the user.');
    }
}

}
