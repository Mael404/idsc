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
                                                    <td style="text-align: center">{{ $payment->student_id }}</td>
                                                    <td>{{ $payment->student->full_name ?? 'No student found' }}</td>
                                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y h:i A') }}
                                                    </td>

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
                </div>

                <div class="modal fade" id="newPaymentModal" tabindex="-1" aria-labelledby="newPaymentModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('payment.store') }}"
                            onsubmit="return handlePrintAndSubmit(event)">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="newPaymentModalLabel">New Payment</h5>

                                </div>
                                <div class="modal-body">

                                    <input type="hidden" id="schoolYearInput" name="school_year">
                                    <input type="hidden" id="semesterInput" name="semester">

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
                                        <label for="gradingPeriod">Grading Period</label>
                                        <select class="form-control" id="gradingPeriod" name="grading_period" required>
                                            <option value="">Select Grading Period</option>
                                            <option value="prelims">Prelims</option>
                                            <option value="midterms">Midterms</option>
                                            <option value="prefinals">Pre-finals</option>
                                            <option value="finals">Finals</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="orNumber">OR Number</label>
                                        <input type="text" class="form-control @error('or_number') is-invalid @enderror"
                                            id="orNumber" name="or_number" placeholder="Enter OR Number" required>
                                        @error('or_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="printableReceipt" style="display: none;">
                    <div
                        style="width: 100%; font-family: Arial, sans-serif; text-align: left; position: relative; font-size: larger; line-height: 1.8;">
                        <div style="text-align: left; font-size: 20px; margin-bottom: 50px;">
                            <span id="receiptDate"></span>
                        </div>
                        <div style="text-align: left; font-size: 22px; font-weight: bold; margin-bottom: 50px;">
                            <span id="receiptStudentName"></span>
                        </div>
                        <div style="text-align: left; font-size: 20px; margin-bottom: 20px;">
                            School Year: <span id="receiptSchoolYear"></span>
                        </div>
                        <div style="text-align: left; font-size: 20px; margin-bottom: 20px;">
                            Semester: <span id="receiptSemester"></span>
                        </div>
                        <div style="text-align: left; font-size: 20px; margin-bottom: 50px;">
                            <span id="receiptAmountWords"></span>
                        </div>
                        <div style="text-align: left; font-size: 20px; margin-bottom: 50px;">
                            <span id="receiptAmount"></span>
                        </div>
                        <div style="text-align: left; font-size: 20px; margin-bottom: 50px;">
                            <span id="receiptRemarks"></span>
                        </div>
                    </div>
                </div>


                <script>
                    function handlePrintAndSubmit(event) {
                        event.preventDefault(); // Prevent form from submitting immediately
                        printReceipt(); // Trigger printing
                        setTimeout(() => {
                            event.target.submit(); // Submit the form after printing
                        }, 800); // Adjust delay as needed
                        return false;
                    }

                    function printReceipt() {
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

                        const currentDate = new Date();

                        const nameParts = document.getElementById('studentName').value.trim().split(' ');
                        let formattedName = document.getElementById('studentName').value;
                        if (nameParts.length >= 3) {
                            const lastName = nameParts.pop();
                            const firstName = nameParts.shift();
                            const middleInitial = nameParts.length > 0 ? nameParts[0].charAt(0).toUpperCase() + '.' : '';
                            formattedName = `${lastName.toUpperCase()}, ${firstName.toUpperCase()} ${middleInitial}`;
                        }

                        const amount = parseFloat(document.getElementById('amount').value);
                        const remarks = document.getElementById('remarks').value;
                        const amountWords = convertAmountToWords(amount).toUpperCase() + ' PESOS ONLY';

             
                        function formatSemester(sem) {
                            sem = sem.toString().toUpperCase();
                            if (sem === '1' || sem === 'FIRST' || sem === '1ST') return '1ST';
                            if (sem === '2' || sem === 'SECOND' || sem === '2ND') return '2ND';
                            if (sem === '3' || sem === 'THIRD' || sem === '3RD') return '3RD';
                            return sem; // fallback if unexpected input
                        }

                        const rawSchoolYear = document.getElementById('schoolYearInput')?.value || "N/A";
                        const rawSemester = document.getElementById('semesterInput')?.value || "N/A";

                        const formattedSemester = formatSemester(rawSemester);
                        const formattedSemesterAndSY = `${formattedSemester} SEMESTER SY ${rawSchoolYear}`;

                        const cashier = "EVELYN P.";

                        const printContent = `
            <div>${getDateInWords(currentDate)}</div>
            <div>${formattedName}</div>
          <div>${formattedSemesterAndSY}</div>

            <div>${remarks}</div>
            <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                <div>${amountWords}</div>
                <div>₱${amount.toFixed(2)}</div>
            </div>
            <div style="text-align: right; margin-top: 60px;">${cashier}</div>
        `;

                        const printWindow = window.open('', '', 'width=600,height=600');
                        printWindow.document.open();
                        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Receipt</title>
                    <style>
                        body { font-family: Arial, sans-serif; font-size: 18px; padding: 20px; line-height: 1.8; }
                        div { margin-bottom: 15px; }
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
                        const teens = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
                            "Nineteen"
                        ];
                        const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

                        function numberToWords(num) {
                            let word = "";

                            if (num >= 100000) {
                                word += ones[Math.floor(num / 100000)] + " Hundred ";
                                num %= 100000;
                            }

                            if (num >= 20000) {
                                word += tens[Math.floor(num / 10000)] + " ";
                                num %= 10000;
                            } else if (num >= 10000) {
                                word += teens[Math.floor((num % 10000) / 1000)] + " Thousand ";
                                num %= 1000;
                            }

                            if (num >= 1000) {
                                word += ones[Math.floor(num / 1000)] + " Thousand ";
                                num %= 1000;
                            }

                            if (num >= 100) {
                                word += ones[Math.floor(num / 100)] + " Hundred ";
                                num %= 100;
                            }

                            if (num >= 20) {
                                word += tens[Math.floor(num / 10)] + " ";
                                num %= 10;
                            } else if (num >= 10) {
                                word += teens[num - 10] + " ";
                                num = 0;
                            }

                            if (num > 0) {
                                word += ones[num] + " ";
                            }

                            return word.trim();
                        }

                        let num = Math.floor(amount);
                        let cents = Math.round((amount - num) * 100);

                        let words = numberToWords(num);
                        if (cents > 0) {
                            words += ` and ${cents} Centavos`;
                        }

                        return words || "Zero";
                    }
                </script>



                <style>
                    #printableReceipt {
                        font-size: 24px !important;
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

                        // Hidden inputs
                        const schoolYearInput = document.getElementById('schoolYearInput');
                        const semesterInput = document.getElementById('semesterInput');

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
                                                // Set visible fields
                                                studentNameInput.value = student.full_name;
                                                balanceDueInput.value = student.balance_due;
                                                searchInput.value = student.student_id;
                                                suggestionsList.innerHTML = '';
                                                // Set hidden inputs directly from data
                                                schoolYearInput.value = student.school_year;
                                                semesterInput.value = student.semester;

                                                // Fetch semester and school_year
                                                fetch(`/get-semester-year/${student.student_id}`)
                                                    .then(response => response.json())
                                                    .then(info => {
                                                        if (!info.error) {
                                                            schoolYearInput.value = info
                                                                .school_year;
                                                            semesterInput.value = info.semester;
                                                        } else {
                                                            schoolYearInput.value = '';
                                                            semesterInput.value = '';
                                                            console.error(info.error);
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Fetch error:', error);
                                                    });
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
            $('#paymentsTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>


@endsection
