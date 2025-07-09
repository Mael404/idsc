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
                    <div id="success-alert" class="popup-alert fadeDownIn shadow rounded-lg p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold fs-5 text-success-custom">
                                {{ session('success') }}
                                <i class="fas fa-check-circle ms-1"></i>
                                <!-- Added ms-3 for spacing and positioned icon on the right -->
                            </span>
                        </div>
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
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="editCourseModalLabel">View/Edit Course</h5>
                                   
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
                                    <div class="form-group">
                                        <label>Lecture Hours</label>
                                        <input type="number" step="0.1" class="form-control" id="modal-lecture-hours"
                                            name="lecture_hours" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Lab Hours</label>
                                        <input type="number" step="0.1" class="form-control" id="modal-lab-hours"
                                            name="lab_hours" required>
                                    </div>

                                    <!-- Dropdown for Prerequisite Course -->
                                    <div class="form-group">
                                        <label for="prerequisite_id">Prerequisite (optional):</label>
                                        <select name="prerequisite_id" id="modal-prerequisite" class="form-control">
                                            <option value="">-- None --</option>
                                            @foreach ($allCourses as $course)
                                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                                            @endforeach
                                        </select>
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
                                <form action="{{ route('courses.store') }}" method="POST">
                                    @csrf
                                    <!-- Course Details Inputs -->
                                    <div class="form-group">
                                        <input type="text" name="code" class="form-control" placeholder="Course Code"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Course Name" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="description" class="form-control" placeholder="Description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="units" step="0.1" class="form-control"
                                            placeholder="Units" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="lecture_hours" step="0.1" class="form-control"
                                            placeholder="Lecture Hours" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="lab_hours" step="0.1" class="form-control"
                                            placeholder="Lab Hours" required>
                                    </div>


                                    <!-- Dropdown for Prerequisite Cssourse -->
                                    <div class="form-group">
                                        <label for="prerequisite_id">Prerequisite (optional):</label>
                                        <select name="prerequisite_id" class="form-control">
                                            <option value="">-- None --</option>
                                            @foreach ($allCourses as $course)
                                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Course</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Display Courses in Table -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="coursesTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Name</th>
                                                <th>Description</th>
                                                <th>Units</th>
                                                <th>Lecture Hrs</th>
                                                <th>Lab Hrs</th>
                                                <th>Prerequisite</th> <!-- Added column for Prerequisite -->
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
                                                    <td>{{ $course->lecture_hours }}</td>
                                                    <td>{{ $course->lab_hours }}</td>

                                                    <!-- Show the prerequisite name if it exists -->
                                                    <td>{{ $course->prerequisite ? $course->prerequisite->name : 'None' }}
                                                    </td>

                                                    <td class="text-center">
                                                        <span
                                                            class="badge {{ $course->active ? 'badge-success' : 'badge-danger' }}">
                                                            {{ $course->active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">
                                                            <!-- Edit Button -->
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-info btn-sm fixed-width-btn view-course-btn"
                                                                data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                                                data-id="{{ $course->id }}"
                                                                data-code="{{ $course->code }}"
                                                                data-name="{{ $course->name }}"
                                                                data-description="{{ $course->description }}"
                                                                data-units="{{ $course->units }}"
                                                                data-lecture-hours="{{ $course->lecture_hours }}"
                                                                data-lab-hours="{{ $course->lab_hours }}"
                                                                data-prerequisite-id="{{ $course->prerequisite_id }}">
                                                                <i class="fas fa-edit"></i> <!-- Edit Icon -->
                                                            </a>
                                                            <!-- Activate/Deactivate Button -->
                                                            <form
                                                                action="{{ route('courses.toggleActive', $course->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-warning btn-sm fixed-width-btn">
                                                                    <i
                                                                        class="fas {{ $course->active ? 'fa-times' : 'fa-check' }}"></i>
                                                                </button>
                                                            </form>

                                                            <!-- Delete Button -->
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm fixed-width-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $course->id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>

                                                        <!-- DELETE MODAL -->
                                                        <div class="modal fade" id="deleteModal{{ $course->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="deleteModalLabel{{ $course->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content border-danger">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title"
                                                                            id="deleteModalLabel{{ $course->id }}">
                                                                            Delete Course</h5>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete
                                                                        <strong>{{ $course->name }}</strong>
                                                                        ({{ $course->code }})
                                                                        ?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form method="POST"
                                                                            action="{{ route('courses.destroy', $course->id) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Cancel</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">Yes, Delete</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove('fadeDownIn');
                    alert.classList.add('fadeOut');
                    setTimeout(() => {
                        alert.remove();
                    }, 400);
                }, 2500);
            }
        });
    </script>


@endsection
