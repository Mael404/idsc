<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Add favicon logos -->
    <link rel="icon" type="image/png" href="{{ asset('img/idslogo.png') }}">

    <!-- accordion-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <meta name="csrf-token " content="{{ csrf_token() }}">
    <title>@yield('tab_title') | {{ config('app.name') }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">




</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        {{-- Dynamic Sidebar based on user role --}}
        {{-- TEMP: Set default role to 'vpadmin' --}}
        @php
            $userRole = Auth::user()->role; // Fetch the role of the authenticated user
        @endphp

        {{-- Sidebar based on role --}}
        @switch($userRole)
            @case('admin')
                @include('admin.admin_sidebar')
            @break

            @case('vp_academic')
                @include('vp_academic.vpacademic_sidebar')
            @break

            @case('student')
                @include('student.student_sidebar')
            @break

            @case('vp_admin')
                @include('vp_admin.vpadmin_sidebar')
            @break

            @case('registrar')
                @include('registrar.registrar_sidebar')
            @break

            @case('cashier')
                @include('cashier.cashier_sidebar')
            @break

            @case('accounting')
                @include('accountant.accountant_sidebar')
            @break

            @case('president')
                @include('president.president_sidebar')
            @break

            @default
                {{-- Optional: fallback sidebar --}}
                <p>No sidebar available for this role.</p>
        @endswitch



        @yield('content')

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <!-- Authentication -->
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <!-- Logout button -->
                    <button class="btn btn-primary" type="button"
                        onclick="document.getElementById('logout-form').submit();">
                        Logout
                    </button>
                </div>

            </div>
        </div>
    </div>


    <!-- Bootstrap 5 JS (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">


    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Add this in your HTML head or before your script tag -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.js') }}"></script>

    <!-- Page-specific scripts -->
    @yield('scripts')


    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</body>

</html>
