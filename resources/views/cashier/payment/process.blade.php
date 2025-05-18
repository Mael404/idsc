@extends('layouts.main')

@section('tab_title', 'Payments')
@section('vpacademic_sidebar')
    @include('vp_academic.vpacademic_sidebar')
@endsection

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
                    <button class="btn btn-primary" data-toggle="modal" data-target="#newPaymentModal">
                        New Payment
                    </button>
                </div>

                @php
                    use Illuminate\Support\Str;
                @endphp <!-- Display Billings in Table -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="billingsTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Student Name</th>
                                                <th>Semester - School Year</th>
                                                <th>Balance Due</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($billings as $billing)
                                                <tr>
                                                    <td>{{ $billing->student_id }}</td>
                                                    <td>{{ optional($billing->student)->full_name ? Str::title(optional($billing->student)->full_name) : 'No student found' }}
                                                    </td>
                                                    <td>{{ $billing->semester . ' - ' . $billing->school_year }}</td>
                                                    <td>{{ number_format($billing->balance_due, 2) }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">
                                                            <!-- Edit Button -->
                                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                                data-target="#editBillingModal{{ $billing->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>


                                                        </div>

                                                        <!-- Edit Billing Modal -->
                                                        <div class="modal fade" id="editBillingModal{{ $billing->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="editBillingModalLabel{{ $billing->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <form method="POST"
                                                                    action="{{ route('billings.update', $billing->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary text-white">
                                                                            <h5 class="modal-title">View Billing</h5>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="form-group">
                                                                                <label>Student ID</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="student_id"
                                                                                    value="{{ $billing->student_id }}"
                                                                                    readonly>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Student Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="student_name"
                                                                                    value="{{ optional($billing->student)->full_name ? Str::title(optional($billing->student)->full_name) : 'No student found' }}"
                                                                                    readonly>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Tuition Fee</label>
                                                                                <input type="number" class="form-control"
                                                                                    name="tuition_fee"
                                                                                    value="{{ $billing->tuition_fee }}"
                                                                                    readonly>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Discount</label>
                                                                                <input type="number" class="form-control"
                                                                                    name="discount"
                                                                                    value="{{ $billing->discount }}"
                                                                                    readonly>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Miscellaneous Fee</label>
                                                                                <input type="number" class="form-control"
                                                                                    name="misc_fee"
                                                                                    value="{{ $billing->misc_fee }}"
                                                                                    readonly>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Balance Due</label>
                                                                                <input type="number" class="form-control"
                                                                                    name="balance_due"
                                                                                    value="{{ $billing->balance_due }}"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>


                                                        <!-- Delete Confirmation Modal -->
                                                        <div class="modal fade" id="deleteModal{{ $billing->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="deleteModalLabel{{ $billing->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content border-danger">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title">Delete Billing</h5>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete this billing record
                                                                        for
                                                                        <strong>{{ optional($billing->student)->full_name ? Str::title(optional($billing->student)->full_name) : 'Unknown Student' }}</strong>?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form method="POST"
                                                                            action="{{ route('billings.destroy', $billing->id) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Cancel</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">Yes, Delete</button>
                                                                        </form>
                                                                    </div>
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
                <!-- New Payment Modal -->
                <div class="modal fade" id="newPaymentModal" tabindex="-1" aria-labelledby="newPaymentModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('payment.store') }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="newPaymentModalLabel">New Payment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group position-relative">
                                        <label for="searchStudent">Search Student</label>
                                        <input type="text" class="form-control" id="searchStudent" name="student_id"
                                            placeholder="Search by Student ID">
                                        <ul id="searchSuggestions" class="list-group position-absolute w-100 mt-2"
                                            style="z-index: 1050;"></ul>
                                    </div>
                                    <div class="form-group">
                                        <label for="studentName">Student Name</label>
                                        <input type="text" class="form-control" id="studentName" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="balanceDue">Balance Due</label>
                                        <input type="number" class="form-control" id="balanceDue" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" class="form-control" id="amount" name="payment_amount"
                                            step="0.01" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="orNumber">OR Number</label>
                                        <input type="text"
                                            class="form-control @error('or_number') is-invalid @enderror" id="orNumber"
                                            name="or_number" placeholder="Enter OR Number" required>
                                        @error('or_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="printReceipt()">Print</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="printableReceipt" style="display: none;">
                    <div
                        style="width: 100%; font-family: Arial, sans-serif; text-align: left; position: relative; font-size: larger; line-height: 1.8;">
                        <!-- Date in Words -->
                        <div style="text-align: left; font-size: 20px; margin-bottom: 50px;">
                            <span id="receiptDate"></span>
                        </div>

                        <!-- Name -->
                        <div style="text-align: left; font-size: 22px; font-weight: bold; margin-bottom: 50px;">
                            <span id="receiptStudentName"></span>
                        </div>

                        <!-- Amount in Words -->
                        <div style="text-align: left; font-size: 20px; margin-bottom: 50px;">
                            <span id="receiptAmountWords"></span>
                        </div>

                        <!-- Amount in Numbers -->
                        <div style="text-align: left; font-size: 20px; margin-bottom: 50px;">
                            ₱<span id="receiptAmount"></span>
                        </div>

                        <!-- Remarks -->
                        <div style="text-align: left; font-size: 20px; margin-bottom: 50px;">
                            <span id="receiptRemarks"></span>
                        </div>
                    </div>
                </div>

                <script>
                    function printReceipt() {
                        // Convert date to words
                        function getDateInWords(date) {
                            const months = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            const day = date.getDate();
                            const month = months[date.getMonth()];
                            const year = date.getFullYear();
                            return `${month} ${day}, ${year}`;
                        }

                        // Populate the receipt fields
                        const currentDate = new Date();
                        document.getElementById('receiptDate').innerText = getDateInWords(currentDate);

                        document.getElementById('receiptStudentName').innerText = document.getElementById('studentName').value;

                        const amount = parseFloat(document.getElementById('amount').value);
                        document.getElementById('receiptAmount').innerText = amount.toFixed(2);
                        document.getElementById('receiptAmountWords').innerText = convertAmountToWords(amount);

                        document.getElementById('receiptRemarks').innerText = document.getElementById('remarks').value;

                        // Fetch the printable content
                        const printContent = document.getElementById('printableReceipt').innerHTML;

                        // Open a new window for the print dialog
                        const printWindow = window.open('', '', 'width=600,height=600');
                        printWindow.document.open();
                        printWindow.document.write(`
<html>
    <head>
        <title>Print Receipt</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; font-size: larger; line-height: 1.8; }
            div { margin-bottom: 50px; }
        </style>
    </head>
    <body>
        ${printContent}
    </body>
</html>
`);
                        printWindow.document.close();
                        printWindow.print();
                    }

                    function convertAmountToWords(amount) {
                        const ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
                        const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
                        const teens = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
                            "Nineteen"
                        ];

                        if (amount === 0) return "Zero";

                        let words = "";
                        let num = Math.floor(amount);
                        let cents = Math.round((amount - num) * 100);

                        if (num >= 1000) {
                            words += ones[Math.floor(num / 1000)] + " Thousand ";
                            num %= 1000;
                        }

                        if (num >= 100) {
                            words += ones[Math.floor(num / 100)] + " Hundred ";
                            num %= 100;
                        }

                        if (num >= 20) {
                            words += tens[Math.floor(num / 10)] + " ";
                            num %= 10;
                        } else if (num >= 10) {
                            words += teens[num - 10] + " ";
                            num = 0;
                        }

                        if (num > 0) {
                            words += ones[num] + " ";
                        }

                        if (cents > 0) {
                            words += "and " + cents + " Centavos";
                        }

                        return words.trim();
                    }
                </script>



                <!-- Optional CSS -->
                <style>
                    #printableReceipt {
                        font-size: 24px !important;
                        /* Base font size much larger */
                    }

                    #printableReceipt h3 {
                        text-align: center;
                        margin-bottom: 30px;
                        font-size: 28px !important;
                        font-weight: bold;
                    }

                    #printableReceipt p {
                        margin: 10px 0;
                        font-size: 24px !important;
                        line-height: 1.5;
                    }

                    #printableReceipt div {
                        font-size: 24px !important;
                        margin-bottom: 25px !important;
                    }

                    #receiptDate,
                    #receiptStudentName,
                    #receiptSchoolYear,
                    #receiptRemarks,
                    #receiptAmountWords,
                    #receiptAmount {
                        font-size: 24px !important;
                    }

                    #receiptStudentName {
                        font-size: 26px !important;
                        font-weight: bold;
                    }
                </style>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchInput = document.getElementById('searchStudent');
                        const suggestionsList = document.getElementById('searchSuggestions');
                        const studentNameInput = document.getElementById('studentName');
                        const balanceDueInput = document.getElementById('balanceDue');

                        searchInput.addEventListener('input', function() {
                            const query = this.value;

                            if (query.length >= 2) {
                                fetch(`/api/search-students?query=${query}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        suggestionsList.innerHTML = '';
                                        data.forEach(student => {
                                            const suggestion = document.createElement('li');
                                            suggestion.className = 'list-group-item list-group-item-action';
                                            suggestion.textContent =
                                                `${student.student_id} - ${student.full_name}`;
                                            suggestion.addEventListener('click', function() {
                                                studentNameInput.value = student.full_name;
                                                balanceDueInput.value = student.balance_due;
                                                searchInput.value = student.student_id;
                                                suggestionsList.innerHTML = '';
                                            });
                                            suggestionsList.appendChild(suggestion);
                                        });
                                    });
                            } else {
                                suggestionsList.innerHTML = '';
                            }
                        });

                        // Hide suggestions when clicking outside
                        document.addEventListener('click', function(event) {
                            if (!suggestionsList.contains(event.target) && event.target !== searchInput) {
                                suggestionsList.innerHTML = '';
                            }
                        });
                    });
                </script>


            </div>

        </div>
        <!-- End Page Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#billingsTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>


@endsection
