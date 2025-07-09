@extends('layouts.main')

@section('tab_title', 'Reports')
@section('content')
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            @include('layouts.topbar')

            <div class="container-fluid">
                @include('layouts.success-message')

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Payment History</h1>

                    <button class="btn btn-primary" onclick="printTable()">
                        <i class="fas fa-print mr-1"></i> Print
                    </button>
                </div>

                <!-- Filter and Total -->
                <div class="row justify-content-between mt-4 mb-2">
                    <div class="col-md-6">
                        <button class="btn btn-primary filter-btn" data-filter="today">Today</button>
                        <button class="btn btn-secondary filter-btn" data-filter="week">This Week</button>
                        <button class="btn btn-success filter-btn" data-filter="month">This Month</button>
                    </div>
                    <div class="col-md-6 text-end">
                        <h5>Total Amount: ₱<span id="totalAmount">0.00</span></h5>
                    </div>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->student_id }}</td>
                                            <td>{{ $payment->student->full_name ?? 'No student found' }}</td>
                                            <td class="amount">{{ number_format($payment->amount, 2, '.', '') }}</td>
                                            <td class="payment-date">{{ $payment->payment_date }}</td>
                                            <td>{{ $payment->remarks }}</td>
                                            <td>{{ $payment->or_number }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm void-payment"
                                                    data-payment-id="{{ $payment->id }}"
                                                    data-student-id="{{ $payment->student_id }}"
                                                    data-amount="{{ $payment->amount }}"
                                                    data-semester="{{ $payment->semester }}"
                                                    data-school-year="{{ $payment->school_year }}">
                                                    Void
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Modal -->
                <div class="modal fade" id="voidConfirmationModal" tabindex="-1" role="dialog"
                    aria-labelledby="voidConfirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Void</h5>
                            
                            </div>
                            <div class="modal-body">
                                Are you sure you want to void this transaction? This action is not undoable.
                            </div>
                            <div class="modal-footer">
                             
                                <button type="button" class="btn btn-danger" id="confirmVoid">Yes, Void Payment</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function printTable() {
                        const table = document.querySelector('#paymentsTable').cloneNode(true);

                        // Remove the last column (Action) from the header
                        const headerRow = table.querySelector('thead tr');
                        if (headerRow) {
                            headerRow.removeChild(headerRow.lastElementChild);
                        }

                        // Remove the last column from each body row
                        const bodyRows = table.querySelectorAll('tbody tr');
                        bodyRows.forEach(row => {
                            row.removeChild(row.lastElementChild);
                        });

                        const printWindow = window.open('', '_blank');
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

                        printWindow.document.write(`
        <html>
            <head>
                <title>Print Table</title>
                ${styles}
            </head>
            <body>
                <h1>Payment Reports</h1>
                ${table.outerHTML}
            </body>
        </html>
    `);

                        printWindow.document.close();
                        printWindow.print();
                        printWindow.close();
                    }
                </script>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <!-- Include DataTables + Buttons + Moment -->
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.1/b-html5-2.4.1/b-print-2.4.1/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#paymentsTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'csv',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5] // Exclude last column
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    }
                ],

                pageLength: 10
            });

            function updateTotal() {
                let total = 0;
                table.rows({
                    search: 'applied'
                }).every(function() {
                    const amount = parseFloat($(this.node()).find('.amount').text());
                    if (!isNaN(amount)) total += amount;
                });
                $('#totalAmount').text(total.toFixed(2));
            }

            updateTotal();
            table.on('draw', updateTotal);

            $('.filter-btn').on('click', function() {
                const filter = $(this).data('filter');
                const today = moment();

                table.rows().every(function() {
                    const row = $(this.node());
                    const dateStr = row.find('.payment-date').text().trim();
                    const date = moment(dateStr);
                    let show = false;

                    if (filter === 'today') {
                        show = date.isSame(today, 'day');
                    } else if (filter === 'week') {
                        show = date.isSame(today, 'week');
                    } else if (filter === 'month') {
                        show = date.isSame(today, 'month');
                    }

                    row.toggle(show);
                });

                table.draw();
            });

            let currentPaymentData = null;

            $(document).on('click', '.void-payment', function() {
                currentPaymentData = {
                    paymentId: $(this).data('payment-id'),
                    studentId: $(this).data('student-id'),
                    amount: $(this).data('amount'),
                    semester: $(this).data('semester'),
                    schoolYear: $(this).data('school-year')
                };

                $('#voidConfirmationModal').modal('show');
            });

            $('#confirmVoid').on('click', function() {
                if (!currentPaymentData) return;

                $.ajax({
                    url: '{{ route('payments.void') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        payment_id: currentPaymentData.paymentId,
                        student_id: currentPaymentData.studentId,
                        amount: currentPaymentData.amount,
                        semester: currentPaymentData.semester,
                        school_year: currentPaymentData.schoolYear
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Payment voided successfully');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while voiding the payment');
                        console.error(xhr.responseText);
                    }
                });

                $('#voidConfirmationModal').modal('hide');
            });
        });
    </script>
@endsection
