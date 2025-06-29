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
                    <h1 class="h3 mb-0 text-gray-800">Manual Pending Enrollees</h1>

                </div>

                @php
                    use Illuminate\Support\Str;
                @endphp <!-- Display Billings in Table -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="pendingTable">
                                        <thead>
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Student Name</th>
                                                <th>School Year</th>
                                                <th>Semester</th>
                                                <th>Status</th>
                                                <th>Initial Payment</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pendingEnrollments as $enrollment)
                                                @php
                                                    $middleInitial = $enrollment->middle_name
                                                        ? strtoupper(substr($enrollment->middle_name, 0, 1)) . '.'
                                                        : '';
                                                    $formattedName = "{$enrollment->last_name}, {$enrollment->first_name} {$middleInitial}";
                                                    $formattedSem =
                                                        strtoupper($enrollment->semester) .
                                                        " S.Y. {$enrollment->school_year}";
                                                @endphp
                                                <tr>
                                                    <td>{{ $enrollment->student_id }}</td>
                                                    <td>{{ $enrollment->full_name ?? 'N/A' }}</td>
                                                    <td>{{ $enrollment->school_year }}</td>
                                                    <td>{{ $enrollment->semester }}</td>
                                                    <td>{{ $enrollment->status }}</td>
                                                    <td>₱{{ number_format($enrollment->initial_payment, 2) }}</td>
                                                    <td class="text-center">
                                                        <!-- Summary Modal Trigger -->
                                                        <button type="button" class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#summaryModal{{ $enrollment->id }}">
                                                            <i class="fas fa-check"></i>
                                                        </button>

                                                        <!-- Summary Modal -->
                                                        <div class="modal fade" id="summaryModal{{ $enrollment->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="summaryModalLabel{{ $enrollment->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content shadow-lg rounded-3">
                                                                    <form method="POST"
                                                                        action="{{ route('manualcashier.confirm', $enrollment->id) }}"
                                                                        onsubmit="return handleSubmitAndPrint(event, {{ $enrollment->id }})">
                                                                        @csrf
                                                                        <div class="modal-header bg-primary text-white">
                                                                            <h5 class="modal-title fw-bold"
                                                                                id="summaryModalLabel{{ $enrollment->id }}">
                                                                                Enrollment Summary</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>

                                                                        <div class="modal-body px-4 py-3">
                                                                            <!-- Student Info Section -->
                                                                            <div class="border rounded p-3 mb-4">
                                                                                <h6 class="text-primary fw-semibold mb-3">
                                                                                    Student Information</h6>
                                                                                @php
                                                                                    $middleInitial = $enrollment->middle_name
                                                                                        ? strtoupper(
                                                                                                substr(
                                                                                                    $enrollment->middle_name,
                                                                                                    0,
                                                                                                    1,
                                                                                                ),
                                                                                            ) . '.'
                                                                                        : '';
                                                                                    $formattedName = "{$enrollment->last_name}, {$enrollment->first_name} {$middleInitial}";
                                                                                @endphp
                                                                                <div class="mb-2">
                                                                                    <strong>Name:</strong>
                                                                                    <span
                                                                                        id="studentName{{ $enrollment->id }}">{{ $formattedName }}</span>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <strong>Semester:</strong>
                                                                                    <span>{{ $formattedSem }}</span>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <strong>Initial Payment:</strong>
                                                                                    ₱<span
                                                                                        id="initialPayment{{ $enrollment->id }}">{{ number_format($enrollment->initial_payment, 2) }}</span>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Confirmation Input Section -->
                                                                            <div class="border rounded p-3 mb-4">
                                                                                <h6 class="text-success fw-semibold mb-3">
                                                                                    Confirmation Details</h6>
                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        for="or_number{{ $enrollment->id }}"
                                                                                        class="form-label">OR Number</label>
                                                                                    <input type="text" name="or_number"
                                                                                        class="form-control"
                                                                                        id="or_number{{ $enrollment->id }}"
                                                                                        placeholder="Enter OR Number"
                                                                                        required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        for="receipt_date{{ $enrollment->id }}"
                                                                                        class="form-label">Receipt
                                                                                        Date</label>
                                                                                    <input type="date"
                                                                                        name="receipt_date"
                                                                                        class="form-control"
                                                                                        id="receipt_date{{ $enrollment->id }}"
                                                                                        required>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        for="remarks{{ $enrollment->id }}"
                                                                                        class="form-label">Remarks</label>
                                                                                    <textarea name="remarks" class="form-control" id="remarks{{ $enrollment->id }}" rows="2"
                                                                                        placeholder="Enter any remarks"></textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer bg-light">
                                                                            <button type="submit"
                                                                                class="btn btn-success w-100 py-2 fw-bold">Submit
                                                                                & Confirm</button>
                                                                            <button type="button"
                                                                                class="btn btn-secondary w-100 py-2 mt-2"
                                                                                data-bs-dismiss="modal">Cancel</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <script>
                                                            function handleSubmitAndPrint(event, enrollmentId) {
                                                                event.preventDefault(); // Stop normal submission

                                                                const form = event.target;

                                                                // SAFELY get data using ID-based spans
                                                                const studentName = document.getElementById(`studentName${enrollmentId}`).textContent.trim();
                                                                const amountText = document.getElementById(`initialPayment${enrollmentId}`).textContent.trim();
                                                                const amount = parseFloat(amountText.replace(/[^\d.-]/g, ''));
                                                                const remarks = form.querySelector(`#remarks${enrollmentId}`).value;

                                                                // Fill in hidden fields
                                                                document.getElementById('studentName').value = studentName;
                                                                document.getElementById('amount').value = amount;
                                                                document.getElementById('remarks').value = remarks;

                                                                // Print the receipt
                                                               printReceipt(enrollmentId);

                                                                // Delay form submission slightly
                                                                setTimeout(() => {
                                                                    form.submit();
                                                                }, 1000);

                                                                return false;
                                                            }
                                                        </script>

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


                <input type="hidden" id="studentName">
                <input type="hidden" id="amount">
                <input type="hidden" id="remarks">

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
                    function printReceipt(enrollmentId) {
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
                        const receiptDateInput = document.getElementById('receipt_date' + enrollmentId).value;
                        const receiptDate = new Date(receiptDateInput);
                        document.getElementById('receiptDate').innerText = getDateInWords(receiptDate);


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
            $('#pendingTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>


@endsection
