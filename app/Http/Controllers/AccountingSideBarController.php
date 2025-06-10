<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Billing;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\ProgramCourseMapping;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\YearLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingSideBarController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function dashboard()
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $schoolYearName = $activeSchoolYear->name ?? null;
        $semester = $activeSchoolYear->semester ?? null;

        $totalInitialFees = Billing::where('school_year', $schoolYearName)
            ->where('semester', $semester)
            ->sum('initial_payment');

        $outstandingBalances = Billing::where('school_year', $schoolYearName)
            ->where('semester', $semester)
            ->sum('balance_due');

        $recentPayments = Payment::orderBy('payment_date', 'desc')->take(5)->get();
        $fullPaymentsCount = Billing::where('is_full_payment', true)->count();

        $balanceDistributionData = Billing::selectRaw('MONTHNAME(created_at) as month, SUM(balance_due) as outstanding, SUM(tuition_fee - balance_due) as collected')
            ->groupBy('month')
            ->orderByRaw("STR_TO_DATE(month, '%M') ASC")
            ->get();

        $paymentSourcesData = Payment::selectRaw('remarks, COUNT(*) as count')
            ->groupBy('remarks')
            ->get();

        // 📊 Program-wise Initial Payments and Balances
        $programFinancials = DB::table('billings')
            ->join(DB::raw('enrollments'), DB::raw('CONVERT(billings.student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci'), '=', DB::raw('enrollments.student_id'))
            ->join('program_course_mappings', 'enrollments.course_mapping_id', '=', 'program_course_mappings.id')
            ->join('programs', 'program_course_mappings.program_id', '=', 'programs.id')
            ->where('billings.school_year', $schoolYearName)
            ->where('billings.semester', $semester)
            ->select(
                'programs.name as program_name',
                DB::raw('SUM(billings.initial_payment) as total_initial_payment'),
                DB::raw('SUM(billings.balance_due) as total_balance_due')
            )
            ->groupBy('programs.name')
            ->get();


        return view('accountant.accountant_db', [
            'totalInitialFees' => $totalInitialFees,
            'outstandingBalances' => $outstandingBalances,
            'recentPayments' => $recentPayments,
            'fullPaymentsCount' => $fullPaymentsCount,
            'balanceDistributionData' => $balanceDistributionData,
            'paymentSourcesData' => $paymentSourcesData,
            'programFinancials' => $programFinancials, // 👈 pass to view
        ]);
    }


    /**
     * Show the transactions page.
     */
    public function transactions()
    {
        // Fetch payments and admissions
        $payments = \App\Models\Payment::all();

        // Fetch all necessary admissions and related data
        $admissions = \App\Models\Admission::with(['programCourseMapping.program'])->get();

        return view('accountant.transactions', compact('payments', 'admissions'));
    }


    public function getBillingByStudent($studentId)
    {
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['error' => 'No active school year found'], 404);
        }

        $billingData = Billing::where('student_id', $studentId)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->get();

        return response()->json($billingData);
    }

    /**
     * Show the Statement of Account (SOA) page.
     */
    public function soa()
    {
        // Fetch admissions and scholarships
        $admissions = Admission::with(['programCourseMapping.program'])->orderBy('created_at')->get();
        $scholarships = Scholarship::all();

        // Get active school year
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            // Handle no active school year case, e.g., empty billing or error message
            $billingData = collect(); // empty collection
        } else {
            // Fetch billing data for active school year and semester
            $billingData = Billing::where('school_year', $activeSchoolYear->name)
                ->where('semester', $activeSchoolYear->semester)
                ->get();
        }

        // Pass all data to the view
        return view('accountant.soa', compact('admissions', 'scholarships', 'billingData', 'activeSchoolYear'));
    }





    /**
     * Show the student ledger page.
     */
    public function studentLedger()
    {
        $admissions = Admission::with(['programCourseMapping.program'])->orderBy('created_at')->get();
        $scholarships = Scholarship::all();

        // Get active school year
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            $billingData = collect(); // empty collection
        } else {
            $billingData = Billing::where('school_year', $activeSchoolYear->name)
                ->where('semester', $activeSchoolYear->semester)
                ->get()
                ->keyBy('student_id'); // Map billing data by student_id for quick access
        }

        return view('accountant.student-ledger', compact('admissions', 'scholarships', 'billingData', 'activeSchoolYear'));
    }
  public function ledger(Request $request)
{
    $studentId = $request->query('student_id');

    $student = Admission::with(['programCourseMapping.program', 'scholarship'])
        ->where('student_id', $studentId)
        ->first();

    if (!$student) {
        return redirect()->back()->withErrors(['Student not found.']);
    }

    $billings = Billing::where('student_id', $studentId)->get();

    $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

    $yearLevelName = 'Unknown Year Level';

    if ($activeSchoolYear) {
        $enrollment = Enrollment::where('student_id', $studentId)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();

        if ($enrollment) {
            $programCourseMapping = ProgramCourseMapping::find($enrollment->course_mapping_id);
            if ($programCourseMapping) {
                $yearLevel = YearLevel::find($programCourseMapping->year_level_id);
                if ($yearLevel) {
                    $yearLevelName = $yearLevel->name;
                }
            }
        }
    }

    return view('accountant.ledger', [
        'student' => $student,
        'billings' => $billings,
        'active_school_year' => $activeSchoolYear->name ?? 'Unknown School Year',
        'active_semester' => $activeSchoolYear->semester ?? 'Unknown Semester',
        'year_level_name' => $yearLevelName,

        // Pass the schedule of payment dates or null if no active sy
        'schedule' => $activeSchoolYear ? [
            'prelims' => $activeSchoolYear->prelims_date,
            'midterms' => $activeSchoolYear->midterms_date,
            'pre_finals' => $activeSchoolYear->pre_finals_date,
            'finals' => $activeSchoolYear->finals_date,
        ] : null,
    ]);
}




    public function pendingVoids()
    {
        // Get only payments marked as pending for voiding
        $payments = Payment::where('status', 'pending_void')
            ->with(['student', 'student.enrollment.programCourseMapping.program']) // adjust as per your actual relations
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('accountant.pending-voids', compact('payments'));
    }

    public function approveVoid(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            // Check payment_type first
            if ($payment->payment_type === 'others') {
                // If payment_type is "others", only update status
                $payment->status = 'void_approved';
                $payment->save();
                return; // Exit the transaction early
            }

            // Proceed with the normal void approval logic
            $payment->status = 'void_approved';
            $payment->save();

            // Get billing record for this student + school year + semester
            $billing = Billing::where('student_id', $payment->student_id)
                ->where('school_year', $payment->school_year)
                ->where('semester', $payment->semester)
                ->first();

            if (!$billing) {
                throw new \Exception('Billing record not found.');
            }

            // The grading period for the voided payment, e.g. 'prelims'
            $gradingPeriod = $payment->grading_period;

            // Validate the grading period matches a due column
            $validPeriods = ['prelims', 'midterms', 'prefinals', 'finals'];
            if (!in_array($gradingPeriod, $validPeriods)) {
                throw new \Exception("Invalid grading period '{$gradingPeriod}' in payment.");
            }

            // Column to add amount back to
            $dueColumn = $gradingPeriod . '_due';

            // Add the voided amount back to the due column
            $billing->$dueColumn += $payment->amount;

            // Recalculate balance_due as sum of all dues
            $billing->balance_due =
                $billing->prelims_due +
                $billing->midterms_due +
                $billing->prefinals_due +
                $billing->finals_due;

            // Update is_full_payment flag
            $billing->is_full_payment = $billing->balance_due <= 0;

            $billing->save();
        });

        return redirect()->route('accountant.pending_voids')->with('success', 'Void approved and billing updated.');
    }


    /**
     * Reject void request.
     */
    public function rejectVoid(Payment $payment)
    {
        $payment->status = 'void_rejected';
        $payment->save();

        return redirect()->route('accountant.pending_voids')->with('success', 'Void request rejected.');
    }
    /**
     * Show the promisories page.
     */
    public function promisories()
    {
        // Fetch any necessary data for promisories here.
        return view('promisories');
    }
}
