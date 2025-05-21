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
                                                        <button class="btn btn-primary btn-sm" title="View"
                                                            data-toggle="modal" data-target="#admissionDetailsModal">
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
                    <div class="modal-dialog modal-fullscreen-lg-down modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content" style="min-width: 1200px;"> <!-- Adjust width as needed -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentDetailsModalLabel">Student Account Summary</h5>

                            </div>
                            <div class="modal-body p-0"> <!-- Remove padding to maximize space -->
                                <div class="table-responsive"> <!-- Add responsive wrapper -->
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr class="border-b border-b-slate-100 last:border-b-0">
                                                <th rowspan="2"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    School Year</th>
                                                <th rowspan="2"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Semester</th>
                                                <th rowspan="2"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    No. of Units</th>
                                                <th rowspan="2"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Tuition Fee</th>
                                                <th rowspan="2"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Discount</th>
                                                <th rowspan="2"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Total Tuition Fee</th>
                                                <th colspan="5"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Assessment of Fees</th>
                                                <th rowspan="2"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    4 Exams</th>
                                                <th colspan="4"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Less Payment</th>
                                                <th rowspan="2"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Total Balance</th>
                                                <th rowspan="2"
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Remarks</th>
                                            </tr>
                                            <tr class="border-b border-b-slate-100 last:border-b-0">
                                                <th
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Misc</th>
                                                <th
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Old Bal</th>
                                                <th
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Total</th>
                                                <th
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Initial Payment</th>
                                                <th
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Balance</th>
                                                <th
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Prelim</th>
                                                <th
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Midterm</th>
                                                <th
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Pre-final</th>
                                                <th
                                                    class="text-[12px] text-muted text-center font-semibold uppercase bg-white border border-slate-200 px-6 py-4">
                                                    Final</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b border-b-slate-100 last:border-b-0">
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">2024-2025</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">Second
                                                    Semester
                                                </td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">20</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">11,980.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">0.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">11,980.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">2,000.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">0.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">13,980.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">0.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">13,980.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">3,495.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">0.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">0.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">0.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">0.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4">13,980.00</td>
                                                <td class="text-xs text-black text-center bg-white px-6 py-4"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End Page Content -->




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
