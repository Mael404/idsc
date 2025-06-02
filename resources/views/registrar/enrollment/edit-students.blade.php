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
                    <h1 class="h3 mb-0 text-gray-800">Edit Student Info</h1>

                    <button class="btn btn-primary" data-toggle="modal" data-target="#admissionFormModal">
                        Download Report
                    </button>
                </div>

                <!-- ✏️ Edit Admission Form -->
                <form action="{{ route('admissions.update', $admission->student_id) }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12">
                            <!-- Form Navigation Tabs -->
                            <ul class="nav nav-tabs mb-4" id="admissionTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal"
                                        role="tab">Personal Info</a>
                                </li>
                            
                                <li class="nav-item">
                                    <a class="nav-link" id="education-tab" data-toggle="tab" href="#education"
                                        role="tab">Education</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="family-tab" data-toggle="tab" href="#family"
                                        role="tab">Family</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="admissionTabsContent">
                                <!-- Personal Information Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Personal Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="last_name" class="font-weight-bold">Last Name <span
                                                                class="text-danger">*</span></label>
                                                        <input value="{{ $admission->last_name }}" type="text"
                                                            name="last_name" id="last_name" class="form-control" required>
                                                        <div class="invalid-feedback">Please provide last name.</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="first_name" class="font-weight-bold">First Name <span
                                                                class="text-danger">*</span></label>
                                                        <input value="{{ $admission->first_name }}" type="text"
                                                            name="first_name" id="first_name" class="form-control" required>
                                                        <div class="invalid-feedback">Please provide first name.</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="middle_name">Middle Name</label>
                                                        <input value="{{ $admission->middle_name }}" type="text"
                                                            name="middle_name" id="middle_name" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="birthdate" class="font-weight-bold">Birthdate</label>
                                                        <input value="{{ $admission->birthdate }}" type="date"
                                                            name="birthdate" id="birthdate" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="birthplace">Birthplace</label>
                                                        <input value="{{ $admission->birthplace }}" type="text"
                                                            name="birthplace" id="birthplace" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="gender" class="font-weight-bold">Gender <span
                                                                class="text-danger">*</span></label>
                                                        <select name="gender" id="gender" class="form-control"
                                                            required>
                                                            <option value="">Select Gender</option>
                                                            <option value="Male"
                                                                {{ $admission->gender == 'Male' ? 'selected' : '' }}>Male
                                                            </option>
                                                            <option value="Female"
                                                                {{ $admission->gender == 'Female' ? 'selected' : '' }}>
                                                                Female</option>
                                                        </select>
                                                        <div class="invalid-feedback">Please select gender.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="citizenship">Citizenship</label>
                                                        <input value="{{ $admission->citizenship }}" type="text"
                                                            name="citizenship" id="citizenship" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="religion">Religion</label>
                                                        <input value="{{ $admission->religion }}" type="text"
                                                            name="religion" id="religion" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="civil_status">Civil Status</label>
                                                        <input value="{{ $admission->civil_status }}" type="text"
                                                            name="civil_status" id="civil_status" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact_number" class="font-weight-bold">Contact
                                                            Number <span class="text-danger">*</span></label>
                                                        <input value="{{ $admission->contact_number }}" type="text"
                                                            name="contact_number" id="contact_number"
                                                            class="form-control" required>
                                                        <div class="invalid-feedback">Please provide contact number.</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email" class="font-weight-bold">Email <span
                                                                class="text-danger">*</span></label>
                                                        <input value="{{ $admission->email }}" type="email"
                                                            name="email" id="email" class="form-control" required>
                                                        <div class="invalid-feedback">Please provide valid email.</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                    
                                <!-- Education Information Tab -->
                                <div class="tab-pane fade" id="education" role="tabpanel">
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Education Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="student_no">Student Number</label>
                                                        <input value="{{ $admission->student_no }}" type="text"
                                                            name="student_no" id="student_no" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="lrn">LRN</label>
                                                        <input value="{{ $admission->lrn }}" type="text"
                                                            name="lrn" id="lrn" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="admission_status">Admission Status</label>
                                                        <input value="{{ $admission->admission_status }}" type="text"
                                                            name="admission_status" id="admission_status"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="admission_year">Admission Year</label>
                                                        <input value="{{ $admission->admission_year }}" type="number"
                                                            name="admission_year" id="admission_year"
                                                            class="form-control" min="1900" max="2100">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="major">Major</label>
                                                        <input value="{{ $admission->major }}" type="text"
                                                            name="major" id="major" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="school_year" class="font-weight-bold">School Year
                                                            <span class="text-danger">*</span></label>
                                                        <input value="{{ $admission->school_year }}" type="text"
                                                            name="school_year" id="school_year" class="form-control"
                                                            required>
                                                        <div class="invalid-feedback">Please provide school year.</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="semester" class="font-weight-bold">Semester <span
                                                                class="text-danger">*</span></label>
                                                        <input value="{{ $admission->semester }}" type="text"
                                                            name="semester" id="semester" class="form-control" required>
                                                        <div class="invalid-feedback">Please provide semester.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="status" class="font-weight-bold">Status <span
                                                                class="text-danger">*</span></label>
                                                        <input value="{{ $admission->status }}" type="text"
                                                            name="status" id="status" class="form-control" required>
                                                        <div class="invalid-feedback">Please provide status.</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="honors">Honors</label>
                                                        <input value="{{ $admission->honors }}" type="text"
                                                            name="honors" id="honors" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="mt-4 border-bottom pb-2">Previous Schools</h5>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="previous_school">Previous School</label>
                                                        <input value="{{ $admission->previous_school }}" type="text"
                                                            name="previous_school" id="previous_school"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="previous_school_address">Previous School
                                                            Address</label>
                                                        <input value="{{ $admission->previous_school_address }}"
                                                            type="text" name="previous_school_address"
                                                            id="previous_school_address" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="elementary_school">Elementary School</label>
                                                        <input value="{{ $admission->elementary_school }}" type="text"
                                                            name="elementary_school" id="elementary_school"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="elementary_address">Elementary Address</label>
                                                        <input value="{{ $admission->elementary_address }}"
                                                            type="text" name="elementary_address"
                                                            id="elementary_address" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="secondary_school">Secondary School</label>
                                                        <input value="{{ $admission->secondary_school }}" type="text"
                                                            name="secondary_school" id="secondary_school"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="secondary_address">Secondary Address</label>
                                                        <input value="{{ $admission->secondary_address }}" type="text"
                                                            name="secondary_address" id="secondary_address"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Family Information Tab -->
                                <div class="tab-pane fade" id="family" role="tabpanel">
                                    <!-- Father's Information -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Father's Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="father_last_name">Last Name</label>
                                                        <input value="{{ $admission->father_last_name }}" type="text"
                                                            name="father_last_name" id="father_last_name"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="father_first_name">First Name</label>
                                                        <input value="{{ $admission->father_first_name }}" type="text"
                                                            name="father_first_name" id="father_first_name"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="father_middle_name">Middle Name</label>
                                                        <input value="{{ $admission->father_middle_name }}"
                                                            type="text" name="father_middle_name"
                                                            id="father_middle_name" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="father_contact">Contact Number</label>
                                                        <input value="{{ $admission->father_contact }}" type="text"
                                                            name="father_contact" id="father_contact"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="father_profession">Profession</label>
                                                        <input value="{{ $admission->father_profession }}" type="text"
                                                            name="father_profession" id="father_profession"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="father_industry">Industry</label>
                                                        <input value="{{ $admission->father_industry }}" type="text"
                                                            name="father_industry" id="father_industry"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mother's Information -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Mother's Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="mother_last_name">Last Name</label>
                                                        <input value="{{ $admission->mother_last_name }}" type="text"
                                                            name="mother_last_name" id="mother_last_name"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="mother_first_name">First Name</label>
                                                        <input value="{{ $admission->mother_first_name }}" type="text"
                                                            name="mother_first_name" id="mother_first_name"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="mother_middle_name">Middle Name</label>
                                                        <input value="{{ $admission->mother_middle_name }}"
                                                            type="text" name="mother_middle_name"
                                                            id="mother_middle_name" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="mother_contact">Contact Number</label>
                                                        <input value="{{ $admission->mother_contact }}" type="text"
                                                            name="mother_contact" id="mother_contact"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="mother_profession">Profession</label>
                                                        <input value="{{ $admission->mother_profession }}" type="text"
                                                            name="mother_profession" id="mother_profession"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="mother_industry">Industry</label>
                                                        <input value="{{ $admission->mother_industry }}" type="text"
                                                            name="mother_industry" id="mother_industry"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-actions d-flex justify-content-between border-top pt-3">
                                <a href="{{ route('admissions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Add this script for form validation and tab persistence -->
                <script>
                    // Form validation
                    (function() {
                        'use strict';
                        window.addEventListener('load', function() {
                            var forms = document.getElementsByClassName('needs-validation');
                            var validation = Array.prototype.filter.call(forms, function(form) {
                                form.addEventListener('submit', function(event) {
                                    if (form.checkValidity() === false) {
                                        event.preventDefault();
                                        event.stopPropagation();

                                        // Find the first invalid field and switch to its tab
                                        var invalidFields = form.querySelectorAll(':invalid');
                                        if (invalidFields.length > 0) {
                                            var field = invalidFields[0];
                                            var tabPane = field.closest('.tab-pane');
                                            if (tabPane) {
                                                var tabId = tabPane.id;
                                                $('.nav-tabs a[href="#' + tabId + '"]').tab('show');
                                            }
                                            field.focus();
                                        }
                                    }
                                    form.classList.add('was-validated');
                                }, false);
                            });
                        }, false);
                    })();

                    // Remember last active tab
                    $(document).ready(function() {
                        // Check for saved tab in localStorage
                        var lastTab = localStorage.getItem('lastTab');
                        if (lastTab) {
                            $('.nav-tabs a[href="' + lastTab + '"]').tab('show');
                        }

                        // Save the latest tab when a new one is shown
                        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                            localStorage.setItem('lastTab', $(e.target).attr('href'));
                        });
                    });
                </script>

                <!-- End of Edit Form -->


            </div>

        </div>
        <!-- End of Main Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
