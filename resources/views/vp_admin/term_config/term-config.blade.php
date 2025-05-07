@extends('layouts.main')

@section('tab_title', 'Manage Semester')
@section('vpadmin_sidebar')
    @include('vp_admin.vpadmin_sidebar')
@endsection

@section('content')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            @include('layouts.topbar')

            <div class="container-fluid">
                {{-- Success Alert --}}
                @include('layouts.success-message')

                {{-- Page Header --}}
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage School Years</h1>

                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#trashedModal"
                            title="View Trash">
                            <i class="fas fa-trash-alt"></i>
                        </button>

                        <button class="btn btn-primary" data-toggle="modal" data-target="#addSchoolYearModal">
                            Add New School Year
                        </button>
                    </div>
                </div>


                <!-- Trashed School Years Modal -->
                <div class="modal fade" id="trashedModal" tabindex="-1" aria-labelledby="trashedModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title" id="trashedModalLabel">Deleted School Years</h5>
                                <button type="button" class="close text-white"
                                    data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                @if ($trashedSchoolYears->isEmpty())
                                    <p>No deleted school years found.</p>
                                @else
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>School Year</th>
                                                <th>Semester</th>

                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trashedSchoolYears as $sy)
                                                <tr>
                                                    <td>{{ $sy->name }}</td>
                                                    <td>{{ $sy->semester }}</td>


                                                    <td class="d-flex gap-1">


                                                        <!-- Restore -->
                                                        <form action="{{ route('school-years.restore', $sy->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-sm btn-success" title="Restore">
                                                                <i class="fas fa-undo"></i>
                                                            </button>
                                                        </form>

                                                        <!-- Force Delete -->
                                                        <form action="{{ route('school-years.forceDelete', $sy->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to permanently delete this item?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger"
                                                                title="Delete Permanently">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>

                                                    </td>


                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>


                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Add School Year Modal --}}
                <div class="modal fade" id="addSchoolYearModal" tabindex="-1" aria-labelledby="addSchoolYearModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('school-years.store') }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add School Year</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    <!-- School Year Field -->
                                    <div class="form-group">
                                        <label>School Year</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="2024-2025" required value="{{ old('name') }}">

                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Default Unit Price -->
                                    <div class="form-group">
                                        <label>Default Unit Price (optional)</label>
                                        <input type="number" step="0.01" class="form-control" name="default_unit_price"
                                            value="{{ old('default_unit_price') }}">
                                    </div>

                                    <!-- Active Status -->
                                    <div class="form-group">
                                        <label>Is Active?</label>
                                        <select class="form-control" name="is_active">
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- Semester Field -->
                                    <div class="form-group">
                                        <label>Semester</label>
                                        <input type="text" class="form-control @error('semester') is-invalid @enderror"
                                            name="semester" placeholder="Enter semester" required
                                            value="{{ old('semester') }}">

                                        @error('semester')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="submit">Add School Year</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                {{-- School Years Table --}}
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="schoolYearsTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>School Year</th>
                                                <th>Unit Price</th>
                                                <th>Semester</th> <!-- Added Semester column -->

                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($schoolYears as $sy)
                                                <tr class="{{ $sy->deleted_at ? 'table-danger' : '' }}">
                                                    <td>{{ $sy->name }}</td>
                                                    <td>{{ $sy->default_unit_price ?? 'N/A' }}</td>
                                                    <td class="text-center">{{ $sy->semester ?? 'N/A' }}</td>
                                                    <!-- Display Semester -->

                                                    <td class="text-center">
                                                        @if ($sy->is_active)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">

                                                            <!-- Edit Button -->
                                                            <button
                                                                class="btn btn-sm btn-info d-flex justify-content-center align-items-center"
                                                                data-toggle="modal" data-target="#editSchoolYearModal"
                                                                data-id="{{ $sy->id }}"
                                                                data-name="{{ $sy->name }}"
                                                                data-semester="{{ $sy->semester }}"
                                                                data-default_unit_price="{{ $sy->default_unit_price }}"
                                                                data-is_active="{{ $sy->is_active }}" title="Edit"
                                                                style="width: 30px; height: 30px; padding: 0;">
                                                                <i class="fas fa-edit" style="font-size: 14px;"></i>
                                                            </button>


                                                            <!-- Edit School Year Modal -->
                                                            <div class="modal fade" id="editSchoolYearModal"
                                                                tabindex="-1" aria-labelledby="editSchoolYearModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <form method="POST" id="editSchoolYearForm">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">Edit School Year
                                                                                </h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal"><span>&times;</span></button>
                                                                            </div>

                                                                            <div class="modal-body">

                                                                              
                                                                                <div class="form-group">
                                                                                    <label>School Year</label>
                                                                                    <input type="text"
                                                                                        class="form-control @error('name') is-invalid @enderror"
                                                                                        name="name"
                                                                                        id="editSchoolYearName"
                                                                                        value="{{ old('name') }}"
                                                                                        required>
                                                                                    @error('name')
                                                                                        <span
                                                                                            class="text-danger small">{{ $message }}</span>
                                                                                    @enderror
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label>Default Unit Price
                                                                                        (optional)</label>
                                                                                    <input type="number" step="0.01"
                                                                                        class="form-control @error('default_unit_price') is-invalid @enderror"
                                                                                        name="default_unit_price"
                                                                                        id="editSchoolYearDefaultUnitPrice"
                                                                                        value="{{ old('default_unit_price') }}">
                                                                                    @error('default_unit_price')
                                                                                        <span
                                                                                            class="text-danger small">{{ $message }}</span>
                                                                                    @enderror
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label>Semester</label>
                                                                                    <input type="text"
                                                                                        class="form-control @error('semester') is-invalid @enderror"
                                                                                        name="semester"
                                                                                        id="editSchoolYearSemester"
                                                                                        value="{{ old('semester') }}"
                                                                                        required>
                                                                                    @error('semester')
                                                                                        <span
                                                                                            class="text-danger small">{{ $message }}</span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button class="btn btn-primary"
                                                                                    type="submit">Save Changes</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>



                                                            <!-- Toggle Active/Inactive Button -->
                                                            <form action="{{ route('school-years.set-active', $sy->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button
                                                                    class="btn btn-sm {{ $sy->is_active ? 'btn-danger' : 'btn-success' }} d-flex justify-content-center align-items-center"
                                                                    title="{{ $sy->is_active ? 'Set as Inactive' : 'Set as Active' }}"
                                                                    style="width: 30px; height: 30px; padding: 0;">
                                                                    <i class="fas {{ $sy->is_active ? 'fa-times' : 'fa-check' }}"
                                                                        style="font-size: 14px;"></i>
                                                                </button>
                                                            </form>

                                                            <!-- Soft Delete Button -->
                                                            <button
                                                                class="btn btn-sm btn-danger d-flex justify-content-center align-items-center"
                                                                data-toggle="modal" data-target="#confirmDeleteModal"
                                                                data-url="{{ route('school-years.destroy', $sy->id) }}"
                                                                title="Move to Trash"
                                                                style="width: 30px; height: 30px; padding: 0;">
                                                                <i class="fas fa-trash-alt" style="font-size: 14px;"></i>
                                                            </button>
                                                            <!-- Confirm Delete Modal -->
                                                            <div class="modal fade" id="confirmDeleteModal"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="confirmDeleteModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <form method="POST" id="deleteForm">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-danger text-white">
                                                                                <h5 class="modal-title"
                                                                                    id="confirmDeleteModalLabel">Confirm
                                                                                    Deletion</h5>
                                                                                <button type="button"
                                                                                    class="close text-white"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                Are you sure you want to move this school
                                                                                year to the trash?<br>
                                                                                You can restore it later from the Trash
                                                                                modal.
                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-dismiss="modal">Cancel</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-danger">Yes, Move to
                                                                                    Trash</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
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
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#schoolYearsTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>
    <script>
        $('#confirmDeleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var action = button.data('url'); // the route from the button's data-url

            var form = $(this).find('#deleteForm');
            form.attr('action', action);
        });
    </script>
    <script>
        $('#editSchoolYearModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract the ID from the data-id attribute
            var name = button.data('name');
            var semester = button.data('semester');
            var default_unit_price = button.data('default_unit_price');

            // Set the form action dynamically to include the ID in the URL
            var formAction = '{{ route('school-years.update', ':id') }}';
            formAction = formAction.replace(':id', id); // Replace :id with actual ID
            $('#editSchoolYearForm').attr('action', formAction);

            // Fill the modal input fields with current data
            $('#editSchoolYearName').val(name);
            $('#editSchoolYearSemester').val(semester);
            $('#editSchoolYearDefaultUnitPrice').val(default_unit_price);
        });
    </script>

@endsection
