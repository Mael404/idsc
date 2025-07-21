<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificate of Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        table,
        th,
        td {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        table {
            width: 100%;
            margin-bottom: 10px;
        }

        th,
        td {
            padding: 3px;
            text-align: left;
        }

        .section-title {
            background-color: #eaeaea;
            font-weight: bold;
            padding: 4px;
            border: 1px solid #000;
        }

        .no-border td,
        .no-border th {
            border: none !important;
        }

        .signature-box {
            height: 100px;
        }

        .text-end-small {
            text-align: right;
            font-size: 10px;
        }

        .tiny-note {
            font-size: 9px;
            color: #555;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .student-info-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .student-info {
            display: flex;
            width: 90%;
            max-width: 1000px;
            justify-content: space-between;
        }

        .left-side,
        .right-side {
            width: 31%;
        }

        .info-block {
            margin-bottom: 2px;
            display: flex;
        }

        .info-label {
            font-weight: bold;
            margin-right: 5px;
        }

        /* New styles for the remarks table */
        .remarks-container {
            position: relative;
            margin-top: 15px;
            margin-left: 9%;
        }

        .remarks-table {
            position: absolute;
            left: 0;
            right: 0;
            width: 200%;
            margin-left: -10%;
        }

    .name-uppercase {
    font-weight: bold;
    font-size: 1.3em;
    text-transform: uppercase;
    white-space: normal;     /* Allow wrapping */
    overflow: visible;       /* No cutting */
    text-overflow: unset;    /* Remove ellipsis */
    display: inline;         /* Inline behaves better for text flow */
}

.left-side {
    width: 65%; /* instead of 31% */
}
.right-side {
    width: 33%; /* or adjust to balance */
}

    </style>
</head>

<body>
    <img src="{{ asset('img/idslogo.png') }}" alt="Logo Watermark"
        style="
       position: fixed;
       top: 50%;
       left: 50%;
       transform: translate(-50%, -50%);
       width: 500px;
       height: auto;
       opacity: 0.1;
       pointer-events: none;
       z-index: 0;
     ">

    <div class="d-flex justify-content-between px-4" style="margin-top:0; padding-top:0">
        <p style="margin: 0; font-size: 0.6rem;">Date Printed: {{ \Carbon\Carbon::now()->format('m/d/Y') }}</p>
        <p style="margin: 0; font-size: 0.6rem;">Time Printed: {{ \Carbon\Carbon::now('Asia/Manila')->format('h:i A') }}
        </p>
    </div>
    <div class="text-center mb-1 no-print"> <!-- Changed my-3 to mb-1 -->
        <button class="btn btn-primary" onclick="window.print()">Print Certificate</button>
    </div>

    <!-- Header -->
    <div class="d-flex flex-column align-items-center text-center mb-1"> <!-- Changed mb-3 to mb-1 -->
        <div class="d-flex align-items-center">
            <div class="sidebar-brand-icon">
                <img src="{{ asset('img/idslogo.png') }}" alt="Logo" style="width: 85px; height: auto;">
            </div>
            <div class="ms-3 text-start">
                <h4 class="mb-0 fw-bold"
                    style="font-family: 'Times New Roman', Times, serif; font-size: 1.1rem; margin-top: 0;">
                    <!-- Added margin-top:0 -->
                    Infotech Development Systems Colleges, Inc.
                </h4>
                <div class="fw-semibold"
                    style="font-size: 0.9rem; font-family: 'Times New Roman', Times, serif; margin-top: 2px;">
                    <!-- Reduced spacing -->
                    OFFICE OF THE REGISTRAR
                </div>
                <div style="font-size: 0.8rem; margin-top: 2px;">Telephone No. (052) 201-2151 | 0917 881 2638</div>
                <!-- Reduced spacing -->
                <div style="font-size: 0.8rem; margin-top: 2px;"> <!-- Reduced spacing -->
                    Email: <a href="mailto:idscollegesinc@gmail.com">idscollegesinc@gmail.com</a> |
                    <a href="mailto:idscolleges@yahoo.com">idscolleges@yahoo.com</a>
                </div>
            </div>
        </div>
        <div class="mt-1 mx-auto"
            style="width: 85%; margin-bottom: 1px; border-top: 4px solid #005d3e; border-bottom: 1.5px solid #005d3e;">
        </div>
        <div class="mx-auto" style="width: 85%; margin-top: 0px;">
            <div style="border-top: 2px solid #005d3e;"></div>
        </div>

        <h5 class="mt-1 fw-bold" style="letter-spacing: 6px; font-weight:900; font-size: 1rem;">CERTIFICATE OF
            REGISTRATION</h5> <!-- Changed mt-3 to mt-1 -->
    </div>

    <div class="student-info-container">
        <div class="student-info">
            <!-- LEFT SIDE -->
            <div class="left-side">
                <div class="info-block">
                    <span class="info-label">Name:</span>
                    <span class="name-uppercase">
                        {{ $enrollment->admission->first_name ?? '' }}
                        {{ $enrollment->admission->middle_name ?? '' }}
                        {{ $enrollment->admission->last_name ?? '' }}
                    </span>

                </div>



                <div class="info-block" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <span class="info-label">Course:</span>
                    {{ $enrollment->courseMapping->program->name ?? 'N/A' }}
                </div>

                <div class="info-block">
                    <span class="info-label">Major:</span>
                    {{ $enrollment->admission->major ?? '_______________________________' }}
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="right-side">
                <div class="info-block">
                    <span class="info-label">Term:</span>
                    {{ $enrollment->semester ?? 'N/A' }}, SY {{ $enrollment->school_year ?? 'N/A' }}
                </div>

                <div class="info-block">
                    <span class="info-label">Year Level:</span>
                    {{ optional($enrollment->courseMapping->yearLevel)->name ?? 'N/A' }}
                </div>

                <div class="info-block">
                    <span class="info-label">Student No:</span>
                    {{ $enrollment->student_id ?? '____________________' }}
                </div>

                <div class="info-block">
                    <span class="info-label">Scholarship:</span>
                    {{ optional($enrollment->scholarship)->name ?? 'None' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Table -->
    <table style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th>Prof./Instructor</th>
                <th>Subject</th>
                <th>Code</th>
                <th>Descriptive Title</th>
                <th>Units</th>
                <th>Time</th>
                <th>Day</th>
                <th>Room</th>
                <th>FINAL GRADES</th>
            </tr>
        </thead>
        <tbody>
            @php $totalUnits = 0; @endphp
            @foreach ($formattedCourses as $course)
                @php $totalUnits += $course['units']; @endphp
                <tr>
                    <td></td>
                    <td>{{ $course['subject'] }}</td>
                    <td>{{ $course['code'] }}</td>
                    <td>{{ $course['name'] }}</td>
                    <td style="text-align: center;">{{ $course['units'] }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach

           <tr class="fw-bold">
    <td colspan="4" style="text-align: right; padding-right: 10px;">Total Units Enrolled:</td>
    <td style="font-weight: bold; font-size: 1.3em; text-align: center;">{{ $totalUnits }}</td>
    <td colspan="4"></td>
</tr>

        </tbody>
    </table>

    <!-- Fee Sections -->
    <div class="row">
        <!-- MISCELLANEOUS -->
       <div class="col-3">
    <div class="section-title" style="text-align: center; font-size: larger;">MISCELLANEOUS</div>
    <table>
        @php $totalMisc = 0; @endphp
        @foreach ($miscFees as $fee)
            <tr>
                <td>{{ $isIrregular ? $fee->fee_name : $fee->name }}</td>
                <td class="text-end">
                    {{ is_numeric($fee->amount) ? number_format($fee->amount, 2) : $fee->amount }}
                    @php
                        $totalMisc += is_numeric($fee->amount) ? $fee->amount : 0;
                    @endphp
                </td>
            </tr>
        @endforeach
        <tr class="fw-bold-border">
            <td>Total</td>
            <td class="text-end">{{ number_format($totalMisc, 2) }}</td>
        </tr>
    </table>
</div>

        <!-- ASSESSMENT + SCHEDULE -->
        <div class="col-5">
            <div class="section-title" style="text-align: center; font-size: larger;">ASSESSMENT</div>
            <table>
                <tr>
                    <td>Tuition Fee</td>
                    <td class="text-end">{{ number_format($billing->tuition_fee, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td class="text-end">{{ number_format($billing->discount, 2) }}</td>
                </tr>
                <tr>
                    <td>Tuition Fee (with Discount)</td>
                    <td class="text-end">{{ number_format($billing->tuition_fee_discount, 2) }}</td>
                </tr>
                <tr>
                    <td>MISC. FEE</td>
                    <td class="text-end">{{ number_format($billing->misc_fee, 2) }}</td>
                </tr>
                <tr>
                    <td>OLD / BACK ACCOUNTS</td>
                    <td class="text-end">{{ number_format($billing->old_accounts, 2) }}</td>
                </tr>
                <tr class="fw-bold">
                    <td>Total Assessment</td>
                    <td class="text-end">{{ number_format($billing->total_assessment, 2) }}</td>
                </tr>
                <tr>
                    <td>Initial Payment Upon Enrolment</td>
                    <td class="text-end">{{ number_format($billing->initial_payment, 2) }}</td>
                </tr>
                <tr class="fw-bold">
                    <td>Balance Due</td>
                    <td class="text-end">{{ number_format($billing->balance_due, 2) }}</td>
                </tr>
            </table>
            @php
                $installment = $billing->balance_due / 4;
            @endphp

            <div class="section-title" style="margin-top: 15px; text-align: center; font-size: larger;">SCHEDULE OF
                PAYMENT</div>
            <table>
                <tr>
                    <td>PRELIM - {{ \Carbon\Carbon::parse($activeSchoolYear->prelims_date)->format('M d, Y') }}</td>
                    <td class="text-end">₱{{ number_format($installment, 2) }}</td>
                </tr>
                <tr>
                    <td>MIDTERM - {{ \Carbon\Carbon::parse($activeSchoolYear->midterms_date)->format('M d, Y') }}</td>
                    <td class="text-end">₱{{ number_format($installment, 2) }}</td>
                </tr>
                <tr>
                    <td>PRE-FINAL - {{ \Carbon\Carbon::parse($activeSchoolYear->pre_finals_date)->format('M d, Y') }}
                    </td>
                    <td class="text-end">₱{{ number_format($installment, 2) }}</td>
                </tr>
                <tr>
                    <td>FINAL - {{ \Carbon\Carbon::parse($activeSchoolYear->finals_date)->format('M d, Y') }}</td>
                    <td class="text-end">₱{{ number_format($installment, 2) }}</td>
                </tr>
            </table>

            <div class="remarks-container">
                <table class="remarks-table" style="font-size: 0.75rem; line-height: 1;">
                    <thead>
                        <tr>
                            <th class="text-center">REMARKS</th>
                            <th class="text-center">OR/Date</th>
                            <th class="text-center">Prelim</th>
                            <th class="text-center">Midterm</th>
                            <th class="text-center">Pre-Final</th>
                            <th class="text-center">Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2" style="width: 150px; height: 60px;"></td>
                            <td style="height: 30px;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="height: 30px;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td rowspan="2" style="width: 150px; height: 60px;"></td>
                            <td style="height: 30px;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="height: 30px;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-4">
            <div class="signature-box">
                <p class="text-center">__________________________</p>
                <p class="text-center">Student's Signature</p>

                <p class="mt-0"><strong>Certified Correct:</strong></p>
                <p class="reg text-center mt-4">
                    <strong>CHRISTY R. FUENTES</strong><br><em>Registrar</em>
                </p>

                <div class="text-center">
                    <table class="table table-sm d-inline-block mb-0"
                        style="width: auto; font-size: 0.75rem; line-height: 1; height: 1.6rem;">
                        <tr style="height: 1rem;">
                            <th style="min-width: 60px;" class="text-center">OR #</th>
                            <th style="min-width: 80px;" class="text-center">Date</th>
                        </tr>
                        <tr style="height: 1rem;">
                            <td>&nbsp;</td>
                            <td style="min-width: 80px;" class="text-center">{{ now()->format('Y-m-d') }}</td>
                        </tr>
                    </table>
                </div>

                <br>
                <p class="mt-3"><strong>Verified by:</strong></p>
                <p class="fo text-center mt-4">
                    <strong>CRIS P. RONCESVALLES</strong><br><em>Finance Officer</em>
                </p>
            </div>
        </div>
    </div>

    <div style="position: absolute; bottom: 2.2rem; left: 0; right: 0;  font-size: 0.7rem; font-weight: bold;">
        Note:
    </div>
    <div style="position: absolute; bottom: 1.2rem; left: 0; right: 0;  font-size: 0.7rem; font-style:italic">
        * A replacement copy may be issued upon payment of Php 50.00 to the college cashier.
    </div>
    <div style="position: absolute; bottom: 0; left: 0; right: 0; font-size: 0.7rem;">
        IDSC-F-REG-001
    </div>
</body>

</html>
