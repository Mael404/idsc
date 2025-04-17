@extends('layouts.login')

@section('tab_title', 'Reset Password')


@section('content')
<body class="bg-gradient-primary">

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
                                    <form class="user">
                                        <div class="form-group">
                                            <label for="exampleInputEmail" style="font-weight: bold; color: black; font-size: 1rem;">Email Address</label>
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." style="font-size: 0.95rem; padding: 0.9rem;">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword" style="font-weight: bold; color: black; font-size: 1rem;">Password</label>
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" style="font-size: 0.95rem; padding: 0.9rem;">
                                        </div>
                                    
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck" style="font-size: 1rem;">Remember Me</label>
                                            </div>
                                        </div>
                                        <a href="index.html" class="btn btn-primary btn-user btn-block" style="font-size: 1rem; padding: 1rem;">
                                            Login
                                        </a>
                                    </form>
                                    
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="{{route('forgot-password')}}" style="font-size: 1.1rem;">Forgot Password?</a>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


</body>
@endsection
