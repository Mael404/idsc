@extends('layouts.main')

@section('tab_title', 'Dashboard')
@section('registrar_sidebar')
    @include('registrar.registrar_sidebar')
@endsection

@section('content')

    <div id="content-wrapper" class="d-flex flex-column">


        <div id="content">

            @include('layouts.topbar')


            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Semesters</h1>

                    <!-- Replace Add Semester Button Label -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#admissionFormModal">
                        Open Admission Form
                    </button>

                </div>

                <!-- Admission Form Modal with Tabs -->
                <div class="modal fade" id="admissionFormModal" tabindex="-1" aria-labelledby="admissionFormModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admissions.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Admission Form</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>

                                <div class="modal-body">
                                    <ul class="nav nav-tabs mb-3" id="formTabs" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#step1"
                                                role="tab">Personal Info</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step2"
                                                role="tab">Parents</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step3"
                                                role="tab">Other Details</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step4"
                                                role="tab">Admission</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#step5"
                                                role="tab">Education</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <!-- Step 1: Personal Info -->
                                        <div class="tab-pane fade show active" id="step1" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-4"><input name="last_name" class="form-control"
                                                        placeholder="Last Name" required></div>
                                                <div class="col-md-4"><input name="first_name" class="form-control"
                                                        placeholder="First Name" required></div>
                                                <div class="col-md-4"><input name="middle_name" class="form-control"
                                                        placeholder="Middle Name"></div>
                                                <div class="col-12"><input name="address_line1" class="form-control"
                                                        placeholder="Street/Barangay/City"></div>
                                                <div class="col-md-6"><input name="address_line2" class="form-control"
                                                        placeholder="District/Province/Region"></div>
                                                <div class="col-md-3"><input name="zip_code" class="form-control"
                                                        placeholder="Zip Code"></div>
                                                <div class="col-md-3"><input name="contact_number" class="form-control"
                                                        placeholder="Contact No."></div>
                                                <div class="col-12"><input name="email" type="email"
                                                        class="form-control" placeholder="Email Address"></div>
                                            </div>
                                        </div>

                                        <!-- Step 2: Parents Info -->
                                        <div class="tab-pane fade" id="step2" role="tabpanel">
                                            <h6>Father</h6>
                                            <div class="row g-3">
                                                <div class="col-md-4"><input name="father_last_name" class="form-control"
                                                        placeholder="Last Name"></div>
                                                <div class="col-md-4"><input name="father_first_name"
                                                        class="form-control" placeholder="First Name"></div>
                                                <div class="col-md-4"><input name="father_middle_name"
                                                        class="form-control" placeholder="Middle Name"></div>
                                                <div class="col-md-6"><input name="father_contact" class="form-control"
                                                        placeholder="Contact Number"></div>
                                                <div class="col-md-3"><input name="father_profession"
                                                        class="form-control" placeholder="Profession"></div>
                                                <div class="col-md-3"><input name="father_industry" class="form-control"
                                                        placeholder="Industry"></div>
                                            </div>
                                            <h6 class="mt-3">Mother</h6>
                                            <div class="row g-3">
                                                <div class="col-md-4"><input name="mother_last_name" class="form-control"
                                                        placeholder="Last Name"></div>
                                                <div class="col-md-4"><input name="mother_first_name"
                                                        class="form-control" placeholder="First Name"></div>
                                                <div class="col-md-4"><input name="mother_middle_name"
                                                        class="form-control" placeholder="Middle Name"></div>
                                                <div class="col-md-6"><input name="mother_contact" class="form-control"
                                                        placeholder="Contact Number"></div>
                                                <div class="col-md-3"><input name="mother_profession"
                                                        class="form-control" placeholder="Profession"></div>
                                                <div class="col-md-3"><input name="mother_industry" class="form-control"
                                                        placeholder="Industry"></div>
                                            </div>
                                        </div>

                                        <!-- Step 3: Other Personal Details -->
                                        <div class="tab-pane fade" id="step3" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-3"><input name="gender" class="form-control"
                                                        placeholder="Gender"></div>
                                                <div class="col-md-3"><input name="birthdate" type="date"
                                                        class="form-control"></div>
                                                <div class="col-md-3"><input name="birthplace" class="form-control"
                                                        placeholder="Birthplace"></div>
                                                <div class="col-md-3"><input name="citizenship" class="form-control"
                                                        placeholder="Citizenship"></div>
                                                <div class="col-md-3"><input name="religion" class="form-control"
                                                        placeholder="Religion"></div>
                                                <div class="col-md-3"><input name="civil_status" class="form-control"
                                                        placeholder="Civil Status"></div>
                                            </div>
                                        </div>

                                        <!-- Step 4: Admission Info -->
                                        <div class="tab-pane fade" id="step4" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <select name="course" class="form-select">
                                                        <option selected disabled>Choose Course</option>
                                                        <option>Bachelor of Science in IT</option>
                                                        <option>Bachelor of Education</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6"><input name="major" class="form-control"
                                                        placeholder="Major"></div>
                                            </div>

                                            <div class="form-check mt-3">
                                                <input class="form-check-input" type="radio" name="admission_status"
                                                    value="highschool" id="highschool">
                                                <label class="form-check-label" for="highschool">High School
                                                    Graduate</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="admission_status"
                                                    value="transferee" id="transferee">
                                                <label class="form-check-label" for="transferee">Transferee</label>
                                            </div>

                                            <div class="row g-3 mt-2">
                                                <div class="col-md-6"><input name="student_no" class="form-control"
                                                        placeholder="Student No. (if transferee)"></div>
                                                <div class="col-md-6"><input name="admission_year" class="form-control"
                                                        placeholder="Year When Admitted"></div>
                                                <div class="col-md-6">
                                                    <select name="scholarship" class="form-select">
                                                        <option selected disabled>Select Scholarship</option>
                                                        <option>Academic</option>
                                                        <option>Athletic</option>
                                                        <option>Government Grant</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6"><input name="previous_school" class="form-control"
                                                        placeholder="Previous School (if any)"></div>
                                                <div class="col-12"><input name="previous_school_address"
                                                        class="form-control" placeholder="School Address (if any)"></div>
                                            </div>
                                        </div>

                                        <!-- Step 5: Education History -->
                                        <div class="tab-pane fade" id="step5" role="tabpanel">
                                            <div class="row g-3">
                                                <div class="col-md-6"><input name="elementary_school"
                                                        class="form-control" placeholder="Elementary School Name"></div>
                                                <div class="col-md-6"><input name="elementary_address"
                                                        class="form-control" placeholder="Elementary Address"></div>
                                                <div class="col-md-6"><input name="secondary_school" class="form-control"
                                                        placeholder="Secondary School Name"></div>
                                                <div class="col-md-6"><input name="secondary_address"
                                                        class="form-control" placeholder="Secondary Address"></div>
                                                <div class="col-12"><input name="honors" class="form-control"
                                                        placeholder="Honors Received"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-success" type="submit">Submit</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
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
