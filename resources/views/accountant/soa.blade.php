@extends('layouts.main')

@section('tab_title', 'SOA')
@section('accountant_sidebar')
    @include('accountant.accountant_sidebar')
@endsection

@section('content')

    <div id="content-wrapper" class="d-flex flex-column">


        <div id="content">

            @include('layouts.topbar')


            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Student Account Summary</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
                </div>

                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="admissionsTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Student No.</th>
                                                <th>Full Name</th>
                                                <th>Course</th>
                                                <th>Scholar</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1; // Initialize the counter for No.
                                            @endphp
                                            @foreach ($admissions as $admission)
                                                @php
                                                    // Fetch scholarship details
                                                    $scholarship = $scholarships->firstWhere(
                                                        'id',
                                                        $admission->scholarship_id,
                                                    );
                                                    $scholarshipName = $scholarship
                                                        ? $scholarship->name
                                                        : 'No Scholarship';
                                                @endphp
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $admission->student_id }}</td>
                                                    <td>{{ $admission->first_name }} {{ $admission->middle_name }}
                                                        {{ $admission->last_name }}</td>
                                                    <td>{{ $admission->programCourseMapping->program->name ?? 'Unknown Course' }}
                                                    </td>
                                                    <td>{{ $scholarshipName }}</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm view-billing-btn"
                                                            title="View Billing"
                                                            data-student-id="{{ $admission->student_id }}"
                                                            data-bs-toggle="modal" data-bs-target="#paymentDetailsModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

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


                <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fs-5 fw-bold" id="paymentDetailsModalLabel">Student Account Summary
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-2">
                                <div class="table-responsive" style="max-height: 70vh;">
                                    <table class="table table-bordered table-hover align-middle mb-0">
                                        <thead class="sticky-top bg-light">
                                            <tr>
                                                <th rowspan="2" class="text-center align-middle bg-white">
                                                    <span class="d-block text-nowrap">School Year</span>
                                                </th>
                                                <th rowspan="2" class="text-center align-middle bg-white">
                                                    <span class="d-block text-nowrap">Semester</span>
                                                </th>
                                                <th rowspan="2" class="text-center align-middle bg-white">
                                                    <span class="d-block text-nowrap">No. of Units</span>
                                                </th>
                                                <th rowspan="2" class="text-center align-middle bg-white">
                                                    <span class="d-block text-nowrap">Tuition Fee</span>
                                                </th>
                                                <th rowspan="2" class="text-center align-middle bg-white">
                                                    <span class="d-block text-nowrap">Discount</span>
                                                </th>
                                                <th rowspan="2" class="text-center align-middle bg-white">
                                                    <span class="d-block text-nowrap">Total Tuition Fee</span>
                                                </th>
                                                <th colspan="5" class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Assessment of Fees</span>
                                                </th>
                                                <th rowspan="2" class="text-center align-middle bg-white">
                                                    <span class="d-block text-nowrap">4 Exams</span>
                                                </th>
                                                <th colspan="4" class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Less Payment</span>
                                                </th>
                                                <th rowspan="2" class="text-center align-middle bg-white">
                                                    <span class="d-block text-nowrap">Total Balance</span>
                                                </th>
                                                <th rowspan="2" class="text-center align-middle bg-white">
                                                    <span class="d-block text-nowrap">Remarks</span>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Misc</span>
                                                </th>
                                                <th class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Old Bal</span>
                                                </th>
                                                <th class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Total</span>
                                                </th>
                                                <th class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Initial Payment</span>
                                                </th>
                                                <th class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Balance</span>
                                                </th>
                                                <th class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Prelim</span>
                                                </th>
                                                <th class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Midterm</span>
                                                </th>
                                                <th class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Pre-final</span>
                                                </th>
                                                <th class="text-center bg-white">
                                                    <span class="d-block text-nowrap">Final</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="billingData" class="border-top-0">
                                            <!-- Billing rows will be dynamically inserted here by JS -->
                                            <tr>
                                                <td colspan="18" class="text-center py-4">Loading data...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Print Summary</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- End Pasge Content -->



            </div>
            <!-- End of Main Content -->

            @include('layouts.footer')

        </div>
        <!-- End of Content Wrapper -->
    @endsection
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- REQUIRED for DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#admissionsTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>

    <script>
        $(document).on('click', '.btn-primary', function() {
            // Fetch payment details dynamically, e.g., via AJAX
            let paymentId = $(this).data('payment-id');
            // Populate the modal content with details corresponding to paymentId
            $('#paymentDetailsModal').modal('show');
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('paymentDetailsModal');
            const billingTableBody = modal.querySelector('#billingData');

            modal.addEventListener('show.bs.modal', function(event) {
                // Button that triggered the modal
                const button = event.relatedTarget;
                const studentId = button.getAttribute('data-student-id');

                if (!studentId) return;

                // Clear old data
                billingTableBody.innerHTML =
                    '<tr><td colspan="18" class="text-center">Loading...</td></tr>';

                // Fetch billing data from your controller via AJAX
                fetch(`/billing/${studentId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            billingTableBody.innerHTML =
                                `<tr><td colspan="18" class="text-center text-danger">${data.error}</td></tr>`;
                            return;
                        }

                        if (data.length === 0) {
                            billingTableBody.innerHTML =
                                '<tr><td colspan="18" class="text-center">No billing data found.</td></tr>';
                            return;
                        }

                        // Build rows
                        let rows = '';
                        data.forEach(billing => {
                            const balanceDue = parseFloat(billing.balance_due).toFixed(2);
                            const fourExams = (balanceDue / 4).toFixed(2);

                            rows += `
        <tr>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${billing.school_year}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${billing.semester}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${billing.no_of_units ?? '-'}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.tuition_fee).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.discount || 0).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.tuition_fee_discount).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.misc_fee).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.old_accounts || 0).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.total_assessment).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.initial_payment).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${balanceDue}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${fourExams}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.prelims_due || 0).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.midterms_due || 0).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.prefinals_due || 0).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${parseFloat(billing.finals_due || 0).toFixed(2)}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${balanceDue}</td>
            <td class="text-xs text-black text-center bg-white px-6 py-4">${billing.remarks || ''}</td>
        </tr>
    `;
                        });


                        billingTableBody.innerHTML = rows;
                    })
                    .catch(() => {
                        billingTableBody.innerHTML =
                            '<tr><td colspan="18" class="text-center text-danger">Failed to load billing data.</td></tr>';
                    });
            });
        });
    </script>
