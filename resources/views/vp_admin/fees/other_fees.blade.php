@extends('layouts.main')

@section('tab_title', 'Manage Semester')
@section('vpadmin_sidebar')
    @include('vp_admin.vpadmin_sidebar')
@endsection

@section('content')
    <!-- Content Wrapper -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.topbar')

            <div class="container-fluid">
                @include('layouts.success-message')

                <!-- Page Header -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Other Fees</h1>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#trashedModal">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addFeeModal">
                            Add New Fee
                        </button>
                    </div>
                </div>

                <!-- Trashed Fees Modal -->
                <div class="modal fade" id="trashedModal" tabindex="-1" aria-labelledby="trashedModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Trashed Fees</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if ($trashedFees->isEmpty())
                                    <p>No trashed fees found.</p>
                                @else
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Fee Name</th>
                                                <th>Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($trashedFees as $fee)
                                                <tr class="table-danger">
                                                    <td>{{ $fee->name }}</td>
                                                    <td>{{ number_format($fee->amount, 2) }}</td>
                                                    <td>
                                                        <form action="{{ route('fees.restore', $fee->id) }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button class="btn btn-sm btn-success">Restore</button>
                                                        </form>
                                                        <form action="{{ route('fees.forceDelete', $fee->id) }}"
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

                <!-- Fees Table -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="feesTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Fee Name</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fees as $fee)
                                                <tr>
                                                    <td>{{ $fee->name }}</td>
                                                    <td>{{ number_format($fee->amount, 2) }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-{{ $fee->status === 'active' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($fee->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">
                                                            <!-- Edit Button -->
                                                            <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                                data-target="#editFeeModal{{ $fee->id }}"
                                                                title="Edit"
                                                                style="width: 30px; height: 30px; padding: 0;">
                                                                <i class="fas fa-edit" style="font-size: 14px;"></i>
                                                            </button>

                                                            <!-- Toggle Status -->
                                                            <form action="{{ route('fees.toggleStatus', $fee->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button
                                                                    class="btn btn-sm {{ $fee->status === 'active' ? 'btn-danger' : 'btn-success' }}"
                                                                    title="{{ $fee->status === 'active' ? 'Set as Inactive' : 'Set as Active' }}"
                                                                    style="width: 30px; height: 30px; padding: 0;">
                                                                    <i class="fas {{ $fee->status === 'active' ? 'fa-times' : 'fa-check' }}"
                                                                        style="font-size: 14px;"></i>
                                                                </button>
                                                            </form>

                                                            <!-- Soft Delete -->
                                                            <form action="{{ route('fees.destroy', $fee->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-danger" title="Delete"
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
                @foreach ($fees as $fee)
                    <!-- Edit Fee Modal -->
                    <div class="modal fade" id="editFeeModal{{ $fee->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editFeeModalLabel{{ $fee->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-warning text-white">
                                    <h5 class="modal-title" id="editFeeModalLabel{{ $fee->id }}">Edit Fee</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('fees.update', $fee->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label for="name{{ $fee->id }}">Fee Name</label>
                                            <input type="text" class="form-control" id="name{{ $fee->id }}"
                                                name="name" value="{{ $fee->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="amount{{ $fee->id }}">Amount</label>
                                            <input type="number" class="form-control" id="amount{{ $fee->id }}"
                                                name="amount" value="{{ $fee->amount }}" step="0.01"
                                                min="0" required>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-warning">Update Fee</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Add Fee Modal -->
                <div class="modal fade" id="addFeeModal" tabindex="-1" role="dialog"
                    aria-labelledby="addFeeModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="addFeeModalLabel">Add New Fee</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('fees.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Fee Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            step="0.01" min="0" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Fee</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.footer')
        </div>
    </div>

    <!-- End of Content Wrapper -->
@endsection

@section('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#feesTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>



@endsection
