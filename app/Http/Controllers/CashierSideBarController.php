<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\OldAccount;
use App\Models\OldAccPayment;
use App\Models\OtherFee;
use App\Models\OtherPayment;
use App\Models\Program;
use App\Models\SchoolYear;
use App\Models\ShsBilling;
use App\Models\ShsEnrollment;
use App\Models\ShsPayment;
use App\Models\Student;
use App\Models\UniformFee;
use App\Models\UniformPayment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class CashierSideBarController extends Controller
{

    public function dashboard()
    {
        return view('cashier.cashier_db');
    }


    public function reportOtherPayments()
    {
        $user = Auth::user();
        $colleges = OtherPayment::where('student_id', '!=', null)
            ->where('is_void', 0)
            ->where('processed_by', $user->id)
            ->with('college')
            ->get();
        $shs = OtherPayment::where('lrn_number', '!=', null)
            ->where('is_void', 0)
            ->where('processed_by', $user->id)
            ->with('shs')
            ->get();

        foreach ($colleges as $collegePayment) {
            $college = $collegePayment->college;
            $enrollment = Enrollment::where('student_id', $collegePayment->student_id)->first();
            if ($enrollment && method_exists($enrollment, 'courseMapping')) {
                $course = $enrollment->courseMapping();
                $courseInstance = is_object($course) && method_exists($course, 'getResults') ? $course->getResults() : $course;
                $courseName = $courseInstance->getCourseName();
                $collegePayment->course = $courseName;
                $collegePayment->yearLevelName = $courseInstance->getYearLevelName();
                $collegePayment->yearCourse = $collegePayment->yearLevelName . ' ' . $courseName;
            }
        }
        foreach ($shs as $shsPayment) {
            $student = $shsPayment->shs;
            $enrollment = ShsEnrollment::where('student_id', $student->student_id)->first();
            $shsPayment->strand = $enrollment->strand;
            $shsPayment->yearLevel = $enrollment->grade_level;
            $shsPayment->yearStrand = $shsPayment->yearLevel . ' ' . $shsPayment->strand;
            $shsPayment->payment_method = $payment->payment_method ?? 'N/A';
            $shsPayment->type_of_payee = $enrollment->type_of_payee;
        }
        $payments = $colleges->merge($shs)->sortByDesc('created_at')->values();


        // echo $payments;
        return view('cashier.reports.other', compact('payments'));
    }

    public function reportOldAccountPayments()
    {
        $user = Auth::user();
        $payments = OldAccPayment::where('is_void', 0)
            ->where('processed_by', $user->id)
            ->with('student')
            ->get();

        // echo $payments;
        return view('cashier.reports.old', compact('payments'));
    }
    public function reportUniformPayments()
    {
        $colleges = UniformPayment::where('student_id', '!=', null)
            ->where('is_void', 0)
            ->with('college')
            ->get();


        foreach ($colleges as $collegePayment) {
            $college = $collegePayment->college;
            $enrollment = Enrollment::where('student_id', $collegePayment->student_id)->first();
            if ($enrollment && method_exists($enrollment, 'courseMapping')) {
                $course = $enrollment->courseMapping();
                $courseInstance = is_object($course) && method_exists($course, 'getResults') ? $course->getResults() : $course;
                $courseName = $courseInstance->getCourseName();
                $collegePayment->course = $courseName;
                $collegePayment->yearLevelName = $courseInstance->getYearLevelName();
                $collegePayment->yearCourse = $collegePayment->yearLevelName . ' ' . $courseName;
            }
        }
        $shs = UniformPayment::where('lrn_number', '!=', null)
            ->where('is_void', 0)
            ->with('shs')
            ->get();

        foreach ($shs as $shsPayment) {
            $student = $shsPayment->shs;
            $enrollment = ShsEnrollment::where('student_id', $student->student_id)->first();
            $shsPayment->strand = $enrollment->strand;
            $shsPayment->yearLevel = $enrollment->grade_level;
            $shsPayment->yearStrand = $shsPayment->yearLevel . ' ' . $shsPayment->strand;
            $shsPayment->payment_method = $payment->payment_method ?? 'N/A';
            $shsPayment->type_of_payee = $enrollment->type_of_payee;
        }
        $payments = $colleges->merge($shs)->sortByDesc('created_at')->values();
        // echo $payments;
        return view('cashier.reports.uniform', compact('payments'));
    }

    public function processPayment()
    {
        $billings = Billing::with('student')->get()->map(function ($billing) {
            $billing->full_name = Str::title($billing->student->first_name . ' ' . $billing->student->last_name);
            $billing->school_year_semester = "{$billing->school_year} - {$billing->semester}";
            return $billing;
        });

        // Only show non-voided payments
        $payments = Payment::with('student')
            ->where('is_void', false)
            ->get()
            ->sortByDesc('created_at');;

        return view('cashier.payment.process', compact('billings', 'payments'));
    }
    public function shsPayment()
    {

        $billings = ShsBilling::with('student')->get()->map(function ($billing) {
            $billing->full_name = Str::title($billing->student->first_name . ' ' . $billing->student->last_name);
            $billing->school_year_semester = "{$billing->school_year} - {$billing->semester}";
            return $billing;
        });

        // Only show non-voided payments
        $payments = ShsPayment::with('student')
            ->where('is_void', false)
            ->get();

        return view('cashier.payment.shs_pyment', compact('billings', 'payments'));
    }

    /**
     * Show the list of payment reports.
     */
    public function reportsIndex()
    {
        $payments = Payment::with('student')
            ->where(function ($query) {
                $query->whereNull('payment_type')
                    ->orWhere('payment_type', '');
            })
            ->where('is_void', false)
            ->where('status', '!=', 'void_approved')
            ->where('processed_by', Auth::id())
            ->get();


        foreach ($payments as $payment) {
            $student = $payment->student;

            $enrollment = Enrollment::where('student_id', $student->student_id)->first();
            if ($enrollment && method_exists($enrollment, 'courseMapping')) {
                $course = $enrollment->courseMapping();

                $courseInstance = is_object($course) && method_exists($course, 'getResults') ? $course->getResults() : $course;
                $courseName = $courseInstance->getCourseName();
                $payment->course = $courseName;
                $payment->yearLevelName = $courseInstance->getYearLevelName();
                $payment->yearCourse = $payment->yearLevelName . ' ' . $courseName;
                $payment->payment_method = $payment->payment_method ?? 'N/A';
            }
        }

        return view('cashier.reports.index', compact('payments'));
    }

    public function shsReports()
    {
        $payments = ShsPayment::with('student')
            ->where('is_void', false)
            ->where('status', '!=', 'void_approved')
            ->where('processed_by', Auth::id())
            ->get();


        foreach ($payments as $payment) {
            $student = $payment->student;
            $enrollment = ShsEnrollment::where('student_id', $student->student_id)->first();
            $payment->strand = $enrollment->strand;
            $payment->yearLevel = $enrollment->grade_level;
            $payment->yearStrand = $payment->yearLevel . ' ' . $payment->strand;
            $payment->payment_method = $payment->payment_method ?? 'N/A';
            $payment->type_of_payee = $enrollment->type_of_payee;
        }

        return view('cashier.reports.shs', compact('payments'));
    }


    public function pendingEnrollments()
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return back()->with('error', 'No active school year found.');
        }

        $pendingEnrollments = Enrollment::with(['admission'])
            ->where('status', 'Pending')
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->get()
            ->map(function ($enrollment) use ($activeSchoolYear) {
                $admission = $enrollment->admission;

                $enrollment->first_name = optional($admission)->first_name;
                $enrollment->last_name = optional($admission)->last_name;
                $enrollment->middle_name = optional($admission)->middle_name;

                $middleInitial = $enrollment->middle_name
                    ? strtoupper(substr($enrollment->middle_name, 0, 1)) . '.'
                    : '';

                $enrollment->full_name = Str::title(
                    "{$enrollment->last_name}, {$enrollment->first_name} {$middleInitial}"
                );
                // Billing logic
                $billing = \App\Models\Billing::where('student_id', $enrollment->student_id)
                    ->where('school_year', $activeSchoolYear->name)
                    ->where('semester', $activeSchoolYear->semester)
                    ->first();
                $enrollment->initial_payment = $billing ? $billing->initial_payment : 0.00;

                return $enrollment;
            });


        return view('cashier.payment.pending', compact('pendingEnrollments'));
    }
    public function pendingShsEnrollments()
    {
        try {
            $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

            if (!$activeSchoolYear) {
                return back()->with('error', 'No active school year found.');
            }

            $pendingEnrollments = ShsEnrollment::with(['student'])
                ->where('status', 'Pending')
                ->where('school_years', $activeSchoolYear->name)
                ->where('semester', $activeSchoolYear->semester)
                ->get()
                ->map(function ($enrollment) use ($activeSchoolYear) {
                    $admission = $enrollment->student;

                    $enrollment->first_name = optional($admission)->first_name;
                    $enrollment->last_name = optional($admission)->last_name;
                    $enrollment->middle_name = optional($admission)->middle_name;

                    $middleInitial = $enrollment->middle_name
                        ? strtoupper(substr($enrollment->middle_name, 0, 1)) . '.'
                        : '';

                    $enrollment->full_name = Str::title(
                        "{$enrollment->last_name}, {$enrollment->first_name} {$middleInitial}"
                    );


                    $student = Student::findOrFail($enrollment->student_id);
                    // Billing logic
                    $billing = ShsBilling::where('student_lrn', $student->lrn_number)
                        ->where('school_year', $activeSchoolYear->name)
                        ->where('semester', $activeSchoolYear->semester)
                        ->first();
                    $enrollment->initial_payment = $billing ? $billing->initial_payment : 0.00;

                    return $enrollment;
                }
            );

            // echo $pendingEnrollments;
            return view('cashier.payment.shspending', compact('pendingEnrollments'));
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while fetching SHS pending enrollments: ' . $e->getMessage());
        }
    }
    public function confirmPending(Request $request, $id)
    {

        $validate = $request->validate([
            'ref_number' => 'required_if:payment_method,ONLINE|nullable|string',
            'payment_method' => 'required|string'
        ]);

        $paymentMethod = $request->payment_method;
        $refNumber = $request->ref_number;
        $isOnline = $paymentMethod ? true : false;

        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return back()->with('error', 'No active school year found.');
        }

        // Find the enrollment record
        $enrollment = Enrollment::where('id', $id)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();
        // Update admission status
        $admission = Admission::where('student_id', $enrollment->student_id)->first();
        if (!$enrollment) {
            return back()->with('error', 'Enrollment not found or does not match active school year.');
        }
        if (!$isOnline) {
            // Update enrollment status
            $enrollment->status = 'Enrolled';
            $enrollment->save();

            if ($admission) {
                $admission->status = 'Enrolled';
                $admission->save();
            }
        }

        // Fetch the initial payment from billing
        $billing = Billing::where('student_id', $enrollment->student_id)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();

        if ($billing) {
            // Insert into payments table
            Payment::create([
                'student_id'     => $enrollment->student_id,
                'school_year'    => $activeSchoolYear->name,
                'semester'       => $activeSchoolYear->semester,
                'grading_period' => 'Initial Payment',
                'amount'         => $billing->initial_payment ?? 0,
                'remarks'        => $request->input('remarks'),
                'payment_date'   => now(),
                'or_number'      => $request->input('or_number'),
                'processed_by'   => Auth::id(), // or auth()->id()
                're_number'      => $refNumber,
                'payment_method' => $paymentMethod,
                'status'         => $isOnline ? "pending" : 'completed',
                'enrollment_id'  => $enrollment->id,
            ]);
        }

        return back()->with('success', 'Student enrollment and payment recorded successfully!');
    }

    public function manualconfirmPending(Request $request, $id)
    {
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            return back()->with('error', 'No active school year found.');
        }

        // Find the enrollment record
        $enrollment = Enrollment::where('id', $id)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'Enrollment not found or does not match active school year.');
        }

        // Update enrollment status
        $enrollment->status = 'Enrolled';
        $enrollment->save();

        // Update admission status
        $admission = Admission::where('student_id', $enrollment->student_id)->first();
        if ($admission) {
            $admission->status = 'Enrolled';
            $admission->save();
        }

        // Fetch the initial payment from billing
        $billing = Billing::where('student_id', $enrollment->student_id)
            ->where('school_year', $activeSchoolYear->name)
            ->where('semester', $activeSchoolYear->semester)
            ->first();

        if ($billing) {
            // Insert into payments table
            // Insert into payments table
            Payment::create([
                'student_id'    => $enrollment->student_id,
                'school_year'   => $activeSchoolYear->name,
                'semester'      => $activeSchoolYear->semester,
                'grading_period' => 'Initial Payment',
                'amount'        => $billing->initial_payment ?? 0,
                'remarks'       => $request->input('remarks'),
                'payment_date'  => $request->input('receipt_date'),
                'or_number'     => $request->input('or_number'),
                'processed_by'  => Auth::id(),
            ]);
        }

        return back()->with('success', 'Student enrollment and payment recorded successfully!');
    }


    public function otherPayments()
    {
        $today = now()->toDateString();
        $colleges = OtherPayment::where('student_id', '!=', null)
            ->whereDate('payment_date', $today)
            ->get();

        $shs = OtherPayment::where('lrn_number', '!=', null)
            ->whereDate('payment_date', $today)
            ->get();

        $payments = $colleges->merge($shs)->sortByDesc('created_at')->values();
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $payments = $payments->map(function ($payment) {
            if ($payment->student_id) {
                $payment->student = Admission::where('student_id', $payment->student_id)->first();
            } elseif ($payment->lrn_number) {
                $payment->student = Student::where('lrn_number', $payment->lrn_number)->first();
            } else {
                $payment->student = null;
            }

            $payment->full_name = Str::title($payment->student->first_name . ' ' . $payment->student->last_name);
            return $payment;
        });
        $otherFees = OtherFee::all();
        return view('cashier.payment.other', compact('payments', 'activeSchoolYear', 'otherFees'));
    }

    public function oldAccounts()
    {
        $today = now()->toDateString();
        $user =  Auth::user();
        $payments = OldAccPayment::where('processed_by', $user->id)
            ->whereDate('payment_date', $today)
            ->with('student')
            ->get();
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        // Get all other fees (no type column needed)
        $otherFees = OtherFee::all();
        // dd($payments);

        return view('cashier.payment.old', compact('payments', 'activeSchoolYear', 'otherFees'));
    }


    public function uniformPayments()
    {
        $today = now()->toDateString();
        $colleges = UniformPayment::where('student_id', '!=', null)
            ->whereDate('payment_date', $today)
            ->get();

        $shs = UniformPayment::where('lrn_number', '!=', null)
            ->whereDate('payment_date', $today)
            ->get();

        $payments = $colleges->merge($shs)->sortByDesc('created_at')->values();
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();
        $payments = $payments->map(function ($payment) {
            if ($payment->student_id) {
                $payment->student = Admission::where('student_id', $payment->student_id)->first();
            } elseif ($payment->lrn_number) {
                $payment->student = Student::where('lrn_number', $payment->lrn_number)->first();
            } else {
                $payment->student = null;
            }

            $payment->full_name = Str::title($payment->student->first_name . ' ' . $payment->student->last_name);
            return $payment;
        });
        // Get all other fees (no type column needed)
        $uniformFees = UniformFee::all();

        return view('cashier.payment.uniform', compact('payments', 'activeSchoolYear', 'uniformFees'));
    }
}
