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
            margin-top: 20px;
            margin-bottom: 15px;
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
            margin-top: 25px;
            margin-left: 9%;
        }

        .remarks-table {
            position: absolute;
            left: 0;
            right: 0;
            width: 200%;
            /* Extend beyond the container */
            margin-left: -10%;
            /* Center the extension */
        }
    </style>
</head>

<body>
    <div class="text-center my-3 no-print">
        <button class="btn btn-primary" onclick="window.print()">Print Certificate</button>
    </div>

    <!-- Header -->
    <div class="d-flex flex-column align-items-center text-center mb-1">
        <div class="d-flex align-items-center">
            <div class="sidebar-brand-icon">
                <img src="{{ asset('img/idslogo.png') }}" alt="Logo"
                    style="width: 85px; height: auto; filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.8));">
            </div>
            <div class="ms-3 text-start">
                <h4 class="mb-1">Infotech Development Systems Colleges, Inc.</h4>
                <h5 class="mb-1">OFFICE OF THE REGISTRAR</h5>
                <div>Telephone No. (052) 201-2151 | 0917 881 2638</div>
                <div>Email: idscollegescinc@gmail.com | idscolleges@yahoo.com</div>
            </div>
        </div>
        <h6 class="mt-3">CERTIFICATE OF REGISTRATION</h6>
    </div>



    <div class="student-info-container">
        <div class="student-info">
            <!-- LEFT SIDE -->
            <div class="left-side">
                <div class="info-block">
                    <span class="info-label">Name:</span>
                    {{ ucfirst(strtolower($admission->first_name)) }}
                    {{ ucfirst(strtolower($admission->middle_name)) }}
                    {{ ucfirst(strtolower($admission->last_name)) }}
                </div>


                <div class="info-block">
                    <span class="info-label">Course:</span>
                    {{ $admission->courseMapping->program->name ?? 'N/A' }}
                </div>

                <div class="info-block">
                    <span class="info-label">Major:</span>
                    {{ $admission->major ?? '_______________________________' }}
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="right-side">
                <div class="info-block">
                    <span class="info-label">Term:</span>
                    {{ $admission->semester ?? 'N/A' }}, SY {{ $admission->school_year ?? 'N/A' }}
                </div>

                <div class="info-block">
                    <span class="info-label">Year Level:</span>
                    {{ optional($admission->courseMapping->yearLevel)->name ?? 'N/A' }}
                </div>

                <div class="info-block">
                    <span class="info-label">Student No:</span>
                    {{ $admission->student_id ?? '____________________' }}
                </div>

                <div class="info-block">
                    <span class="info-label">Scholarship:</span>
                    {{ optional($admission->scholarship)->name ?? 'None' }}
                </div>
            </div>
        </div>
    </div>


    <!-- Subject Table -->
    <table style="margin-bottom: 30px;">
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
                    <td>{{ $course['units'] }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach

            <tr class="fw-bold">
                <td colspan="4">Total Units Enrolled:</td>
                <td>{{ $totalUnits }}</td>
                <td colspan="4"></td>
            </tr>
        </tbody>


    </table>

    <!-- Fee Sections -->
    <div class="row">
        <!-- MISCELLANEOUS -->
        <div class="col-3">
            <div class="section-title">MISCELLANEOUS</div>
            <table>
                @php $totalMisc = 0; @endphp
                @foreach ($miscFees as $fee)
                    <tr>
                        <td>{{ $fee->name }}</td>
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
            <div class="section-title">ASSESSMENT</div>
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

            <div class="section-title" style="margin-top: 20px;">SCHEDULE OF PAYMENT</div>
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
                <p class="reg text-center mt-5">
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
                <p class="mt-4"><strong>Verified by:</strong></p>
                <p class="fo text-center mt-5">
                    <strong>CRIS P. RONCESVALLES</strong><br><em>Finance Officer</em>
                </p>
            </div>
        </div>

        <!-- SIGNATURES -->




    </div>

</body>

</html>
