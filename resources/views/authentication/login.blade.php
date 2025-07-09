@extends('layouts.login')

@section('tab_title', 'Log-in')

@section('content')

    <div class="container" style="max-width: 90%; padding: 2rem;">
        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-xl-8 col-lg-10 col-md-10">
                <div class="card o-hidden border-0 shadow-lg my-5" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h3 class="h3 text-gray-900 mb-4" style="font-size: 2.5rem;">Welcome</h3>
                                    </div>

                                    <!-- Session Status: This is the part you asked for -->
                                    <x-auth-session-status class="mb-4" :status="session('status')" />

                                    <!-- Laravel Authentication Form -->
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <!-- Email Address -->
                                        <div class="form-group">
                                            <label for="email" style="font-weight: bold; color: black; font-size: 1rem;">Email Address</label>
                                            <input type="email" class="form-control form-control-user"
                                                   id="email" name="email" aria-describedby="emailHelp"
                                                   placeholder="Enter Email Address..."
                                                   value="{{ old('email') }}" required autofocus
                                                   style="font-size: 0.95rem; padding: 0.9rem;">
                                            @error('email')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group">
                                            <label for="password" style="font-weight: bold; color: black; font-size: 1rem;">Password</label>
                                            <input type="password" class="form-control form-control-user"
                                                   id="password" name="password"
                                                   placeholder="Password" required autocomplete="current-password"
                                                   style="font-size: 0.95rem; padding: 0.9rem;">
                                            @error('password')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Remember Me -->
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                                <label class="custom-control-label" for="remember_me" style="font-size: 1rem;">Remember Me</label>
                                            </div>
                                        </div>

                                        <!-- Login Button -->
                                        <button type="submit" class="btn btn-primary btn-user btn-block" style="font-size: 1rem; padding: 1rem;">
                                            Login
                                        </button>
                                    </form>
                                    
                                    <hr>
                                    
                                    <div class="text-center">
                                        <a class="small" href="{{ route('password.request') }}" style="font-size: 1.1rem;">Forgot Password?</a>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
