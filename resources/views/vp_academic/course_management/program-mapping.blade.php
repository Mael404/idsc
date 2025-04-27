@extends('layouts.main')

@section('tab_title', 'Program Mapping')
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
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Page Heading with Button on Same Row -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Program Mappings</h1>

                    <!-- Button to Open Create Program Mapping Modal -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createProgramMappingModal">
                        Create New Program Mapping
                    </button>
                </div>

           <!-- Display Existing Program Mappings -->
<div class="row">
    @foreach ($programMappings as $groupKey => $mappings)
        @php
            // Get program, year level, and semester for the current group
            $firstMapping = $mappings->first();
            $program = $firstMapping->program;
            $yearLevel = $firstMapping->yearLevel;
            $semester = $firstMapping->semester;
        @endphp

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Program: {{ $program->name }} - Year Level: {{ $yearLevel->name }} - Semester: {{ $semester->name }}
                </div>
                <div class="card-body">
                    <h5 class="card-title">Courses</h5>
                    <ul class="list-group">
                        @foreach ($mappings as $mapping)
                            <li class="list-group-item">{{ $mapping->course->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
</div>

                <!-- Modal for Create Program Mapping -->
                <div class="modal fade" id="createProgramMappingModal" tabindex="-1" aria-labelledby="createProgramMappingModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('program.mapping.store') }}" id="programMappingForm">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createProgramMappingModalLabel">Create New Program Mapping</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Program Dropdown -->
                                    <div class="form-group">
                                        <label for="program_id">Program</label>
                                        <select class="form-control" id="program_id" name="program_id" required>
                                            <option value="">Select Program</option>
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                
                                    <!-- Course Dropdown -->
                                    <div class="form-group">
                                        <label for="course_id">Course</label>
                                        <select class="form-control" id="course_id" name="course_id" required>
                                            <option value="">Select Course</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-primary mt-2" id="addCourseBtn">Add Course</button>
                                    </div>
                
                                    <!-- List of Selected Courses -->
                                    <div id="selectedCourses" class="mb-3">
                                        <ul class="list-group" id="selectedCoursesList"></ul>
                                    </div>
                
                                    <!-- Hidden inputs for each selected course -->
                                    <div id="hiddenCoursesInputs"></div>
                
                                    <!-- Year Level and Semester -->
                                    <div class="form-group">
                                        <label for="year_level_id">Year Level</label>
                                        <select class="form-control" id="year_level_id" name="year_level_id" required>
                                            <option value="">Select Year Level</option>
                                            @foreach($yearLevels as $yearLevel)
                                                <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                
                                    <div class="form-group">
                                        <label for="semester_id">Semester</label>
                                        <select class="form-control" id="semester_id" name="semester_id" required>
                                            <option value="">Select Semester</option>
                                            @foreach($semesters as $semester)
                                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Mapping</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                

            </div>

        </div>
        <!-- End Page Content -->

        <!-- End of Main Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->
@endsection

@section('scripts')
    <script src="{{ asset('js/programs.js') }}"></script>

    <!-- DataTables JS -->
    <script>
        $(document).ready(function() {
            $('#coursesTable').DataTable({
                responsive: true,
                pageLength: 10
            });
        });
    </script>
 
 <script>
    $(document).ready(function() {
        // Handle adding selected courses to the list
        $('#addCourseBtn').on('click', function() {
            var courseId = $('#course_id').val();
            var courseName = $('#course_id option:selected').text();
    
            if (courseId && courseName) {
                // Create a list item
                var listItem = $('<li class="list-group-item d-flex justify-content-between align-items-center"></li>');
                listItem.text(courseName);
    
                // Add a hidden input to store the course ID
                var hiddenInput = $('<input type="hidden" name="course_id[]" />').val(courseId);
                listItem.append(hiddenInput);
    
                // Add a remove button to the list item
                var removeButton = $('<button class="btn btn-danger btn-sm">Remove</button>');
                removeButton.on('click', function() {
                    // Remove the hidden input as well
                    hiddenInput.remove();
                    listItem.remove();
                });
                listItem.append(removeButton);
    
                // Append the list item to the selected courses list
                $('#selectedCoursesList').append(listItem);
    
                // Clear the course dropdown
                $('#course_id').val('');
            } else {
                alert('Please select a course.');
            }
        });
    
        // Optionally, handle the form submission with selected courses
        $('#programMappingForm').on('submit', function(event) {
            // Ensure that at least one course has been selected before submitting
            if ($('#selectedCoursesList li').length === 0) {
                event.preventDefault();
                alert('Please select at least one course.');
            }
        });
    });
</script>

    
@endsection
