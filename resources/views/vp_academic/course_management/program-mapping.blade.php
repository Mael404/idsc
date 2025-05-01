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




                <table id="mapping" class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Year Level</th>
                            <th>Semester</th>
                            <th>Effective SY</th>
                            <th>Courses (with Prerequisites)</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programMappings as $key => $mappings)
                            @php
                                $first = $mappings->first();
                            @endphp
                            <tr>
                                <td>{{ $first->program->name ?? 'N/A' }}</td>
                                <td>{{ $first->yearLevel->name ?? 'N/A' }}</td>
                                <td>{{ $first->semester->name ?? 'N/A' }}</td>
                                <td>{{ $first->effective_sy }}</td>
                                <td>
                                    <ul>
                                        @foreach ($mappings as $mapping)
                                            <li>
                                                <strong>{{ $mapping->course->name }}</strong>
                                                @if ($mapping->course->prerequisites->count())
                                                    <br><small>Prerequisites:
                                                        {{ $mapping->course->prerequisites->pluck('name')->implode(', ') }}
                                                    </small>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center" style="gap: 5px;">
                                        @php
                                            $first = $mappings->first(); // You already used this earlier
                                        @endphp

                                        <!-- VIEW BUTTON -->
                                        <a href="javascript:void(0);"
                                            class="btn btn-info btn-sm fixed-width-btn view-mapping-btn"
                                            data-bs-toggle="modal" data-bs-target="#viewMappingModal{{ $first->id }}"
                                            data-id="{{ $first->id }}">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- TOGGLE ACTIVE/INACTIVE BUTTON -->
                                        <form action="{{ route('program.mapping.toggleActive', $first->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm fixed-width-btn">
                                                <i class="fas {{ $first->active ? 'fa-times' : 'fa-check' }}"></i>
                                            </button>
                                        </form>

                                        <!-- DELETE BUTTON -->
                                        <button type="button" class="btn btn-danger btn-sm fixed-width-btn"
                                            data-bs-toggle="modal" data-bs-target="#deleteMappingModal{{ $first->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>


                                    <!-- DELETE CONFIRMATION MODAL -->
                                    <div class="modal fade" id="deleteMappingModal{{ $first->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel{{ $first->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content border-danger">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $first->id }}">Delete
                                                        Mapping</h5>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete the mapping for
                                                    <strong>{{ $first->program->name ?? 'N/A' }}</strong>,
                                                    {{ $first->yearLevel->name ?? '' }} -
                                                    {{ $first->semester->name ?? '' }} SY {{ $first->effective_sy }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST"
                                                        action="{{ route('program.mapping.destroy', $first->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <form method="POST" action="{{ route('program.mapping.update', $first->id) }}"
                                        id="editProgramMappingForm{{ $first->id }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal fade" id="viewMappingModal{{ $first->id }}" tabindex="-1"
                                            aria-labelledby="viewMappingModalLabel{{ $first->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title"
                                                            id="viewMappingModalLabel{{ $first->id }}">Edit Program
                                                            Mapping</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Program Info -->
                                                        <p><strong>Program:</strong> {{ $first->program->name }}</p>
                                                        <p><strong>Year Level:</strong> {{ $first->yearLevel->name }}</p>
                                                        <p><strong>Semester:</strong> {{ $first->semester->name }}</p>
                                                        <p><strong>Effective SY:</strong> {{ $first->effective_sy }}</p>

                                                        <hr>

                                                        <!-- Existing Courses -->
                                                        <h6>Current Courses:</h6>
                                                        <ul class="list-group mb-3"
                                                            id="existingCoursesList{{ $first->id }}">
                                                            @foreach ($mappings as $mapping)
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <span>
                                                                        {{ $mapping->course->name }}
                                                                        @if ($mapping->course->prerequisites->count())
                                                                            <small class="text-muted">
                                                                                (Prerequisites:
                                                                                {{ $mapping->course->prerequisites->pluck('name')->implode(', ') }})
                                                                            </small>
                                                                        @endif
                                                                    </span>
                                                                    <div class="d-flex align-items-center"
                                                                        style="gap: 10px;">
                                                                        <input type="hidden" name="existing_courses[]"
                                                                            value="{{ $mapping->course->id }}">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm remove-existing-course">Remove</button>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                        <!-- Add New Course -->
                                                        <!-- Search and Add Course -->
                                                        <div class="form-group position-relative">
                                                            <label for="courseSearch{{ $first->id }}">Search and Add
                                                                Course</label>
                                                            <input type="text" class="form-control" autocomplete="off"
                                                                id="courseSearch{{ $first->id }}"
                                                                placeholder="Type course name...">
                                                            <div id="courseSuggestions{{ $first->id }}"
                                                                class="list-group position-absolute w-100 z-index-3"
                                                                style="max-height: 200px; overflow-y: auto;"></div>
                                                        </div>

                                                        <!-- New Courses List -->
                                                        <ul class="list-group mt-3" id="newCoursesList{{ $first->id }}">
                                                        </ul>

                                                    </div>

                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Update
                                                            Mapping</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>


                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>



                <!-- Modal for Create Program Mapping -->
                <div class="modal fade" id="createProgramMappingModal" tabindex="-1"
                    aria-labelledby="createProgramMappingModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('program.mapping.store') }}" id="programMappingForm">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createProgramMappingModalLabel">Create New Program Mapping
                                    </h5>
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
                                            @foreach ($programs as $program)
                                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Add New Course -->
                                    <div class="form-group">
                                        <label for="courseSearch{{ $first->id }}">Search and Add Course</label>
                                        <input type="text" class="form-control" id="courseSearch{{ $first->id }}"
                                            placeholder="Type to search...">
                                        <div id="courseSuggestions{{ $first->id }}" class="list-group mt-1"></div>
                                    </div>

                                    <!-- New Courses List -->
                                    <ul class="list-group mt-3" id="newCoursesList{{ $first->id }}"></ul>


                                    <!-- Hidden inputs for each selected course -->
                                    <div id="hiddenCoursesInputs"></div>

                                    <!-- Year Level and Semester -->
                                    <div class="form-group">
                                        <label for="year_level_id">Year Level</label>
                                        <select class="form-control" id="year_level_id" name="year_level_id" required>
                                            <option value="">Select Year Level</option>
                                            @foreach ($yearLevels as $yearLevel)
                                                <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="semester_id">Semester</label>
                                        <select class="form-control" id="semester_id" name="semester_id" required>
                                            <option value="">Select Semester</option>
                                            @foreach ($semesters as $semester)
                                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Effective SY (School Year) Input -->
                                    <div class="form-group">
                                        <label for="effective_sy">Effective School Year</label>
                                        <input type="text" class="form-control" id="effective_sy" name="effective_sy"
                                            placeholder="Enter School Year (e.g., 2025-2026)" required>
                                    </div>
                                    <input type="hidden" name="action_type" value="create_mapping">

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
            $('#mapping').DataTable({
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
                    var listItem = $(
                        '<li class="list-group-item d-flex justify-content-between align-items-center"></li>'
                    );
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
    <script>
       $(document).ready(function () {
    const courses = @json($courses);

    $(document).on('input', '[id^="courseSearch"]', function () {
        const modalId = $(this).attr('id').replace('courseSearch', '');
        const query = $(this).val().toLowerCase();
        const suggestionsBox = $(`#courseSuggestions${modalId}`);
        suggestionsBox.empty().show();

        if (query.length < 1) return;

        const filtered = courses.filter(course =>
            course.name.toLowerCase().includes(query)
        );

        if (filtered.length === 0) {
            suggestionsBox.append(`<div class="list-group-item disabled">No courses found</div>`);
        } else {
            filtered.forEach(course => {
                suggestionsBox.append(
                    `<button type="button" class="list-group-item list-group-item-action" data-id="${course.id}" data-name="${course.name}">
                        ${course.name}
                    </button>`
                );
            });
        }
    });

    // Add course on suggestion click
    $(document).on('click', '[id^="courseSuggestions"] .list-group-item-action', function () {
        const courseId = $(this).data('id');
        const courseName = $(this).data('name');
        const modalId = $(this).parent().attr('id').replace('courseSuggestions', '');

        const alreadyExists = $(`#newCoursesList${modalId} input[value="${courseId}"]`).length > 0 ||
                              $(`#existingCoursesList${modalId} input[value="${courseId}"]`).length > 0;

        if (alreadyExists) {
            showDuplicateCourseAlert('This course is already added.');
            return;
        }

        const listItem = $(`
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${courseName}</span>
                <div>
                    <input type="hidden" name="new_courses[]" value="${courseId}">
                    <button type="button" class="btn btn-danger btn-sm remove-new-course">Remove</button>
                </div>
            </li>
        `);

        $(`#newCoursesList${modalId}`).append(listItem);
        $(`#courseSearch${modalId}`).val('');
        $(`#courseSuggestions${modalId}`).empty().hide();
    });

    // Remove course
    $(document).on('click', '.remove-new-course', function () {
        $(this).closest('li').remove();
    });

    // Hide suggestions when clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.form-group').length) {
            $('[id^="courseSuggestions"]').hide();
        }
    });

    function showDuplicateCourseAlert(message) {
        $('#dynamic-alert').remove();

        const alertHtml = `
            <div id="dynamic-alert" class="popup-alert fadeDownIn shadow rounded-lg p-4 position-fixed top-0 end-0 m-3 bg-white z-5">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-semibold fs-6 text-danger">
                        ${message}
                        <i class="fas fa-exclamation-circle ms-1"></i>
                    </span>
                </div>
            </div>
        `;

        $('body').append(alertHtml);

        setTimeout(() => {
            $('#dynamic-alert').removeClass('fadeDownIn').addClass('fadeOut');
            setTimeout(() => {
                $('#dynamic-alert').remove();
            }, 400);
        }, 2500);
    }
});

    </script>




@endsection
