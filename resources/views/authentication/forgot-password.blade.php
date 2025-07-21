@extends('layouts.login')

@section('tab_title', 'Reset Password')


@section('content')

    <div class="container" style="max-width: 80%; padding: 2rem;">
        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-xl-10 col-lg-10 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image mb-5"></div>

                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                        <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                            and we'll send you a link to reset your password!</p>
                                    </div>
                                    <form class="user">
                                        <div class="form-group">
                                            <label for="exampleInputEmail"
                                                style="font-weight: bold; color: black; font-size: 1rem;">Email
                                                Address</label>
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..."
                                                style="font-size: 0.95rem; padding: 0.9rem;">
                                        </div>
                                        <a href="login.html" class="btn btn-primary btn-user btn-block"
                                            style="font-size: 1rem; padding: 1rem;">
                                            Reset Password
                                        </a>
                                    </form>

                                    <hr>

                                    <div class="text-center">
                                        <a class="small" href="{{route('login')}}" style="font-size: 1.0rem;">Already have an
                                            account? Login!</a>
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
