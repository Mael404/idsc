<!-- BACK END FUNCTION IS IN THE BILLING CONTROLLER -->
@extends('layouts.main')

@section('tab_title', 'Enrollment Heatmap')
@section('president_sidebar')
@include('president.president_sidebar')
@endsection

@section('content')

<div id="content-wrapper" class="d-flex flex-column">

    <div id="content">

        @include('layouts.topbar')

        <div class="container-fluid">

            <!-- Page Headifng -->
            <div class="mb-4 d-sm-flex align-items-center justify-content-between">
                <h1 class="mb-0 text-gray-800 h3">Enrollment Heatmap</h1>

            </div>

        </div>
        <!-- /.container-fluid -->

        <!-- End of Main Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->
</div>
@endsection