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
                    <h1 class="h3 mb-0 text-gray-800">Transferee Enrollment</h1>


                    <button class="btn btn-primary" data-toggle="modal" data-target="#admissionFormModal">
                        Open Admission Form
                    </button>

                </div>
                <div class="modal fade" id="admissionFormModal" tabindex="-1" aria-labelledby="admissionFormModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admissions.store') }}" id="admissionForm">
                                @csrf
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Admission Form</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="school_year"
                                        value="{{ $activeSchoolYear ? $activeSchoolYear->name : '' }}">
                                    <input type="hidden" name="semester"
                                        value="{{ $activeSchoolYear ? $activeSchoolYear->semester : '' }}">

                                    <!-- Progress Indicator -->
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: 20%;" aria-valuenow="20"
                                            aria-valuemin="0" aria-valuemax="100">Step 1 of 5</div>
                                    </div>

                                    <ul class="nav nav-tabs mb-3" id="formTabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1"
                                                role="tab" aria-controls="step1" aria-selected="true">
                                                <i class="fas fa-user mr-1"></i> Personal Info
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2"
                                                role="tab" aria-controls="step2" aria-selected="false">
                                                <i class="fas fa-users mr-1"></i> Parents
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="step3-tab" data-toggle="tab" href="#step3"
                                                role="tab" aria-controls="step3" aria-selected="false">
                                                <i class="fas fa-info-circle mr-1"></i> Other Details
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="step4-tab" data-toggle="tab" href="#step4"
                                                role="tab" aria-controls="step4" aria-selected="false">
                                                <i class="fas fa-graduation-cap mr-1"></i> Admission
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="step5-tab" data-toggle="tab" href="#step5"
                                                role="tab" aria-controls="step5" aria-selected="false">
                                                <i class="fas fa-book mr-1"></i> Education
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <!-- Step 1: Personal Info -->
                                      <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
    <div class="alert alert-info mb-3">
        <i class="fas fa-info-circle mr-2"></i>Fields marked with <span class="text-danger">*</span> are required.
    </div>
    <div class="row g-3">
        <!-- Name Fields -->
        <div class="col-md-4">
            <label for="last_name">Last Name <span class="text-danger">*</span></label>
            <input type="text" id="last_name" name="last_name" class="form-control" required placeholder="e.g., Dela Cruz">
        </div>
        <div class="col-md-4">
            <label for="first_name">First Name <span class="text-danger">*</span></label>
            <input type="text" id="first_name" name="first_name" class="form-control" required placeholder="e.g., Juan">
        </div>
        <div class="col-md-4">
            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" class="form-control" placeholder="e.g., Reyes">
        </div>
        <!-- Address Fields with Cascading Dropdowns -->
      <div class="form-group">
    <label for="region">Region</label>
    <select id="region" name="region" class="form-control">
        <option value="">Select Region</option>
        @foreach ($regions as $region)
            <option value="{{ $region->regCode }}">{{ $region->regDesc }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="province">Province</label>
    <select id="province" name="province" class="form-control" disabled>
        <option value="">Select Province</option>
    </select>
</div>
<div class="form-group">
    <label for="city">City/Municipality</label>
    <select id="city" name="city" class="form-control" disabled>
        <option value="">Select City/Municipality</option>
    </select>
</div>
<div class="form-group">
    <label for="barangay">Barangay</label>
    <select id="barangay" name="barangay" class="form-control" disabled>
        <option value="">Select Barangay</option>
    </select>
</div>

        <!-- Additional Address and Contact Fields -->
        <div class="col-md-6">
            <label for="zip_code">Zip Code</label>
            <input type="text" id="zip_code" name="zip_code" class="form-control" placeholder="e.g., 1000">
        </div>
        <div class="col-md-6">
            <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
            <input type="tel" id="contact_number" name="contact_number" class="form-control" required placeholder="e.g., 09123456789">
        </div>
        <div class="col-12">
            <label for="email">Email Address <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" class="form-control" required placeholder="e.g., juan.delacruz@example.com">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const region = document.getElementById('region');
    const province = document.getElementById('province');
    const city = document.getElementById('city');
    const barangay = document.getElementById('barangay');

    region.addEventListener('change', () => {
        fetch(`/provinces/${region.value}`)
            .then(response => response.json())
            .then(data => {
                province.innerHTML = '<option value="">Select Province</option>';
                city.innerHTML = '<option value="">Select City/Municipality</option>';
                barangay.innerHTML = '<option value="">Select Barangay</option>';
                city.disabled = true;
                barangay.disabled = true;

                data.forEach(provinceItem => {
                    province.innerHTML += `<option value="${provinceItem.provCode}">${provinceItem.provDesc}</option>`;
                });
                province.disabled = false;
            });
    });

    province.addEventListener('change', () => {
        fetch(`/cities/${province.value}`)
            .then(response => response.json())
            .then(data => {
                city.innerHTML = '<option value="">Select City/Municipality</option>';
                barangay.innerHTML = '<option value="">Select Barangay</option>';
                barangay.disabled = true;

                data.forEach(cityItem => {
                    city.innerHTML += `<option value="${cityItem.citymunCode}">${cityItem.citymunDesc}</option>`;
                });
                city.disabled = false;
            });
    });

    city.addEventListener('change', () => {
        fetch(`/barangays/${city.value}`)
            .then(response => response.json())
            .then(data => {
                barangay.innerHTML = '<option value="">Select Barangay</option>';
                data.forEach(brgyItem => {
                    barangay.innerHTML += `<option value="${brgyItem.brgyCode}">${brgyItem.brgyDesc}</option>`;
                });
                barangay.disabled = false;
            });
    });
});
</script>


                                        <!-- Step 2: Parents Info -->
                                        <div class="tab-pane fade" id="step2" role="tabpanel"
                                            aria-labelledby="step2-tab">
                                            <div class="alert alert-info mb-3">
                                                <i class="fas fa-info-circle mr-2"></i>Please provide your parents'
                                                information.
                                            </div>

                                            <h5 class="mb-3"><i class="fas fa-male mr-2"></i>Father's Information</h5>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="father_last_name">Last Name</label>
                                                    <input type="text" id="father_last_name" name="father_last_name"
                                                        class="form-control" placeholder="e.g., Dela Cruz">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="father_first_name">First Name</label>
                                                    <input type="text" id="father_first_name" name="father_first_name"
                                                        class="form-control" placeholder="e.g., Pedro">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="father_middle_name">Middle Name</label>
                                                    <input type="text" id="father_middle_name"
                                                        name="father_middle_name" class="form-control"
                                                        placeholder="e.g., Santos">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="father_contact">Contact Number</label>
                                                    <input type="tel" id="father_contact" name="father_contact"
                                                        class="form-control" placeholder="e.g., 09123456789">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="father_profession">Profession</label>
                                                    <input type="text" id="father_profession" name="father_profession"
                                                        class="form-control" placeholder="e.g., Engineer">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="father_industry">Industry</label>
                                                    <input type="text" id="father_industry" name="father_industry"
                                                        class="form-control" placeholder="e.g., Construction">
                                                </div>
                                            </div>

                                            <h5 class="mt-4 mb-3"><i class="fas fa-female mr-2"></i>Mother's Information
                                            </h5>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="mother_last_name">Last Name</label>
                                                    <input type="text" id="mother_last_name" name="mother_last_name"
                                                        class="form-control" placeholder="e.g., Dela Cruz">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="mother_first_name">First Name</label>
                                                    <input type="text" id="mother_first_name" name="mother_first_name"
                                                        class="form-control" placeholder="e.g., Maria">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="mother_middle_name">Middle Name</label>
                                                    <input type="text" id="mother_middle_name"
                                                        name="mother_middle_name" class="form-control"
                                                        placeholder="e.g., Reyes">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="mother_contact">Contact Number</label>
                                                    <input type="tel" id="mother_contact" name="mother_contact"
                                                        class="form-control" placeholder="e.g., 09123456789">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="mother_profession">Profession</label>
                                                    <input type="text" id="mother_profession" name="mother_profession"
                                                        class="form-control" placeholder="e.g., Teacher">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="mother_industry">Industry</label>
                                                    <input type="text" id="mother_industry" name="mother_industry"
                                                        class="form-control" placeholder="e.g., Education">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 3: Other Personal Details -->
                                        <div class="tab-pane fade" id="step3" role="tabpanel"
                                            aria-labelledby="step3-tab">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="gender">Gender</label>
                                                    <select id="gender" name="gender" class="form-control">
                                                        <option value="" selected disabled>Select Gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="birthdate">Birthdate</label>
                                                    <input type="date" id="birthdate" name="birthdate"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="birthplace">Birthplace</label>
                                                    <input type="text" id="birthplace" name="birthplace"
                                                        class="form-control" placeholder="e.g., Manila City">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="citizenship">Citizenship</label>
                                                    <input type="text" id="citizenship" name="citizenship"
                                                        class="form-control" placeholder="e.g., Filipino">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="religion">Religion</label>
                                                    <input type="text" id="religion" name="religion"
                                                        class="form-control" placeholder="e.g., Roman Catholic">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="civil_status">Civil Status</label>
                                                    <select id="civil_status" name="civil_status" class="form-control">
                                                        <option value="" selected disabled>Select Status</option>
                                                        <option value="single">Single</option>
                                                        <option value="married">Married</option>
                                                        <option value="separated">Separated</option>
                                                        <option value="widowed">Widowed</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 4: Admission Info -->
                                        <div class="tab-pane fade" id="step4" role="tabpanel"
                                            aria-labelledby="step4-tab">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="course_mapping_id">Course Mapping <span
                                                            class="text-danger">*</span></label>
                                                    <select id="course_mapping_id" name="course_mapping_id"
                                                        class="form-control">
                                                        <option value="" selected disabled>Choose Mapping</option>
                                                        @foreach ($courseMappings as $mapping)
                                                            @if ($mapping->program && $mapping->yearLevel)
                                                                <option value="{{ $mapping->id }}">
                                                                    {{ $mapping->program->name }} -
                                                                    {{ $mapping->yearLevel->name }}
                                                                    ({{ $mapping->effective_sy }})
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>

                                                    <div id="totalUnitsContainer" class="alert alert-info mt-3"
                                                        style="display:none;">
                                                        Total Units for Selected Mapping: <strong
                                                            id="totalUnitsValue"></strong>
                                                    </div>
                                                    <!-- Tuition Fee will appear here dynamically -->
                                                    <div id="tuitionFeeContainer" class="alert alert-success mt-2"
                                                        style="display:none;"></div>

                                                    <input type="hidden" name="tuition_fee" id="tuition_fee_input" />



                                                    <script>
                                                  $('#course_mapping_id').on('change', function() {
    let mappingId = $(this).val();

    if (!mappingId) {
        $('#totalUnitsContainer').hide();
        $('#tuitionFeeContainer').hide();
        return;
    }

    $.ajax({
        url: '{{ route('getMappingUnits') }}',
        type: 'POST',
        data: {
            mapping_id: mappingId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            let totalUnits = response.total_units;
            let tuitionFee = response.tuition_fee;
            
            // Check if the mapping has NSTP course
            if (response.courses && Array.isArray(response.courses)) {
                let hasNSTP = response.courses.some(course => 
                    course.name && course.name.toLowerCase().includes('nstp')
                );
                
                if (hasNSTP) {
                    totalUnits = totalUnits / 2;
                    tuitionFee = tuitionFee / 2;
                }
            }

            $('#totalUnitsValue').text(totalUnits);
            $('#totalUnitsContainer').show();

            $('#tuitionFeeContainer').html('Tuition Fee: <strong>₱' + tuitionFee.toFixed(2) + '</strong>').show();

            // Set the hidden input's value to the tuition fee number (no formatting)
            $('#tuition_fee_input').val(tuitionFee);
        },
        error: function() {
            $('#totalUnitsContainer').hide();
            $('#tuitionFeeContainer').hide();
        }
    });
});
                                                    </script>


                                                </div>

                                                <div class="col-md-6">
                                                    <label for="major">Major</label>
                                                    <input type="text" id="major" name="major"
                                                        class="form-control" placeholder="e.g., N/A if None">
                                                </div>
                                            </div>

                                            <div id="manualCourseSelection" style="display: none;" class="mt-3">
                                                <label for="course_ids">Select Courses (for
                                                    irregular/transferee/returnee)</label>
                                                <select id="course_ids" name="course_ids[]" class="form-control"
                                                    multiple>
                                                    @foreach ($allCourses as $course)
                                                        <option value="{{ $course->id }}">{{ $course->code }} -
                                                            {{ $course->title }}</option>
                                                    @endforeach
                                                </select>

                                                <!-- Selected Courses List -->
                                                <div id="selectedCoursesContainer" class="mt-3">
                                                    <h5>Selected Courses:</h5>
                                                    <ul id="selectedCoursesList"
                                                        style="list-style-type:none; padding-left:0;"></ul>
                                                </div>
                                            </div>

                                         <div class="mt-3">
    <label class="form-label">Admission Status <span class="text-danger">*</span></label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="admission_status" value="highschool" id="highschool" required checked>
        <label class="form-check-label" for="highschool">High School Graduate</label>
     <div class="col-md-6 mt-2">
                                                    <label for="lrn">LRN:</label>
                                                    <input type="text" id="lrn" name="lrn"
                                                        class="form-control" placeholder="e.g., 2025-12345">
                                                </div>
    </div>
</div>



                                            <div class="row g-3 mt-2" id="transfereeFields" style="display: none;">
                                                <div class="col-md-6">
                                                    <label for="student_no">Student No. (if transferee)</label>
                                                    <input type="text" id="student_no" name="student_no"
                                                        class="form-control" placeholder="e.g., 2020-12345">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="admission_year">Year When Admitted</label>
                                                    <input type="text" id="admission_year" name="admission_year"
                                                        class="form-control" placeholder="e.g., 2023">
                                                </div>
                                            </div>

                                            <div class="row g-3 mt-2">
                                                <div class="col-md-6">
                                                    <label for="scholarship">Scholarship</label>
                                                    <select id="scholarship" name="scholarship" class="form-control">
                                                        <option value="" selected disabled>Select Scholarship
                                                        </option>
                                                        @foreach ($scholarships as $scholarship)
                                                            <option value="{{ $scholarship->id }}">
                                                                {{ $scholarship->name }}</option>
                                                        @endforeach
                                                        <option value="none">None</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="previous_school">Previous School (if any)</label>
                                                    <input type="text" id="previous_school" name="previous_school"
                                                        class="form-control" placeholder="e.g., ABC University">
                                                </div>
                                                <div class="col-12">
                                                    <label for="previous_school_address">School Address (if any)</label>
                                                    <input type="text" id="previous_school_address"
                                                        name="previous_school_address" class="form-control"
                                                        placeholder="e.g., 123 College Ave, City">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 5: Education History -->
                                        <div class="tab-pane fade" id="step5" role="tabpanel"
                                            aria-labelledby="step5-tab">
                                            <div class="alert alert-info mb-3">
                                                <i class="fas fa-info-circle mr-2"></i>Please provide your educational
                                                background.
                                            </div>

                                            <h5 class="mb-3"><i class="fas fa-school mr-2"></i>Elementary Education</h5>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="elementary_school">School Name</label>
                                                    <input type="text" id="elementary_school" name="elementary_school"
                                                        class="form-control" placeholder="e.g., ABC Elementary School">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="elementary_address">School Address</label>
                                                    <input type="text" id="elementary_address"
                                                        name="elementary_address" class="form-control"
                                                        placeholder="e.g., 123 School St, City">
                                                </div>
                                            </div>

                                            <h5 class="mt-4 mb-3"><i class="fas fa-school mr-2"></i>Secondary Education
                                            </h5>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="secondary_school">School Name</label>
                                                    <input type="text" id="secondary_school" name="secondary_school"
                                                        class="form-control" placeholder="e.g., XYZ High School">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="secondary_address">School Address</label>
                                                    <input type="text" id="secondary_address" name="secondary_address"
                                                        class="form-control"
                                                        placeholder="e.g., 456 High School Ave, City">
                                                </div>
                                            </div>

                                            <div class="row g-3 mt-2">
                                                <div class="col-12">
                                                    <label for="honors">Honors Received</label>
                                                    <input type="text" id="honors" name="honors"
                                                        class="form-control"
                                                        placeholder="e.g., With Honors, Best in Math, Valedictorian">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="prevBtn" disabled>
                                        <i class="fas fa-arrow-left mr-1"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary" id="nextBtn">
                                        Next <i class="fas fa-arrow-right ml-1"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submitBtn"
                                        style="display: none;">
                                        <i class="fas fa-check mr-1"></i> Submit Application
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
                                            @foreach ($admissions as $admission)
                                                <tr>
                                                    <td>{{ strtoupper($admission->student_id) }}</td>
                                                    <td>{{ strtoupper($admission->first_name . ' ' . $admission->middle_name . ' ' . $admission->last_name) }}
                                                    </td>
                                                    <td>{{ $admission->courseMapping->combination_label ?? 'N/A' }}</td>

                                                    <td>{{ ucfirst($admission->status) }}</td>
                                                    <td>{{ $admission->email }}</td>
                                           
<td class="text-center align-middle">
    @php
        $initialPayment = $admission->billing->initial_payment ?? 0;
    @endphp

    <div class="d-flex justify-content-center align-items-center gap-1" style="min-width: 150px;">
        <!-- View Button with Eye Icon -->
        <button type="button" 
                class="btn btn-info btn-sm position-relative"
                data-bs-toggle="modal"
                data-bs-target="#studentModal{{ $admission->student_id }}"
                title="View Student">
            <i class="fas fa-eye"></i>

            @if($initialPayment <= 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                      data-bs-toggle="tooltip"
                      title="This student hasn't made an initial payment yet.">
                    Warning
                </span>
            @endif
        </button>

        <!-- Edit Button -->
        <a href="{{ route('admissions.edit', $admission->student_id) }}"
           class="btn btn-warning btn-sm"
           title="Edit Student">
            <i class="fas fa-edit"></i>
        </a>

        <!-- Print COR Button -->
        <a href="{{ route('admissions.printCOR', $admission->student_id) }}"
           target="_blank" rel="noopener"
           class="btn btn-primary btn-sm"
           title="Print COR">
            <i class="fas fa-print"></i>
        </a>
    </div>

    <!-- Initialize Bootstrap tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</td>

                                                </tr>

                                          <!-- Student Information Modal - Larger Size -->
<div class="modal fade" id="studentModal{{ $admission->student_id }}" tabindex="-1"
    aria-labelledby="studentModalLabel{{ $admission->student_id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <!-- Changed to modal-lg for larger size -->
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="studentModalLabel{{ $admission->student_id }}">
                    <i class="bi bi-person-vcard me-2"></i>Student Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
@if($admission->billing)
            {{-- Form starts here, targeting your update route --}}
            <form method="POST" action="{{ route('billing.updateInitialPayment', $admission->billing->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-body p-4"> <!-- Added more padding -->
                    <!-- Basic Information Section (unchanged, just display) -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-info-circle me-2"></i>Basic Information
                        </h6>
                        <div class="row g-3">
                            <!-- Display info only -->
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Student ID:</strong></p>
                                    <p class="text-dark">{{ $admission->student_id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1"><strong>Full Name:</strong></p>
                                    <p class="text-dark">
                                        {{ Str::title("{$admission->last_name}, {$admission->first_name} {$admission->middle_name}") }}
                                    </p>
                                </div>
                            </div>
                            <!-- Other basic info here similarly... -->
                        </div>
                    </div>

                    <!-- Billing Information Section -->
                    @if ($admission->billing)
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-cash-coin me-2"></i>Financial Information
                            </h6>

                            <!-- Display all billing info readonly -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-1"><strong>School Year:</strong></p>
                                        <p class="text-dark">{{ $admission->billing->school_year }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-1"><strong>Semester:</strong></p>
                                        <p class="text-dark">{{ $admission->billing->semester }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-1"><strong>Tuition Fee:</strong></p>
                                        <p class="text-dark">₱{{ number_format($admission->billing->tuition_fee, 2) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-1"><strong>Discount:</strong></p>
                                        <p class="text-dark">{{ $admission->billing->discount }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-primary mt-3 mb-4">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="p-2">
                                            <p class="mb-1"><strong>Total Assessment:</strong></p>
                                            <h5 class="fw-bold mb-0">
                                                ₱{{ number_format($admission->billing->total_assessment, 2) }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-2">
                                            <p class="mb-1"><strong>Balance Due:</strong></p>
                                            <h5 class="fw-bold mb-0">
                                                ₱{{ number_format($admission->billing->balance_due, 2) }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold mt-4 mb-3">
                                <i class="bi bi-calendar-check me-2"></i>Payment Schedule
                            </h6>
                          <div class="row g-3">
                                <!-- Full width Initial Payment -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="initial_payment{{ $admission->id }}" class="form-label"><strong>Initial Payment</strong></label>
                                        <input 
                                            placeholder="0.00" 
                                            type="number" 
                                            name="initial_payment" 
                                            step="0.01" 
                                            min="0"
                                            class="form-control"
                                            id="initial_payment{{ $admission->id }}"
                                            value="{{ old('initial_payment', ($admission->billing->initial_payment ?? 0) > 0 ? $admission->billing->initial_payment : '') }}">
                                    </div>
                                </div>

                                <!-- Two Columns: Prelims & Midterms -->
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-1"><strong>Prelims Due:</strong></p>
                                        <p class="text-dark">₱{{ number_format($admission->billing->prelims_due, 2) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-1"><strong>Midterms Due:</strong></p>
                                        <p class="text-dark">₱{{ number_format($admission->billing->midterms_due, 2) }}</p>
                                    </div>
                                </div>

                                <!-- Two Columns: Pre-Finals & Finals -->
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-1"><strong>Pre-Finals Due:</strong></p>
                                        <p class="text-dark">₱{{ number_format($admission->billing->prefinals_due, 2) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-1"><strong>Finals Due:</strong></p>
                                        <p class="text-dark">₱{{ number_format($admission->billing->finals_due, 2) }}</p>
                                    </div>
                                </div>

                                <!-- Payment Status -->
                                <div class="col-12">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-1"><strong>Payment Status:</strong></p>
                                        <p>
                                            @php
                                                $initial = $admission->billing->initial_payment ?? 0;
                                                $balance = $admission->billing->balance_due ?? 0;
                                            @endphp

                                            @if ($balance == 0)
                                                <span class="badge bg-success p-2">Fully Paid</span>
                                            @elseif ($initial > 0 && $initial < $balance)
                                                <span class="badge bg-warning p-2">Installment</span>
                                            @else
                                                <span class="badge bg-secondary p-2">—</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @else
                        <div class="alert alert-warning mt-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            No billing information available for this student.
                        </div>
                    @endif
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
    <p><em>No billing record found for this admission.</em></p>
@endif
            {{-- Form ends here --}}
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

<!-- Font Awesome for icons (include in your head tag) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Tab navigation variables
    const formTabs = document.querySelectorAll('#formTabs .nav-link');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.querySelector('.progress-bar');
    let currentTab = 0;

    // Admission info elements
    const admissionStatusRadios = document.querySelectorAll('input[name="admission_status"]');
    const transfereeFields = document.getElementById('transfereeFields');
    const manualCourseSelection = document.getElementById('manualCourseSelection');

    // Course selection elements
    const courseSelect = document.getElementById('course_ids');
    const selectedCoursesList = document.getElementById('selectedCoursesList');

    // Track selected courses
    let selectedCourses = [];

    // Define required fields for each step (0-indexed)
  const requiredFields = [
    // Step 1: Personal Info
    ['last_name', 'first_name', 'contact_number', 'email', 'region', 'province', 'city', 'barangay'],
    
    // Step 2: Parents Info (optional)
    [],
    
    // Step 3: Other Personal Details
    ['gender', 'birthdate', 'citizenship'],
    
    // Step 4: Admission Info
    ['course_mapping_id', 'admission_status' ,'major' ,'previous_school'],
    
    // Step 5: Education History
    ['secondary_school', 'secondary_address']
];

    // Initialize UI
    updateTabs();
    if (manualCourseSelection) manualCourseSelection.style.display = 'none';
    if (transfereeFields) transfereeFields.style.display = 'none';
    updateSelectedCoursesDisplay();

    // Tab navigation functions
    nextBtn.addEventListener('click', function() {
        if (validateCurrentStep(currentTab)) {
            currentTab++;
            updateTabs();
        }
    });

    prevBtn.addEventListener('click', function() {
        currentTab--;
        updateTabs();
    });

    function updateTabs() {
        formTabs.forEach((tab, index) => {
            const tabPane = document.querySelector(tab.getAttribute('href'));
            if (index === currentTab) {
                tab.classList.add('active');
                tabPane.classList.add('show', 'active');
            } else {
                tab.classList.remove('active');
                tabPane.classList.remove('show', 'active');
            }
        });

        prevBtn.disabled = currentTab === 0;
        nextBtn.style.display = currentTab === formTabs.length - 1 ? 'none' : 'block';
        submitBtn.style.display = currentTab === formTabs.length - 1 ? 'block' : 'none';

        const progress = ((currentTab + 1) / formTabs.length) * 100;
        progressBar.style.width = `${progress}%`;
        progressBar.textContent = `Step ${currentTab + 1} of ${formTabs.length}`;
        progressBar.setAttribute('aria-valuenow', progress);
    }

  // Admission status change handler
admissionStatusRadios.forEach(radio => {
    // Check the default selected radio button on page load
    if (radio.checked) {
        handleAdmissionStatusChange(radio.value);
    }

    radio.addEventListener('change', function() {
        handleAdmissionStatusChange(this.value);
    });
});

// Function to handle admission status changes
function handleAdmissionStatusChange(value) {
    // Show/hide transferee fields
    if (transfereeFields) {
        transfereeFields.style.display = value === 'transferee' ? 'block' : 'none';
    }

    // Show/hide manual course selection
    if (manualCourseSelection) {
        const showCourseSelection = (value === 'transferee' || value === 'returnee');
        manualCourseSelection.style.display = showCourseSelection ? 'block' : 'none';
    }

    // Clear selections when switching away from irregular status
    if (!showCourseSelection) {
        clearCourseSelections();
    }
}


    // Course selection handling
    if (courseSelect && selectedCoursesList) {
        courseSelect.addEventListener('change', function() {
            // Get newly selected options that aren't already tracked
            const newSelections = Array.from(this.selectedOptions)
                .filter(option => !selectedCourses.some(c => c.value === option.value));

            // Add new selections to our array
            selectedCourses = selectedCourses.concat(newSelections.map(option => ({
                value: option.value,
                text: option.text
            })));

            updateSelectedCoursesDisplay();
        });
    }

    function updateSelectedCoursesDisplay() {
        if (!selectedCoursesList) return;
        
        selectedCoursesList.innerHTML = '';

        if (selectedCourses.length === 0) {
            selectedCoursesList.innerHTML = '<li class="text-muted">No courses selected</li>';
            return;
        }

        selectedCourses.forEach(course => {
            const li = document.createElement('li');
            li.className = 'd-flex justify-content-between align-items-center mb-2';
            li.innerHTML = `
                <span>${course.text}</span>
                <button type="button" class="btn btn-sm btn-outline-danger remove-course" 
                        data-value="${course.value}" title="Remove course">
                    <i class="fas fa-times"></i>
                </button>
            `;
            selectedCoursesList.appendChild(li);
        });

        // Add event listeners to remove buttons
        document.querySelectorAll('.remove-course').forEach(button => {
            button.addEventListener('click', function() {
                const valueToRemove = this.getAttribute('data-value');

                // Remove from our array
                selectedCourses = selectedCourses.filter(c => c.value !== valueToRemove);

                // Update the select element
                const option = Array.from(courseSelect.options)
                    .find(opt => opt.value === valueToRemove);
                if (option) option.selected = false;

                updateSelectedCoursesDisplay();
            });
        });
    }

    function clearCourseSelections() {
        selectedCourses = [];
        if (courseSelect) {
            Array.from(courseSelect.options).forEach(opt => opt.selected = false);
        }
        updateSelectedCoursesDisplay();
    }

    // Form submission handler
    document.getElementById('admissionForm').addEventListener('submit', function(e) {
        // Verify courses are selected if manual selection is shown
        if (manualCourseSelection && manualCourseSelection.style.display === 'block' && selectedCourses.length === 0) {
            e.preventDefault();
            alert('Please select at least one course');
            return;
        }

        // Sync selections with the select element before submission
        if (courseSelect) {
            Array.from(courseSelect.options).forEach(opt => opt.selected = false);
            selectedCourses.forEach(course => {
                const option = Array.from(courseSelect.options)
                    .find(opt => opt.value === course.value);
                if (option) option.selected = true;
            });
        }

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Submitting...';
        submitBtn.disabled = true;
    });

    function validateCurrentStep(step) {
        const fields = requiredFields[step];
        let isValid = true;
        let firstInvalidField = null;
        
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            }
        });

        // Special validation for admission status in step 4
        if (step === 3) { // Step 4 is index 3
            const admissionStatusSelected = document.querySelector('input[name="admission_status"]:checked');
            if (!admissionStatusSelected && requiredFields[3].includes('admission_status')) {
                isValid = false;
                admissionStatusRadios.forEach(radio => {
                    radio.closest('.form-check').classList.add('is-invalid');
                });
            } else {
                admissionStatusRadios.forEach(radio => {
                    radio.closest('.form-check').classList.remove('is-invalid');
                });
            }
        }

        if (!isValid) {
            alert('Please fill in all required fields before proceeding.');
            if (firstInvalidField) {
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidField.focus();
            }
        }

        return isValid;
    }

    // Add input event listeners to clear validation errors when typing
    requiredFields.flat().forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        }
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
