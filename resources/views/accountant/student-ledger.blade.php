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
                                                <th>Balance Due</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($admissions as $admission)
                                                @php
                                                    $scholarship = $scholarships->firstWhere(
                                                        'id',
                                                        $admission->scholarship_id,
                                                    );
                                                    $scholarshipName = $scholarship
                                                        ? $scholarship->name
                                                        : 'No Scholarship';
                                                    $balanceDue =
                                                        $billingData->get($admission->student_id)->balance_due ??
                                                        'No Data';
                                                @endphp
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $admission->student_id }}</td>
                                                    <td>{{ $admission->first_name }} {{ $admission->middle_name }}
                                                        {{ $admission->last_name }}</td>
                                                    <td>{{ $admission->programCourseMapping->program->name ?? 'Unknown Course' }}
                                                    </td>
                                                    <td>{{ $scholarshipName }}</td>
                                                    <td>{{ $balanceDue }}</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm view-billing-btn"
                                                            title="View Billing"
                                                            onclick="redirectToLedger(
            '{{ $admission->student_id }}',
            '{{ $admission->first_name }} {{ $admission->middle_name }} {{ $admission->last_name }}',
            '{{ $admission->programCourseMapping->program->name ?? 'Unknown Course' }}',
            '{{ $scholarshipName }}'
        )">
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
        function redirectToLedger(studentId) {
            // Redirect to the accountant.ledger route with the student ID as a query parameter
            window.location.href = `/accountant/ledger?student_id=${studentId}`;
        }
    </script>
 <script>
    function redirectToLedger(studentId, studentName, courseAndYear, scholarship) {
        const url = new URL(window.location.origin + '/accountant/ledger');
        url.searchParams.append('student_id', studentId);
        url.searchParams.append('student_name', studentName);
        url.searchParams.append('course_and_year', courseAndYear);
        url.searchParams.append('scholarship', scholarship);
        window.location.href = url.toString();
    }
</script>
