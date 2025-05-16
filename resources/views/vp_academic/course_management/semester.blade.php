@extends('layouts.main')

@section('tab_title', 'Manage Semester')
@section('vpacademic_sidebar')
    @include('vp_academic.vpacademic_sidebar')
@endsection

@section('content')
    <!-- Content Wrasssspper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            @include('layouts.topbar')

            <div class="container-fluid">
                @include('layouts.success-message')
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Semesters</h1>

                    <!-- Button to Open Add Semester Form -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addSemesterModal">
                        Add New Semester
                    </button>
                </div>

                <!-- Edit Semester Modal -->
                <div class="modal fade" id="editSemesterModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="editSemesterForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Edit Semester</h5>
                                </div>
                                <div class="modal-body">
                                    <input type="text" id="modal-semester-name" name="name" class="form-control"
                                        required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="cancelButton">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Semester Modal -->
                <div class="modal fade" id="addSemesterModal" tabindex="-1" aria-labelledby="addSemesterModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('semesters.store') }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Semester</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Semester Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Add Semester</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Display Semesters in Table -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="semestersTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($semesters as $semester)
                                                <tr>
                                                    <td class="text-center">{{ $semester->name }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">
                                                            <!-- View/Edit Button -->
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-info btn-sm toggle-semester-btn"
                                                                data-id="{{ $semester->id }}"
                                                                data-name="{{ $semester->name }}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>

                                                            <!-- Delete Button -->
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $semester->id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                        <!-- Delete Confirmation Modal -->
                                                        <div class="modal fade" id="deleteModal{{ $semester->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="deleteModalLabel{{ $semester->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content border-danger">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title">Delete Semester</h5>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete
                                                                        <strong>{{ $semester->name }}</strong>?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form method="POST"
                                                                            action="{{ route('semesters.destroy', $semester->id) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button" class="btn btn-secondary"
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

        </div>
        <!-- End Page Content -->


        <!-- End of Main Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->
@endsection

@section('scripts')
    <script src="{{ asset('js/semester.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#semestersTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>
@endsection
