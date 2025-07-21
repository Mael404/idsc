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
                    <h1 class="h3 mb-0 text-gray-800">Manage Scholarships</h1>

                    <div class="d-flex gap-2">
                        <!-- View Trash Button -->
                        <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#trashedModal"
                            title="View Trash">
                            <i class="fas fa-trash-alt"></i>
                        </button>

                        <!-- Add New Scholarship Button -->
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addSchoolYearModal">
                            Add New Scholarship
                        </button>
                    </div>
                </div>

                {{-- Trashed Scholarships Modal --}}
                <div class="modal fade" id="trashedModal" tabindex="-1" aria-labelledby="trashedModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="trashedModalLabel">Trashed Scholarships</h5>
                                <button type="button" class="close text-white"
                                    data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                @if ($trashedScholarships->isEmpty())
                                    <p>No trashed scholarships found.</p>
                                @else
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Scholarship Name</th>
                                                <th>Discount/Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trashedScholarships as $scholarship)
                                                <tr class="table-danger">
                                                    <td>{{ $scholarship->name }}</td>
                                                    <td>{{ $scholarship->discount ?? 'N/A' }}</td>
                                                    <td class="text-center">
                                                        <span class="badge badge-secondary">Trashed</span>
                                                    </td>
                                                    <td>
                                                        <!-- Restore Button -->
                                                        <form action="{{ route('scholarships.restore', $scholarship->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-sm btn-success">Restore</button>
                                                        </form>
                                                        <!-- Permanently Delete Button -->
                                                        <form
                                                            action="{{ route('scholarships.forceDelete', $scholarship->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger">Delete</button>
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

                {{-- Scholarships Table --}}
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="scholarshipsTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Scholarship Name</th>
                                                <th>Discount/Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($scholarships as $scholarship)
                                                <tr>
                                                    <td>{{ $scholarship->name }}</td>
                                                    <td>{{ $scholarship->discount ?? 'N/A' }}</td>
                                                    <td class="text-center">
                                                        @if ($scholarship->status == 'active')
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">
                                                            <!-- Toggle Active/Inactive Status Button -->
                                                            <form
                                                                action="{{ route('scholarships.toggleStatus', $scholarship->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button
                                                                    class="btn btn-sm {{ $scholarship->status == 'active' ? 'btn-danger' : 'btn-success' }}"
                                                                    title="{{ $scholarship->status == 'active' ? 'Set as Inactive' : 'Set as Active' }}"
                                                                    style="width: 30px; height: 30px; padding: 0;">
                                                                    <i class="fas {{ $scholarship->status == 'active' ? 'fa-times' : 'fa-check' }}"
                                                                        style="font-size: 14px;"></i>
                                                                </button>
                                                            </form>


                                                            <!-- Soft Delete Button -->
                                                            <form
                                                                action="{{ route('scholarships.destroy', $scholarship->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    class="btn btn-sm btn-danger d-flex justify-content-center align-items-center"
                                                                    title="Delete"
                                                                    style="width: 30px; height: 30px; padding: 0;">
                                                                    <i class="fas fa-trash" style="font-size: 14px;"></i>
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

            {{-- Add New Scholarship Modal --}}
            <div class="modal fade" id="addSchoolYearModal" tabindex="-1" role="dialog"
                aria-labelledby="addSchoolYearModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="addSchoolYearModalLabel">Add New Scholarship</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('scholarships.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Scholarship Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="discount">Discount/Amount</label>
                                    <input type="number" class="form-control" id="discount" name="discount" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Scholarship</button>
                            </form>
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
            $('#scholarshipsTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>



@endsection
