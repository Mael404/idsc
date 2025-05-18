@extends('layouts.main')

@section('tab_title', 'Reports')
@section('content')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            @include('layouts.topbar')

            <div class="container-fluid">
                @include('layouts.success-message')


                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Student Billings</h1>

                    <!-- New Payment Button -->
                
                </div>
                <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
     
        <!-- Print Button -->
        <button class="btn btn-primary" onclick="printTable()">
            Print
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="paymentsTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th>Remarks</th>
                            <th>OR Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->student_id }}</td>
                                <td>{{ $payment->student->full_name ?? 'No student found' }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_date }}</td>
                                <td>{{ $payment->remarks }}</td>
                                <td>{{ $payment->or_number }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function printTable() {
        // Open a new window for printing
        const printWindow = window.open('', '_blank');

        // Fetch the table HTML and add styling for print
        const tableHTML = document.querySelector('#paymentsTable').outerHTML;
        const styles = `
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    font-size: 18px;
                    text-align: left;
                }
                table th, table td {
                    border: 1px solid #dddddd;
                    padding: 8px;
                }
                table th {
                    background-color: #f2f2f2;
                }
            </style>
        `;

        // Populate the print window's content
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Table</title>
                    ${styles}
                </head>
                <body>
                    <h1>Payment Reports</h1>
                    ${tableHTML}
                </body>
            </html>
        `);

        // Close the document to trigger rendering
        printWindow.document.close();

        // Print and close the window
        printWindow.print();
        printWindow.close();
    }
</script>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#paymentsTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>
@endsection
