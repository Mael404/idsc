@extends('layouts.main')
<style>
    /* Style for the multi-select dropdown */
    #course_ids {
        min-height: 150px;
        padding: 8px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    #course_ids option {
        padding: 8px;
        margin: 2px 0;
        border-radius: 3px;
    }

    #course_ids option:hover {
        background-color: #f8f9fa;
    }

    #course_ids option:checked {
        background-color: #007bff;
        color: white;
    }

    /* Style for the selected courses list */
    #selectedCoursesList li {
        padding: 8px;
        background-color: #f8f9fa;
        border-radius: 4px;
        margin-bottom: 5px;
    }

    .remove-course {
        padding: 0 5px;
        line-height: 1;
    }
</style>
@section('tab_title', 'Dashboard')
@section('registrar_sidebar')
    @include('registrar.registrar_sidebar')
@endsection

@section('content')

    <div id="content-wrapper" class="d-flex flex-column">


        <div id="content">

            @include('layouts.topbar')


            <div class="container-fluid">
                @include('layouts.success-message')

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Existing Students</h1>


                    <button class="btn btn-primary" data-toggle="modal" data-target="#admissionFormModal">
                        Open Admission Form
                    </button>

                </div>
                <div class="modal fade" id="admissionFormModal" tabindex="-1" aria-labelledby="admissionFormModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('re_enroll_regular.store') }}" id="admissionForm">
                                @csrf
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Admission Form</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- Progress Indicator -->
                                    <div class="progress mb-4">
                                        <div class="progress-bar" id="progressBar" role="progressbar" style="width: 50%;"
                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                            Step 1 of 2
                                        </div>
                                    </div>

                                    <!-- Tab Navigation -->
                                    <ul class="nav nav-tabs mb-3" id="formTabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1"
                                                role="tab" aria-controls="step1" aria-selected="true">
                                                <i class="fas fa-search mr-1"></i> Student Verification
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link disabled" id="step2-tab" data-toggle="tab" href="#step2"
                                                role="tab" aria-controls="step2" aria-selected="false">
                                                <i class="fas fa-graduation-cap mr-1"></i> Course Mapping
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- Tab Content -->
                                    <div class="tab-content">
                                        <!-- Step 1: Search for Student -->
                                        <div class="tab-pane fade show active" id="step1" role="tabpanel"
                                            aria-labelledby="step1-tab">
                                            <div class="form-group">
                                                <label for="student_search">Search Student (Name or Student ID) <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group mb-3">
                                                    <input type="text" id="student_search" name="student_search"
                                                        class="form-control" placeholder="Enter student ID or name" />
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" type="button"
                                                            id="searchStudentBtn">
                                                            <i class="fas fa-search"></i> Search
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Loading indicator -->
                                                <div id="searchLoading" class="text-center" style="display: none;">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p>Searching student...</p>
                                                </div>

                                                <!-- Student info display -->
                                                <div id="studentInfoContainer" style="display: none;">
                                                    <div class="card mt-3">
                                                        <div class="card-header bg-info text-white">
                                                            <strong>Student Information</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <div id="studentInfo"></div>
                                                            <div id="enrollmentWarnings" class="mt-3"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 2: Course Mapping (updated version) -->
                                        <!-- Step 2: Course Mapping -->
                                        <div class="tab-pane fade" id="step2" role="tabpanel"
                                            aria-labelledby="step2-tab">
                                            <input type="hidden" name="student_id" id="student_id_input">

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="course_mapping_id">Course Mapping <span
                                                            class="text-danger">*</span></label>
                                                    <select id="course_mapping_id" name="course_mapping_id"
                                                        class="form-control" required>
                                                        <option value="" selected disabled>Choose Mapping</option>
                                                        @foreach ($courseMappings as $mapping)
                                                            @if ($mapping->program && $mapping->yearLevel)
                                                                <option value="{{ $mapping->id }}"
                                                                    data-program="{{ $mapping->program_id }}"
                                                                    data-year="{{ $mapping->year_level_id }}"
                                                                    data-semester="{{ $mapping->semester_id }}"
                                                                    data-sy="{{ $mapping->effective_sy }}">
                                                                    [ID: {{ $mapping->id }}] {{ $mapping->program->name }}
                                                                    -
                                                                    {{ $mapping->yearLevel->name }}
                                                                    ({{ $mapping->effective_sy }})
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>

                                                    <div class="mt-2">
                                                        <small class="text-muted">Selected Mapping ID: <span
                                                                id="displayMappingId">-</span></small>
                                                    </div>

                                                    <div id="totalUnitsContainer" class="alert alert-info mt-3"
                                                        style="display:none;">
                                                        Total Units: <strong id="totalUnitsValue"></strong>
                                                    </div>
                                                    <div id="tuitionFeeContainer" class="alert alert-success mt-2"
                                                        style="display:none;">
                                                        Tuition Fee: <strong id="tuitionFeeValue"></strong>
                                                    </div>
                                                    <div id="feeCalculationContainer" class="alert alert-secondary mt-2"
                                                        style="display:none;">
                                                        <small>Calculation: <span
                                                                id="feeCalculationDetails"></span></small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="major">Major</label>
                                                        <input type="text" id="major" name="major"
                                                            class="form-control"
                                                            placeholder="Enter major (if applicable)">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="scholarship">Scholarship</label>
                                                        <select id="scholarship" name="scholarship" class="form-control">
                                                            <option value="" selected disabled>Select Scholarship
                                                            </option>
                                                            @foreach ($scholarships as $scholarship)
                                                                <option value="{{ $scholarship->id }}">
                                                                    {{ $scholarship->name }}
                                                                    ({{ $scholarship->discount_percentage }}% Discount)
                                                                </option>
                                                            @endforeach
                                                            <option value="none">None</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="prevBtn"
                                        style="display: none;">
                                        <i class="fas fa-arrow-left mr-1"></i> Back
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submitBtn"
                                        style="display: none;">
                                        <i class="fas fa-check mr-1"></i> Submit Enrollment
                                    </button>
                                    <button type="button" class="btn btn-primary" id="nextBtn" disabled>
                                        Continue <i class="fas fa-arrow-right ml-1"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                        <i class="fas fa-times mr-1"></i> Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="row justify-content-center mt-3">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="miscFees">
                                        <thead>
                                            <tr>
                                                <th>Student No.</th>
                                                <th>Full Name</th>
                                                <th>Program</th>

                                                <th>Admission Status</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($enrollments as $enrollment)
                                                <tr>
                                                    <td>{{ strtoupper($enrollment->student_id) }}</td>
                                                    <td>{{ strtoupper($enrollment->admission->full_name ?? 'N/A') }}</td>
                                                    <td>{{ $enrollment->courseMapping->combination_label ?? 'N/A' }}</td>
                                                    <td>{{ ucfirst($enrollment->status) }}</td>
                                                    <td>{{ $enrollment->admission->email ?? 'N/A' }}</td>
                                                    <td>
                                                      @php
    $initialPayment = $enrollment->billing->initial_payment ?? 0;
    $studentId = $enrollment->student_id;
@endphp

<div class="col-12">
    <div class="mb-2">
  
      <input 
    type="hidden" 
    name="initial_payment" 
    id="initial_payment{{ $enrollment->student_id }}"
    value="{{ old('initial_payment', ($billing->initial_payment ?? 0) > 0 ? $billing->initial_payment : '') }}">

    </div>
</div>

<div class="position-relative d-inline-block">
    <button type="button"
        class="btn btn-info btn-sm position-relative"
        data-bs-toggle="modal"
        data-bs-target="#studentModal{{ $studentId }}"
        id="viewBtn{{ $studentId }}"
        title="View Student">
        <i class="fas fa-eye"></i>

        {{-- Warning badge, initially hidden --}}
        <span
            id="warning-badge-{{ $studentId }}"
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark d-none"
            data-bs-toggle="tooltip"
            title="This student hasn't made an initial payment yet.">
            Warning
        </span>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const input = document.getElementById('initial_payment{{ $studentId }}');
        const warningBadge = document.getElementById('warning-badge-{{ $studentId }}');

        function checkInitialPayment() {
            const value = parseFloat(input.value);
            if (isNaN(value) || value <= 0) {
                warningBadge.classList.remove('d-none');
            }
        }

        // Initial check on page load
        checkInitialPayment();

        // Optional: Recheck on input change
        input.addEventListener('input', checkInitialPayment);
    });
</script>

                                                        <a href="{{ route('admissions.printCOR', $enrollment->student_id) }}"
                                                            target="_blank" rel="noopener" class="btn btn-primary btn-sm"
                                                            title="Print COR">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                             <div class="modal fade" id="studentModal{{ $enrollment->student_id }}" tabindex="-1"
    aria-labelledby="studentModalLabel{{ $enrollment->student_id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="studentModalLabel{{ $enrollment->student_id }}">
                    <i class="bi bi-person-vcard me-2"></i>Student Billing Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            @php
            
                // Get current active school year
                $activeSchoolYear = \App\Models\SchoolYear::where('is_active', 1)->first();
                
                // Get billing information for this student in the active school year
                $billing = \App\Models\Billing::where('student_id', $enrollment->student_id)
                    ->where('school_year', $activeSchoolYear->name ?? null)
                    ->where('semester', $activeSchoolYear->semester ?? null)
                    ->first();
            @endphp

            @if($billing)
            <form method="POST" action="{{ route('billing.updateInitialPayment', $billing->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <!-- Basic Information Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-info-circle me-2"></i>Basic Information
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Student ID:</strong></p>
                                    <p class="text-dark">{{ strtoupper($enrollment->student_id) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Full Name:</strong></p>
                                    <p class="text-dark">{{ strtoupper($enrollment->admission->full_name ?? 'N/A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Program:</strong></p>
                                    <p class="text-dark">{{ $enrollment->courseMapping->combination_label ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Admission Status:</strong></p>
                                    <p class="text-dark">{{ ucfirst($enrollment->status) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Information Section -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-cash-coin me-2"></i>Financial Information
                        </h6>

                        <!-- School Year and Semester -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>School Year:</strong></p>
                                    <p class="text-dark">{{ $activeSchoolYear->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Semester:</strong></p>
                                    <p class="text-dark">{{ $activeSchoolYear->semester ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Fee Breakdown -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Tuition Fee:</strong></p>
                                    <p class="text-dark">₱{{ number_format($billing->tuition_fee, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Discount:</strong></p>
                                    <p class="text-dark">₱{{ number_format($billing->discount, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Tuition After Discount:</strong></p>
                                    <p class="text-dark">₱{{ number_format($billing->tuition_fee_discount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Miscellaneous Fee:</strong></p>
                                    <p class="text-dark">₱{{ number_format($billing->misc_fee, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Old Accounts Balance:</strong></p>
                                    <p class="text-dark">₱{{ number_format($billing->old_accounts, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Assessment and Balance Due -->
                        <div class="alert alert-primary mt-3 mb-4">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="p-2">
                                        <p class="mb-1"><strong>Total Assessment:</strong></p>
                                        <h5 class="fw-bold mb-0">
                                            ₱{{ number_format($billing->total_assessment, 2) }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2">
                                        <p class="mb-1"><strong>Balance Due:</strong></p>
                                        <h5 class="fw-bold mb-0">
                                            ₱{{ number_format($billing->balance_due, 2) }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <h6 class="fw-bold mt-4 mb-3">
                            <i class="bi bi-calendar-check me-2"></i>Payment Information
                        </h6>
                        
                        <div class="row g-3">
                            <!-- Initial Payment Input -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="initial_payment{{ $enrollment->student_id }}" class="form-label"><strong>Initial Payment</strong></label>
                                    <input 
                                        placeholder="0.00" 
                                        type="number" 
                                        name="initial_payment" 
                                        step="0.01" 
                                        min="0"
                                        class="form-control"
                                        id="initial_payment{{ $enrollment->student_id }}"
                                        value="{{ old('initial_payment', ($billing->initial_payment ?? 0) > 0 ? $billing->initial_payment : '') }}">
                                </div>
                            </div>

                            <!-- Payment Schedule -->
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Prelims Due:</strong></p>
                                    <p class="text-dark">₱{{ number_format($billing->prelims_due, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Midterms Due:</strong></p>
                                    <p class="text-dark">₱{{ number_format($billing->midterms_due, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Pre-Finals Due:</strong></p>
                                    <p class="text-dark">₱{{ number_format($billing->prefinals_due, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Finals Due:</strong></p>
                                    <p class="text-dark">₱{{ number_format($billing->finals_due, 2) }}</p>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            <div class="col-12">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Payment Status:</strong></p>
                                    <p>
                                        @if ($billing->is_full_payment)
                                            <span class="badge bg-success p-2">Fully Paid</span>
                                        @elseif ($billing->initial_payment > 0)
                                            <span class="badge bg-warning p-2">Partial Payment</span>
                                        @else
                                            <span class="badge bg-secondary p-2">No Payment Yet</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Payment
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i> Close
                    </button>
                </div>
            </form>
            @else
                <div class="modal-body p-4">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        No billing information found for the current active school year ({{ $activeSchoolYear->name ?? 'N/A' }} - {{ $activeSchoolYear->semester ?? 'N/A' }}).
                    </div>
                    
                    <!-- At least show basic student info -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-info-circle me-2"></i>Basic Information
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Student ID:</strong></p>
                                    <p class="text-dark">{{ strtoupper($enrollment->student_id) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Full Name:</strong></p>
                                    <p class="text-dark">{{ strtoupper($enrollment->admission->full_name ?? 'N/A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i> Close
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <script>
                    $(document).ready(function() {
                        // Student search functionality
                        $('#searchStudentBtn').click(function() {
                            let query = $('#student_search').val().trim();
                            if (!query) {
                                alert('Please enter a student ID or name to search.');
                                return;
                            }

                            $('#searchLoading').show();
                            $('#studentInfoContainer').hide();
                            $('#studentInfo').html('');
                            $('#enrollmentWarnings').html('');
                            $('#nextBtn').prop('disabled', true);

                            $.ajax({
                                url: '{{ route('search.student') }}',
                                method: 'GET',
                                data: {
                                    query: query
                                },
                                success: function(data) {
                                    $('#searchLoading').hide();

                                    if (data.length === 0) {
                                        $('#studentInfo').html(
                                            '<div class="alert alert-warning">No students found.</div>');
                                        $('#studentInfoContainer').show();
                                        return;
                                    }

                                    let studentObj = data[0];
                                    let student = studentObj.student;

                                    // Store student ID in hidden field for form submission
                                    $('#student_id_input').val(student.student_id);

                                    let html = `
                    <p><strong>Student ID:</strong> ${student.student_id}</p>
                    <p><strong>Name:</strong> ${student.first_name} ${student.middle_name || ''} ${student.last_name}</p>
                    <p><strong>Current Program:</strong> ${student.program ? student.program.name : 'N/A'}</p>
                `;

                                    $('#studentInfo').html(html);

                                    let warnings = [];
                                    let canProceed = true;

                                    if (studentObj.has_unpaid_balance) {
                                        warnings.push(
                                            '<div class="alert alert-danger">Student has unpaid balance.</div>'
                                        );
                                        canProceed = false;
                                    }

                                    if (studentObj.already_enrolled) {
                                        warnings.push(
                                            '<div class="alert alert-danger">Student is already enrolled for current term.</div>'
                                        );
                                        canProceed = false;
                                    }

                                    if (studentObj.has_failing_grades) {
                                        warnings.push(
                                            '<div class="alert alert-danger">Student has failing grades.</div>'
                                        );
                                        canProceed = false;
                                    }

                                    $('#enrollmentWarnings').html(warnings.join(''));
                                    $('#nextBtn').prop('disabled', !canProceed);
                                    $('#studentInfoContainer').show();
                                },
                                error: function(xhr) {
                                    $('#searchLoading').hide();
                                    alert('An error occurred while searching. Please try again.');
                                    console.error('Search error:', xhr.responseText);
                                }
                            });
                        });

                        // Tab navigation and progress control
                        $('#nextBtn').click(function() {
                            const currentTab = $('.tab-pane.active');
                            const nextTab = currentTab.next('.tab-pane');

                            if (currentTab.attr('id') === 'step1') {
                                // Validate student is selected before proceeding
                                if (!$('#student_id_input').val()) {
                                    alert('Please search and select a valid student first.');
                                    return;
                                }

                                // Move to step 2
                                currentTab.removeClass('show active');
                                nextTab.addClass('show active');
                                $('#step1-tab').removeClass('active').addClass('disabled');
                                $('#step2-tab').addClass('active').removeClass('disabled');

                                // Update UI
                                $('#progressBar').css('width', '100%').text('Step 2 of 2');
                                $('#prevBtn').show();
                                $('#nextBtn').hide();
                                $('#submitBtn').show();
                            }
                        });

                        $('#prevBtn').click(function() {
                            const currentTab = $('.tab-pane.active');
                            const prevTab = currentTab.prev('.tab-pane');

                            // Move back to step 1
                            currentTab.removeClass('show active');
                            prevTab.addClass('show active');
                            $('#step2-tab').removeClass('active').addClass('disabled');
                            $('#step1-tab').addClass('active').removeClass('disabled');

                            // Update UI
                            $('#progressBar').css('width', '50%').text('Step 1 of 2');
                            $('#prevBtn').hide();
                            $('#submitBtn').hide();
                            $('#nextBtn').show();
                        });


                        // Form submission handler
                        $('#admissionForm').submit(function(e) {
                            // Validate course mapping is selected
                            if (!$('#course_mapping_id').val()) {
                                e.preventDefault();
                                alert('Please select a course mapping before submitting.');
                                return false;
                            }

                            // You can add additional validation here if needed
                            return true;
                        });
                    });

                    // Course mapping selection handler
                    $('#course_mapping_id').change(function() {
                        const selectedOption = $(this).find('option:selected');
                        const mappingId = selectedOption.val();

                        if (!mappingId) {
                            $('#displayMappingId').text('-');
                            $('#totalUnitsContainer').hide();
                            $('#tuitionFeeContainer').hide();
                            $('#feeCalculationContainer').hide();
                            return;
                        }

                        // Show loading state
                        $('#totalUnitsContainer').html('Calculating units...').show();
                        $('#tuitionFeeContainer').html('Calculating fee...').show();
                        $('#feeCalculationContainer').hide();

                        // Get the mapping details from data attributes
                        const programId = selectedOption.data('program');
                        const yearLevelId = selectedOption.data('year');
                        const semesterId = selectedOption.data('semester');
                        const effectiveSY = selectedOption.data('sy');

                        // Update displayed mapping ID
                        $('#displayMappingId').text(mappingId);

                        // AJAX call to calculate total units and tuition fee
                        $.ajax({
                            url: '{{ route('calculate.tuition.fee') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                program_id: programId,
                                year_level_id: yearLevelId,
                                semester_id: semesterId,
                                effective_sy: effectiveSY
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Display total units
                                    $('#totalUnitsValue').text(response.total_units);
                                    $('#totalUnitsContainer').show();

                                    // Display tuition fee
                                    $('#tuitionFeeValue').text('₱' + response.tuition_fee.toLocaleString());
                                    $('#tuitionFeeContainer').show();

                                    // Display calculation details
                                    $('#feeCalculationDetails').html(`
                    ${response.total_units} units × ₱${response.unit_price.toLocaleString()} 
                    (current unit price) = ₱${response.tuition_fee.toLocaleString()}
                `);
                                    $('#feeCalculationContainer').show();

                                    // Store the calculated fee in a hidden field if needed
                                    $('#tuition_fee_input').val(response.tuition_fee);
                                } else {
                                    alert('Error calculating tuition fee: ' + response.message);
                                }
                            },
                            error: function(xhr) {
                                console.error('Error:', xhr.responseText);
                                alert('An error occurred while calculating the tuition fee.');
                            }
                        });
                    });
                </script>



            </div>


        </div>
        <!-- End of Main Content -->

        @include('layouts.footer')


    </div>
    <!-- End of Content Wrapper -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- REQUIRED for DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        $('#miscFees').DataTable({
            responsive: true,
            pageLength: 10
        });
    });
</script>



<script>
    document.getElementById('course_mapping_id').addEventListener('change', function() {
        const mappingId = this.value;

        if (!mappingId) {
            document.getElementById('tuition_fee').value = '';
            return;
        }

        fetch('{{ route('calculate.tuition.fee') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    course_mapping_id: mappingId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.tuition_fee !== undefined) {
                    document.getElementById('tuition_fee').value = data.tuition_fee;
                } else {
                    document.getElementById('tuition_fee').value = '';
                    console.error(data.error);
                }
            })
            .catch(error => {
                console.error('Error fetching tuition fee:', error);
            });
    });
</script>
