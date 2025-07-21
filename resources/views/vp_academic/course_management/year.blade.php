@extends('layouts.main')

@section('tab_title', 'Manage School Year')
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

                <!-- Page Heading with Button on Same Row -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Year Levels</h1>

                    <!-- Button to Open Add Year Level Form -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addYearLevelModal">
                        Add New Year Level
                    </button>
                </div>
                <!-- Edit Year Level Modal -->
                <div class="modal fade" id="editYearLevelModal" tabindex="-1" aria-labelledby="editYearLevelModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="editYearLevelModalLabel">Edit Year Level</h5>

                            </div>
                            <div class="modal-body">
                                <form id="editYearLevelForm" method="POST" action="">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="modal-name" class="form-label">Year Level Name</label>
                                        <input type="text" id="modal-name" name="name" class="form-control">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Year Level Modal -->
                <div class="modal fade" id="addYearLevelModal" tabindex="-1" aria-labelledby="addYearLevelModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('year_levels.store') }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Year Level</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Year Level Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Add Year Level</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Display Year Levels in Table -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="yearLevelsTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <!-- Your Table with Year Level Information -->
                                            @foreach ($year_levels as $year_level)
                                                <tr>
                                                    <td class="text-center">{{ $year_level->name }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">
                                                            <!-- View/Edit Button -->
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-info btn-sm toggle-year-level-btn"
                                                                data-id="{{ $year_level->id }}"
                                                                data-name="{{ $year_level->name }}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <!-- Delete Button -->
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $year_level->id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                        <!-- Delete Confirmation Modal -->
                                                        <div class="modal fade" id="deleteModal{{ $year_level->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="deleteModalLabel{{ $year_level->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content border-danger">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title">Delete Year Level</h5>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete
                                                                        <strong>{{ $year_level->name }}</strong>?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form method="POST"
                                                                            action="{{ route('year_levels.destroy', $year_level->id) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Cancel</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">Yes,
                                                                                Delete</button>
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
                                    {{-- {{ $year_levels->links() }} --}}
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
    <script src="{{ asset('js/year.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#yearLevelsTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>
@endsection
