@extends('layouts.main')

@section('tab_title', 'Ledger')
@section('accountant_sidebar')
    @include('accountant.accountant_sidebar')
@endsection

@section('content')

    <div id="content-wrapper" class="d-flex flex-column">


        <div id="content">

            @include('layouts.topbar')


            <div id="printableContainer" class="container-fluid">

                <style>
                    @media print {
                        body {
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                            margin: 0;
                            padding: 0;
                            transform: scale(1);
                            transform-origin: top left;
                        }

                        @page {
                            size: landscape;
                            margin: 0.5cm;
                        }

                        /* This will make only odd pages visible in print preview */
                        @page :left {
                            display: none;
                        }

                        /* Hide print button */
                        #printButton {
                            display: none;
                        }

                        /* Alternative approach if you're printing multiple elements on separate pages */
                        /* This will hide every even-numbered element */
                        .page-break {
                            page-break-after: always;
                        }

                        .page-break:nth-child(even) {
                            display: none;
                        }
                    }

                    body {
                        font-family: Arial, sans-serif;

                        /* Added top margin */
                        font-size: 12px;
                        /* Increased base font size */
                    }

                    .container {
                        width: 100%;
                        display: flex;
                        justify-content: space-between;
                    }

                    .section {
                        width: 49%;
                        border: 1px solid #000;
                        padding: 6px;
                        box-sizing: border-box;
                    }

                    .header {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 4px;
                    }

                    .header div {
                        width: 48%;
                    }

                    h2 {
                        text-align: left;
                        color: #007bff;
                        font-style: italic;
                        margin: 0 0 12px 0;
                        /* Increased bottom margin */
                        font-size: 14px;
                        /* Increased font size */
                    }

                    #printButton {
                        margin: 12px 0;
                        /* Increased margin */
                        padding: 8px 14px;
                        /* Increased padding */
                        font-size: 12px;
                        /* Increased font size */
                        background-color: #007bff;
                        color: #fff;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                    }

                    .header span.bold {
                        font-weight: bold;
                    }

                    .header p {
                        margin: 0.5em 0;
                    }

                    .header .underline {
                        display: inline-block;
                        width: 250px;
                        /* Adjust to set the desired underline width */
                        border-bottom: 1px solid black;
                        /* Default underline */
                        text-align: left;
                    }
                </style>
                <button id="printButton" onclick="printContainer()">Print</button>

                <div style='font-family: Arial; font-size: 12px; margin-top: 10px;'>
                    <!-- Increased font size and added margin -->
                    <h2 style="text-align: right;">STUDENT ACCOUNT LEDGER</h2>

                    <div class="header">
                        @php
                            $studentId = request('student_id', 'N/A');
                            $studentName = request('student_name', 'N/A');
                            $courseAndYear = request('course_and_year', 'N/A');
                            $scholarship = request('scholarship', 'None');
                        @endphp

                        <div>
                            <p><span class="bold">Student Name:</span> <span class="underline">{{ $studentName }}</span>
                            </p>
                            <p><span class="bold">Student Number:</span> <span
                                    class="underline">{{ $studentId }}</span></p>
                        </div>
                        <div>
                            <p style="white-space: nowrap;"><span class="bold">Course & Yr.:</span> <span
                                    class="underline">{{ $courseAndYear }}</span></p>
                            </p>
                            <p><span class="bold">Scholarship:</span> <span class="underline">{{ $scholarship }}</span>
                            </p>
                        </div>

                    </div>
<br>
                    <table style='width: 100%;'>
                        <tr>
                            <td style='width: 50%; vertical-align: top;'>
                                <table style='width: 100%; border-collapse: collapse; table-layout: fixed'>
                                    <thead>
                                        <tr>
                                            <td colspan="6" style="padding: 4px 0px; font-size: 12px;">
                                                <!-- Increased font size -->
                                                <b>
                                                    <span
                                                        style="background: #92d050; padding: 4px 8px;">{{ $year_level_name }}</span>
                                                    <i>{{ $active_semester }}, S.Y. {{ $active_school_year }}</i>
                                                </b>
                                            </td>
                                        </tr>
                                        @php
                                            // If there's more than one billing record, you might want to sum or pick one.
                                            $billing = $billings->first() ?? null;
                                        @endphp

                                        <tr>
                                            <td colspan="2"
                                                style="border-top: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                <b>TOTAL ASSESSMENTS</b>
                                            </td>
                                            <td colspan="2"
                                                style="border-top: 0.2px solid #000; border-bottom: 0.2px solid #000; border-right: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                {{ $billing ? number_format($billing->total_assessment, 2) : '0.00' }}
                                            </td>
                                            <td style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px;">
                                                Total Units
                                            </td>
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px">
                                                {{-- You need to calculate or get this value elsewhere --}}
                                                {{-- For example, if you have total units stored, display here --}}
                                                0
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 4px 6px; font-size: 12px">
                                                MISC FEE
                                            </td>
                                            <td colspan="2"
                                                style="border-top: 0.2px solid #000; border-bottom: 0.2px solid #000; border-right: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                {{ $billing ? number_format($billing->misc_fee, 2) : '0.00' }}
                                            </td>
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px;">
                                                OR #
                                            </td>
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px">
                                                DATE
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 4px 6px; font-size: 12px; color: #0099ff">
                                                Initial Payment
                                            </td>
                                            <td colspan="2"
                                                style="border-top: 0.2px solid #000; border-bottom: 0.2px solid #000; border-right: 0.2px solid #000; padding: 4px 6px; font-size: 12px; color: #0099ff">
                                                {{ $billing ? number_format($billing->initial_payment, 2) : '0.00' }}
                                            </td>
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px;">
                                                {{-- Could be OR number here if you track it --}}
                                            </td>
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px">
                                                {{-- Could be date here if you track it --}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 4px 6px; font-size: 12px;">
                                                Balance Due
                                            </td>
                                            <td colspan="2" style="padding: 4px 6px; font-size: 12px;">
                                                {{ $billing ? number_format($billing->balance_due, 2) : '0.00' }}
                                            </td>
                                            <td style="padding: 4px 6px; text-align: center; font-size: 12px;"></td>
                                            <td style="padding: 4px 6px; text-align: center; font-size: 12px"></td>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" style="padding: 8px 6px; font-size: 12px;"></td>
                                            <!-- Increased font size -->
                                        </tr>
                                        <tr>
                                            <td colspan="2"
                                                style="vertical-align: top; padding: 4px 6px; font-size: 12px">
                                                <p>Schedule of Payments:</p>
                                                <p>
                                                    PRELIM: &nbsp;
                                                    <span style="text-decoration: underline">
                                                        {{ $schedule && $schedule['prelims'] ? \Carbon\Carbon::parse($schedule['prelims'])->format('M d, Y') : 'N/A' }}
                                                    </span>
                                                </p>
                                                <p>
                                                    MIDTERM: &nbsp;
                                                    <span style="text-decoration: underline">
                                                        {{ $schedule && $schedule['midterms'] ? \Carbon\Carbon::parse($schedule['midterms'])->format('M d, Y') : 'N/A' }}
                                                    </span>
                                                </p>
                                                <p>
                                                    PREFINAL: &nbsp;
                                                    <span style="text-decoration: underline">
                                                        {{ $schedule && $schedule['pre_finals'] ? \Carbon\Carbon::parse($schedule['pre_finals'])->format('M d, Y') : 'N/A' }}
                                                    </span>
                                                </p>
                                                <p>
                                                    FINAL: &nbsp;
                                                    <span style="text-decoration: underline">
                                                        {{ $schedule && $schedule['finals'] ? \Carbon\Carbon::parse($schedule['finals'])->format('M d, Y') : 'N/A' }}
                                                    </span>
                                                </p>
                                            </td>


                                            <!-- END OF THE UNTIL FINALS -->

                                            <td colspan="4" style="vertical-align: top;">
                                                <table
                                                    style="width: 100%; text-align: center; border-collapse: collapse; table-layout: fixed">
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                            <!-- Increased font size -->
                                                            DATE
                                                        </td>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                            <!-- Increased font size -->
                                                            O.R. #
                                                        </td>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                            <!-- Increased font size -->
                                                            AMOUNT
                                                        </td>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                            <!-- Increased font size -->
                                                            BALANCE
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td></td>
                            <td style='width: 50%; vertical-align: top;'>
                                <table style='width: 100%; border-collapse: collapse; table-layout: fixed'>
                                    <thead>
                                        <tr>
                                            <td colspan="6" style="padding: 4px 0px; font-size: 12px;">
                                                <!-- Increased font size -->
                                                <b>
                                                    <span
                                                        style="background: #92d050; padding: 4px 8px;">{{ $year_level_name }}</span>
                                                    <i>2nd Semester, S.Y. {{ $active_school_year }}</i>
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"
                                                style="border-top: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                <!-- Increased font size -->
                                                <b>TOTAL ASSESTMENTS</b>
                                            </td>
                                            <td colspan="2"
                                                style="border-top: 0.2px solid #000; border-bottom: 0.2px solid #000; border-right: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                            </td> <!-- Increased font size -->
                                            <td style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px;">
                                                <!-- Increased font size -->
                                                Total Units
                                            </td>
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px">
                                            </td> <!-- Increased font size -->
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 4px 6px; font-size: 12px">
                                                <!-- Increased font size -->
                                                <b>MISC FEE</b>
                                            </td>
                                            <td colspan="2"
                                                style="border-top: 0.2px solid #000; border-bottom: 0.2px solid #000; border-right: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                            </td> <!-- Increased font size -->
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px;">
                                                <!-- Increased font size -->
                                                OR #
                                            </td>
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px">
                                                <!-- Increased font size -->
                                                DATE
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 4px 6px; font-size: 12px; color: #0099ff">
                                                <!-- Increased font size -->
                                                Initial Payment
                                            </td>
                                            <td colspan="2"
                                                style="border-top: 0.2px solid #000; border-bottom: 0.2px solid #000; border-right: 0.2px solid #000; padding: 4px 6px; font-size: 12px; color: #0099ff">
                                            </td> <!-- Increased font size -->
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px;">
                                            </td> <!-- Increased font size -->
                                            <td
                                                style="border: 0.2px solid #000; padding: 4px 6px; text-align: center; font-size: 12px">
                                            </td> <!-- Increased font size -->
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 4px 6px; font-size: 12px;">
                                                <!-- Increased font size -->
                                                Balance Due
                                            </td>
                                            <td colspan="2" style="padding: 4px 6px; font-size: 12px;"></td>
                                            <!-- Increased font size -->
                                            <td style="padding: 4px 6px; text-align: center; font-size: 12px;"></td>
                                            <!-- Increased font size -->
                                            <td style="padding: 4px 6px; text-align: center; font-size: 12px"></td>
                                            <!-- Increased font size -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" style="padding: 8px 6px; font-size: 12px;"></td>
                                            <!-- Increased font size -->
                                        </tr>
                                        <tr>
                                            <td colspan="2"
                                                style="vertical-align: top; padding: 4px 6px; font-size: 12px">
                                                <!-- Increased font size -->
                                                <p>Schedule of Payments:</p>
                                                <p>
                                                    PRELIM: &nbsp;
                                                    <span
                                                        style="text-decoration: underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </p>
                                                <p>
                                                    MIDTERM: &nbsp;
                                                    <span
                                                        style="text-decoration: underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </p>
                                                <p>
                                                    PREFINAL: &nbsp;
                                                    <span
                                                        style="text-decoration: underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </p>
                                                <p>
                                                    FINAL: &nbsp;
                                                    <span
                                                        style="text-decoration: underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                </p>
                                            </td>
                                            <td colspan="4" style="vertical-align: top;">
                                                <table
                                                    style="width: 100%; text-align: center; border-collapse: collapse; table-layout: fixed">
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                            <!-- Increased font size -->
                                                            DATE
                                                        </td>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                            <!-- Increased font size -->
                                                            O.R. #
                                                        </td>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                            <!-- Increased font size -->
                                                            AMOUNT
                                                        </td>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 4px 6px; font-size: 12px">
                                                            <!-- Increased font size -->
                                                            BALANCE
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                        <td
                                                            style="border: 0.2px solid #000; padding: 10px 6px; font-size: 12px">
                                                        </td> <!-- Increased font size -->
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <table style='width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 25px'>
                        <tbody>
                            <tr>
                                <td colspan="8" style="padding: 4px 6px; font-size: 12px"> <!-- Increased font size -->
                                    Prepared by:
                                </td>
                                <td colspan="4" style="padding: 4px 6px; font-size: 12px"></td>
                                <!-- Increased font size -->
                            </tr>
                            <tr>
                                <td colspan="8" style="padding: 10px 0"></td>
                            </tr>

                            <tr>
                                <td colspan="4"
                                    style="padding: 0 0 0 70px; font-size: 12px; text-transform: uppercase">
                                    <!-- Increased font size -->
                                    <b>CRIS P. RESCONVALLES</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="padding: 0 0 0 99px; font-size: 11px">Financial Officer</td>
                            </tr>
                        </tbody>
                    </table>
                </div>



            </div>


        </div>
        <!-- End of Main Content -->


    </div>
    </div>
    <script>
        function printContainer() {
            // Save the original content
            const originalContents = document.body.innerHTML;

            // Get the content of the printable container
            const printContents = document.getElementById('printableContainer').innerHTML;

            // Replace the body content with the printable container content
            document.body.innerHTML = printContents;

            // Trigger the print dialog
            window.print();

            // Restore the original content
            document.body.innerHTML = originalContents;
            window.location.reload(); // Reload to reapply JavaScript functionality
        }
    </script>
    <!-- End of Content Wrapper -->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- REQUIRED for DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
