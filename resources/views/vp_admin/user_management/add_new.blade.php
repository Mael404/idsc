@extends('layouts.main')

@section('tab_title', 'User Management')

@section('vpadmin_sidebar')
    @include('vp_admin.vpadmin_sidebar')
@endsection

@section('content')
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        @include('layouts.topbar')

        <div class="container-fluid">
     @include('layouts.success-message')
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Add New User</h1>
            </div>

      

            <!-- User Creation Form -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">User Registration Form</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control" required>
                                        <option value="">-- Select Role --</option>
                                        <option value="cashier">Cashier</option>
                                        <option value="registrar">Registrar</option>
                                        <option value="instructor">Instructor</option>
                                    </select>
                                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Create User</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- /.container-fluid -->

        @include('layouts.footer')
    </div> <!-- End of Content -->
</div> <!-- End of Content Wrapper -->


@endsection
