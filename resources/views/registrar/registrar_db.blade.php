@extends('layouts.main')

@section('tab_title', 'Dashboard')
@section('registrar_sidebar')
    @include('registrar.registrar_sidebar')
@endsection

@section('content')

    <div id="content-wrapper" class="d-flex flex-column">


        <div id="content">

            @include('layouts.topbar')


            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Registrar Dashboard</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Current Courses -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Current Courses</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Blocks and Sections -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Blocks & Sections</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New Users -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">New Users</div>
                                        <div class="h6 mb-0 text-gray-800">
                                            Leemae - Professor<br>
                                            John - Student<br>
                                            Mae - Instructor
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students Enrolled -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Students Enrolled</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">120</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Students per Course (Bar Chart) -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Students per Course</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="studentsPerCourseChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Study Resources -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Student Statistics (2018-2025)</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="studyResourcesChart"></canvas>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- End Page Content -->

            <!-- End Page Content -->


        </div>
        <!-- End of Main Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->
@endsection
