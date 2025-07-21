@extends('layouts.main')

@section('tab_title', 'Manage Programs')
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

         


                <!-- Page Heading with Button on Same Row -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Programs</h1>

                    <!-- Button to Open Add Program Form -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addProgramModal">
                        Add New Program
                    </button>
                </div>

                <!-- View/Edit Program Modal -->
                <div class="modal fade" id="viewEditProgramModal" tabindex="-1" aria-labelledby="viewEditProgramModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" id="viewEditProgramForm">
                            @csrf
                            @method('PUT') <!-- This ensures the form sends a PUT request -->
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="viewEditProgramModalLabel">View/Edit Program</h5>
                                 
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="program-name">Program Name</label>
                                        <input type="text" class="form-control" id="program-name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="program-code">Program Code</label>
                                        <input type="text" class="form-control" id="program-code" name="code" required>
                                    </div>
                                
                                    
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Modal for Add Program -->
                <div class="modal fade" id="addProgramModal" tabindex="-1" aria-labelledby="addProgramModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('programs.store') }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Program</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Program Code</label>
                                        <input type="text" class="form-control" name="code" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Program Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                   
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Add Program</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Display Programs in Table -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="coursesTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                            
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($programs as $program)
                                                <tr>
                                                    <td>{{ $program->code }}</td>
                                                    <td>{{ $program->name }}</td>
                                              
                                                    <td class="text-center">
                                                        <span
                                                            class="badge {{ $program->active ? 'badge-success' : 'badge-danger' }}">
                                                            {{ $program->active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">
                                                            <!-- View Button (Icon) -->
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-info btn-sm fixed-width-btn toggle-program-btn"
                                                                data-id="{{ $program->id }}"
                                                                data-name="{{ $program->name }}"
                                                                data-code="{{ $program->code }}"
                                                                data-effective_school_year="{{ $program->effective_school_year }}">
                                                                <i class="fas fa-edit"></i> <!-- Changed Icon -->
                                                            </a>


                                                            <!-- Activate/Deactivate Form (Icon) -->
                                                            <form
                                                                action="{{ route('programs.toggleActive', $program->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-warning btn-sm fixed-width-btn">
                                                                    <i
                                                                        class="fas {{ $program->active ? 'fa-times' : 'fa-check' }}"></i>
                                                                    <!-- Toggle Icon (Activate: Check, Deactivate: Times) -->
                                                                </button>
                                                            </form>

                                                            <!-- Delete Button (Icon) -->
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm fixed-width-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $program->id }}">
                                                                <i class="fas fa-trash-alt"></i> <!-- Delete Icon -->
                                                            </button>
                                                        </div>

                                                        <!-- DELETE CONFIRMATION MODAL -->
                                                        <div class="modal fade" id="deleteModal{{ $program->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="deleteModalLabel{{ $program->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content border-danger">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title"
                                                                            id="deleteModalLabel{{ $program->id }}">
                                                                            Delete Program</h5>

                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete
                                                                        <strong>{{ $program->name }}</strong>
                                                                        ({{ $program->code }})
                                                                        ?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form method="POST"
                                                                            action="{{ route('programs.destroy', $program->id) }}">
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


        </div>
        <!-- End Page Content -->



        <!-- End of Main Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->
@endsection

@section('scripts')
    <script src="{{ asset('js/programs.js') }}"></script>

    <!-- DataTables JS -->

    <script>
        $(document).ready(function() {
            $('#coursesTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>
@endsection
