@extends('layouts.main')

@section('tab_title', 'Manage Courses')
@section('vpacademic_sidebar')
    @include('vp_academic.vpacademic_sidebar')
@endsection

@section('content')
    <!-- Content Wrasssspper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            @include('layouts.topbar')

            <!-- Begin Page Content -->
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <!-- Page Heading with Button on Same Row -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Courses</h1>

                    <!-- Button to Open Add Course Form -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addCourseModal">
                        Add New Course
                    </button>
                </div>


                <!-- Edit/View Modal -->
                <div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" id="editCourseForm">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCourseModalLabel">View/Edit Course</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Course Code</label>
                                        <input type="text" class="form-control" id="modal-code" name="code" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Course Name</label>
                                        <input type="text" class="form-control" id="modal-name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" id="modal-description" name="description" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Units</label>
                                        <input type="number" class="form-control" id="modal-units" name="units"
                                            min="0" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal for Add Course Form -->
                <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addCourseModalLabel">Add Course</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('courses.store') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="code">Course Code</label>
                                        <input type="text" class="form-control" id="code" name="code" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Course Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Course Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="units">Units</label>
                                        <input type="number" class="form-control" id="units" name="units"
                                            min="0" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Add Course</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display Courses in Table -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">List of Courses</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="coursesTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Name</th>
                                                <th>Description</th>
                                                <th>Units</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($courses as $course)
                                                <tr>
                                                    <td>{{ $course->code }}</td>
                                                    <td>{{ $course->name }}</td>
                                                    <td>{{ $course->description }}</td>
                                                    <td>{{ $course->units }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge {{ $course->active ? 'badge-success' : 'badge-danger' }}">
                                                            {{ $course->active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-info btn-sm fixed-width-btn view-course-btn"
                                                                data-id="{{ $course->id }}"
                                                                data-code="{{ $course->code }}"
                                                                data-name="{{ $course->name }}"
                                                                data-description="{{ $course->description }}"
                                                                data-units="{{ $course->units }}" data-bs-toggle="modal"
                                                                data-bs-target="#courseModal">
                                                                View
                                                            </a>
                                                            <form
                                                                action="{{ route('courses.toggleActive', $course->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-warning btn-sm fixed-width-btn">
                                                                    {{ $course->active ? 'Deactivate' : 'Activate' }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>


                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->
        </div>
        <!-- End Page Content -->


        <!-- End of Main Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->
@endsection

@section('scripts')
    <script src="{{ asset('js/courses.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#coursesTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>
@endsection
