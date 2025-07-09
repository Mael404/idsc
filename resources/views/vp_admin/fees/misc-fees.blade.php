@extends('layouts.main')

@section('tab_title', 'Manage Misc. Fees')
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


                        <button class="btn btn-primary" data-toggle="modal" data-target="#addSchoolYearModal">
                            Add New School Year
                        </button>
                    </div>
                </div>


                <div class="row justify-content-center mt-3">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="miscFees">
                                        <thead>
                                            <tr>
                                                <th>Program</th>
                                                <th>Year Level</th>
                                                <th>Semester</th>
                                                <th>Effective SY</th>
                                                <th>Courses</th>
                                                
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($groupedMappings as $group)
                                                <tr>
                                                    <td>{{ $group['program_name'] }}</td>
                                                    <td>{{ $group['year_level'] }}</td>
                                                    <td>{{ $group['semester'] }}</td>
                                                    <td>{{ $group['effective_sy'] }}</td>

                                                    @php
                                                        $courseList = explode(',', $group['courses']);
                                                    @endphp

                                                    <td>
                                                        <ul class="mb-0 ps-3">
                                                            @foreach ($courseList as $course)
                                                                <li>{{ trim($course) }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>

                                                    <td class="text-center">
                                                        <!-- Modal Trigger Button -->
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#miscFeesModal-{{ $group['mapping_ids'][0] }}">
                                                            Manage Misc Fees
                                                        </button>

                                                        <!-- Miscellaneous Fees Modal -->
                                                        <div class="modal fade"
                                                            id="miscFeesModal-{{ $group['mapping_ids'][0] }}" tabindex="-1"
                                                            aria-labelledby="miscFeesModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content rounded-3 shadow-sm">

                                                                    <div class="modal-header bg-primary text-white">
                                                                        <h5 class="modal-title" id="miscFeesModalLabel">
                                                                            Miscellaneous Fees</h5>
                                                                        <button type="button"
                                                                            class="btn-close btn-close-white"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <form
                                                                            id="miscFeeForm-{{ $group['mapping_ids'][0] }}"
                                                                            method="POST"
                                                                            action="{{ route('misc-fees.store-bulk') }}">
                                                                            @csrf
                                                                            <input type="hidden"
                                                                                name="program_course_mapping_id"
                                                                                value="{{ $group['mapping_ids'][0] }}">

                                                                            <!-- Asdd New Fee -->
                                                                            <div class="mb-4">
                                                                                <h6>Add New Fee</h6>
                                                                                <div class="row g-3 align-items-end">
                                                                                    <div class="col-md-5">
                                                                                        <label
                                                                                            for="feeName-{{ $group['mapping_ids'][0] }}"
                                                                                            class="form-label">Fee
                                                                                            Name</label>
                                                                                        <input type="text"
                                                                                            id="feeName-{{ $group['mapping_ids'][0] }}"
                                                                                            class="form-control"
                                                                                            placeholder="e.g. Lab Fee">
                                                                                    </div>
                                                                                    <div class="col-md-5">
                                                                                        <label
                                                                                            for="feeAmount-{{ $group['mapping_ids'][0] }}"
                                                                                            class="form-label">Amount</label>
                                                                                        <div class="input-group">
                                                                                            <span
                                                                                                class="input-group-text">₱</span>
                                                                                            <input type="number"
                                                                                                id="feeAmount-{{ $group['mapping_ids'][0] }}"
                                                                                                class="form-control"
                                                                                                placeholder="0.00"
                                                                                                step="0.01"
                                                                                                min="0">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <button type="button"
                                                                                            class="btn btn-success w-100"
                                                                                            onclick="addFee('{{ $group['mapping_ids'][0] }}')">Add</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Pending Submission -->
                                                                            <div class="mb-4">
                                                                                <h6>Pending Submission</h6>
                                                                                <div class="table-responsive">
                                                                                    <table
                                                                                        class="table table-sm table-bordered"
                                                                                        id="feeList-{{ $group['mapping_ids'][0] }}">
                                                                                        <thead class="table-light">
                                                                                            <tr>
                                                                                                <th>Fee Name</th>
                                                                                                <th>Amount</th>
                                                                                                <th style="width: 80px;">
                                                                                                    Action</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody></tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <input type="hidden" name="fees_json"
                                                                                    id="feesJson-{{ $group['mapping_ids'][0] }}">
                                                                            </div>

                                                                            <!-- Submit Button -->
                                                                            <div class="d-grid mb-4">
                                                                                <button type="submit"
                                                                                    class="btn btn-primary">Save All
                                                                                    Fees</button>
                                                                            </div>
                                                                        </form>

                                                                        <!-- Current Fees -->
                                                                        <div>
                                                                            <h6>Current Fees</h6>
                                                                            @php
                                                                                $fees = App\Models\MiscFee::whereIn(
                                                                                    'program_course_mapping_id',
                                                                                    $group['mapping_ids'],
                                                                                )->get();
                                                                                $total = $fees->sum('amount');
                                                                            @endphp

                                                                            @if ($fees->count())
                                                                                <div class="table-responsive">
                                                                                    <table
                                                                                        class="table table-sm table-striped align-middle">
                                                                                        <thead class="table-light">
                                                                                            <tr>
                                                                                                <th>Fee Name</th>
                                                                                                <th>Amount</th>
                                                                                                <th style="width: 80px;">
                                                                                                    Action</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            @foreach ($fees as $fee)
                                                                                                <tr>
                                                                                                    <td>{{ $fee->name }}
                                                                                                    </td>
                                                                                                    <td>₱{{ number_format($fee->amount, 2) }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <form
                                                                                                            action="{{ route('misc-fees.destroy', $fee->id) }}"
                                                                                                            method="POST"
                                                                                                            class="d-inline">
                                                                                                            @csrf
                                                                                                            @method('DELETE')
                                                                                                            <button
                                                                                                                type="submit"
                                                                                                                class="btn btn-sm btn-outline-danger"
                                                                                                                title="Delete">
                                                                                                                <i
                                                                                                                    class="fas fa-trash-alt"></i>
                                                                                                            </button>
                                                                                                        </form>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                            <tr
                                                                                                class="fw-bold table-active">
                                                                                                <td>Total</td>
                                                                                                <td>₱{{ number_format($total, 2) }}
                                                                                                </td>
                                                                                                <td></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            @else
                                                                                <div class="alert alert-info">No
                                                                                    miscellaneous fees have been added yet.
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>


                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    @if ($groupedMappings->isEmpty())
                                        <p class="text-muted text-center mt-4">No program-course mappings found.</p>
                                    @endif
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
            $('#miscFees').DataTable({
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
        const feeLists = {};

        function addFee(mappingId) {
            const nameInput = document.getElementById(`feeName-${mappingId}`);
            const amountInput = document.getElementById(`feeAmount-${mappingId}`);
            const name = nameInput.value.trim();
            const amount = parseFloat(amountInput.value);

            if (!name || isNaN(amount)) {
                alert("Please enter a valid fee name and amount.");
                return;
            }

            // Initialize if not already
            if (!feeLists[mappingId]) feeLists[mappingId] = [];

            const fee = {
                name,
                amount
            };
            feeLists[mappingId].push(fee);

            // Clear input fields
            nameInput.value = '';
            amountInput.value = '';

            renderFeeList(mappingId);
        }

        function removeFee(mappingId, index) {
            feeLists[mappingId].splice(index, 1);
            renderFeeList(mappingId);
        }

        function renderFeeList(mappingId) {
            const tbody = document.querySelector(`#feeList-${mappingId} tbody`);
            tbody.innerHTML = '';

            feeLists[mappingId].forEach((fee, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${fee.name}</td>
                <td>₱${fee.amount.toFixed(2)}</td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeFee('${mappingId}', ${index})">Remove</button></td>
            `;
                tbody.appendChild(row);
            });

            // Update hidden input with JSON
            document.getElementById(`feesJson-${mappingId}`).value = JSON.stringify(feeLists[mappingId]);
        }
    </script>


@endsection
