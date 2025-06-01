@extends('layouts.main')

@section('tab_title', 'Transactions')
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
                    <h1 class="h3 mb-0 text-gray-800">Pending Voids</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i> Download Report</a>
                </div>
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <button id="filter-today" class="btn btn-primary btn-sm">Today</button>
                                <button id="filter-week" class="btn btn-primary btn-sm">This Week</button>
                                <button id="filter-month" class="btn btn-primary btn-sm">This Month</button>
                                <button id="filter-year" class="btn btn-primary btn-sm">This Year</button>
                                <button id="filter-clear" class="btn btn-secondary btn-sm">Clear Filter</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="paymentsTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>OR No.</th>
                                                <th>Date</th>
                                                <th>Payor</th>
                                                <th>Course</th>
                                                <th>Semester</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                   <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->or_number }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                                    </td>
                                                    <td>{{ $payment->student->full_name ?? $payment->student_id }}</td>
                                                    <td>{{ $payment->student->programCourseMapping->program->name ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $payment->semester }}</td>
                                                    <td>₱{{ number_format($payment->amount, 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    </td>
                                                    <td>
                                                        <form method="POST"
                                                            action="{{ route('accounting.voids.approve', $payment->id) }}"
                                                            class="d-inline">
                                                            @csrf
                                                            <button class="btn btn-success btn-sm">Approve</button>
                                                        </form>
                                                        <form method="POST"
                                                            action="{{ route('accounting.voids.reject', $payment->id) }}"
                                                            class="d-inline">
                                                            @csrf
                                                            <button class="btn btn-danger btn-sm">Reject</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                              
                                            @endforelse
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
    </div>
    <!-- End of Content Wrapper -->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- REQUIRED for DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        const table = $('#paymentsTable').DataTable({
            responsive: true,
            pageLength: 10,
        });

        // Custom filter for date range
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const dateColumnIndex = 1; // Assuming date is in the second column (zero-indexed)
            const dateStr = data[dateColumnIndex];
            const date = new Date(dateStr);

            // Check if startDate and endDate are defined
            if (window.startDate && window.endDate) {
                return date >= window.startDate && date <= window.endDate;
            }
            return true; // No filtering if dates are not set
        });

        // Function to set date range and redraw table
        function setFilter(startDate, endDate) {
            window.startDate = startDate;
            window.endDate = endDate;
            table.draw();
        }

        // Event handlers for filter buttons
        $('#filter-today').on('click', function() {
            const today = new Date();
            const start = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            const end = new Date(start.getFullYear(), start.getMonth(), start.getDate() + 1);
            setFilter(start, end);
        });

        $('#filter-week').on('click', function() {
            const today = new Date();
            const start = new Date(today.getFullYear(), today.getMonth(), today.getDate() - today
                .getDay());
            const end = new Date(start.getFullYear(), start.getMonth(), start.getDate() + 7);
            setFilter(start, end);
        });

        $('#filter-month').on('click', function() {
            const today = new Date();
            const start = new Date(today.getFullYear(), today.getMonth(), 1);
            const end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            setFilter(start, end);
        });

        $('#filter-year').on('click', function() {
            const today = new Date();
            const start = new Date(today.getFullYear(), 0, 1);
            const end = new Date(today.getFullYear(), 11, 31);
            setFilter(start, end);
        });

        $('#filter-clear').on('click', function() {
            window.startDate = null;
            window.endDate = null;
            table.draw();
        });
    });
</script>
