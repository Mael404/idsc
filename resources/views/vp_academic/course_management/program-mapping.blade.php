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
                            <th>Courses</th>
                            <th>Action</th>
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

                                                                        {{-- 
                                    <!-- TOGGLE ACTIVE/INACTIVE BUTTON -->
                                    <form action="{{ route('program.mapping.toggleActive', $first->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm fixed-width-btn">
                                            <i class="fas {{ $first->active ? 'fa-times' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                --}}


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
                                            <div class="modal-dialog modal-xl"> <!-- Changed to XL -->
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title"
                                                            id="viewMappingModalLabel{{ $first->id }}">Edit Program
                                                            Mapping</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Bottom Section - Add Course -->
                                                        <div class="form-group">
                                                            <fieldset class="border p-4 rounded"
                                                                style="border-color: black;">


                                                                <label for="courseSearch{{ $first->id }}">Add
                                                                    Subjects</label>
                                                                <div class="position-relative d-flex flex-column"
                                                                    style="gap: 10px;">
                                                                    <input type="text" class="form-control"
                                                                        id="courseSearch{{ $first->id }}"
                                                                        placeholder="Search for a course..."
                                                                        onkeyup="filterCourses({{ $first->id }})">

                                                                    <ul id="searchResults{{ $first->id }}"
                                                                        class="list-group position-absolute mt-1"
                                                                        style="
                                                                        display: none;
                                                                        max-height: 200px;
                                                                        overflow-y: auto;
                                                                        width: 100%;
                                                                        z-index: 999;
                                                                        top: 100%;
                                                                        left: 0;
                                                                        background: white;
                                                                        border: 1px solid #ddd;
                                                                        border-radius: 4px;
                                                                    ">
                                                                    </ul>

                                                                </div>
                                                            </fieldset>
                                                        </div>

                                                        <!-- New Courses List -->
                                                        <ul class="list-group mt-3 mb-1"
                                                            id="newCoursesList{{ $first->id }}">
                                                        </ul>

                                                        <!-- Grid layout -->
                                                        <div class="row">

                                                            <!-- Left Column - Program Details with fieldset and centered items -->
                                                            <div class="col-md-4">
                                                                <fieldset class="border p-4 rounded">
                                                                    <legend class="w-auto px-2">Program Details</legend>
                                                                    <div class="">
                                                                        <p><strong>Program:</strong>
                                                                            {{ $first->program->name }}</p>
                                                                        <p><strong>Year Level:</strong>
                                                                            {{ $first->yearLevel->name }}</p>
                                                                        <p><strong>Semester:</strong>
                                                                            {{ $first->semester->name }}</p>
                                                                        <p><strong>Effective SY:</strong>
                                                                            {{ $first->effective_sy }}</p>
                                                                    </div>
                                                                </fieldset>
                                                            </div>

                                                            <!-- Right Column - Current Courses (larger) -->
                                                            <div class="col-md-8">
                                                                <fieldset class="border p-4 rounded">
                                                                    <h6>Current Courses</h6>
                                                                    @php $totalUnits = 0; @endphp
                                                                    <table class="table table-bordered mb-3"
                                                                        id="existingCoursesTable{{ $first->id }}">
                                                                        <thead class="table-light">
                                                                            <tr>
                                                                                <th>Course Name</th>
                                                                                <th>Units</th>
                                                                                <th>Prerequisites</th>
                                                                                <th>Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($mappings as $mapping)
                                                                                @php $totalUnits += $mapping->course->units; @endphp
                                                                                <tr>
                                                                                    <td>{{ $mapping->course->name }}</td>
                                                                                    <td>{{ number_format($mapping->course->units, 1) }}
                                                                                    </td>

                                                                                    <td>
                                                                                        @if ($mapping->course->prerequisites->count())
                                                                                            {{ $mapping->course->prerequisites->pluck('code')->implode(', ') }}
                                                                                        @else
                                                                                            —
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <input type="hidden"
                                                                                            name="existing_courses[]"
                                                                                            value="{{ $mapping->course->id }}">
                                                                                        <button type="button"
                                                                                            class="btn btn-danger btn-sm remove-existing-course-with-confirm"
                                                                                            data-course-id="{{ $mapping->course->id }}">
                                                                                            Remove
                                                                                        </button>
                                                                                    </td>

                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <th>Total Units</th>
                                                                                <th style="text-align: center;">
                                                                                    {{ $totalUnits }}</th>

                                                                                <th colspan="2"></th>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </fieldset>
                                                            </div>
                                                        </div>


                                                        <hr>


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
                    <div class="modal-dialog modal-lg">
                        <form method="POST" action="{{ route('program.mapping.store') }}" id="programMappingForm">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="createProgramMappingModalLabel">Create Program Mapping
                                    </h5>
                                    <button type="button" class="close text-white" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row g-3">

                                            <div class="col-md-6">
                                                <label class="form-label">Program <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="program_id" required>
                                                    <option value="">Select Program</option>
                                                    @foreach ($programs as $program)
                                                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Effective School Year <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="effective_sy"
                                                    placeholder="e.g., 2025-2026" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Year Level <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="year_level_id" required>
                                                    <option value="">Select Year Level</option>
                                                    @foreach ($yearLevels as $yearLevel)
                                                        <option value="{{ $yearLevel->id }}">{{ $yearLevel->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Semester <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="semester_id" required>
                                                    <option value="">Select Semester</option>
                                                    @foreach ($semesters as $semester)
                                                        <option value="{{ $semester->id }}">{{ $semester->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-12 mt-2">
                                                <fieldset class="border p-3 rounded">
                                                    <legend class="float-none w-auto px-2" style="font-size: 1rem;">
                                                        Add Courses
                                                    </legend>

                                                    <div class="form-group mb-3">
                                                        <input type="text" id="courseSearchInput" class="form-control"
                                                            placeholder="Search course by name...">
                                                    </div>

                                                    <ul id="courseSuggestions" class="list-group mb-3"
                                                        style="display: none; max-height: 200px; overflow-y: auto;">
                                                        <!-- Dynamic suggestions go here -->
                                                    </ul>

                                                    <div class="form-group">
                                                        <label>Selected Subjects</label>
                                                        <ul class="list-group" id="selectedCoursesList"></ul>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <!-- Hidden course inputs -->
                                            <div id="hiddenCoursesInputs"></div>
                                            <input type="hidden" name="action_type" value="create_mapping">

                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Save Mapping</button>
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
            const courses = @json($courses);

            // Handle course search and display suggestions
            $('#courseSearchInput').on('input', function() {
                const query = $(this).val().toLowerCase();
                const filtered = courses.filter(course =>
                    course.name.toLowerCase().includes(query)
                );

                const suggestionList = $('#courseSuggestions');
                suggestionList.empty();

                if (query && filtered.length > 0) {
                    suggestionList.show();

                    filtered.forEach(course => {
                        const item = $(
                                '<li class="list-group-item list-group-item-action cursor-pointer"></li>'
                            )
                            .text(course.name)
                            .on('click', function() {
                                addCourseToSelected(course.id, course.name);
                                $('#courseSearchInput').val('');
                                suggestionList.hide();
                            });

                        suggestionList.append(item);
                    });
                } else {
                    suggestionList.hide();
                }
            });

            // Function to add selected course to the list
            function addCourseToSelected(courseId, courseName) {
                const alreadyExists = $('#hiddenCoursesInputs input[value="' + courseId + '"]').length > 0;

                if (alreadyExists) {
                    showPopupAlert('This course has already been added.');
                    return;
                }

                const listItem = $(`
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${courseName}
                </li>
            `);

                const hiddenInput = $('<input type="hidden" name="course_id[]" />').val(courseId);
                const removeButton = $('<button class="btn btn-danger btn-sm">Remove</button>').on('click',
                    function() {
                        hiddenInput.remove();
                        listItem.remove();
                    });

                listItem.append(hiddenInput).append(removeButton);

                $('#selectedCoursesList').append(listItem);
                $('#hiddenCoursesInputs').append(hiddenInput);
            }

            // Show popup alert for error messages
            function showPopupAlert(message) {
                // Remove any existing alert first
                $('#dynamic-popup-alert').remove();

                // Create the alert pop-up HTML
                const alertHtml = `
                <div id="dynamic-popup-alert" class="popup-alert fadeDownIn shadow rounded-lg p-4 position-fixed top-0 end-0 m-3 bg-white z-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold fs-6 text-danger-custom">
                            ${message}
                            <i class="fas fa-exclamation-circle ms-1"></i>
                        </span>
                    </div>
                </div>
            `;

                // Append the pop-up to the body
                $('body').append(alertHtml);

                // Automatically remove the pop-up after a few seconds
                setTimeout(() => {
                    $('#dynamic-popup-alert').removeClass('fadeDownIn').addClass('fadeOut');
                    setTimeout(() => {
                        $('#dynamic-popup-alert').remove();
                    }, 400);
                }, 2500);
            }

            // Ensure at least one course is selected before submitting
            $('#programMappingForm').on('submit', function(event) {
                if ($('#selectedCoursesList li').length === 0) {
                    event.preventDefault();
                    alert('Please select at least one course.');
                }
            });
        });
    </script>



    <script>
        function filterCourses(modalId) {
            const searchInput = document.getElementById(`courseSearch${modalId}`).value.toLowerCase();
            const searchResults = document.getElementById(`searchResults${modalId}`);
            const courses = @json($courses); // All possible courses
            const existingCourseInputs = document.querySelectorAll(
                `#existingCoursesTable${modalId} input[name="existing_courses[]"]`);

            // Get list of existing course IDs
            const existingCourseIds = Array.from(existingCourseInputs).map(input => parseInt(input.value));
            const newCourseInputs = document.querySelectorAll(`#newCoursesList${modalId} input[name="new_courses[]"]`);
            const newCourseIds = Array.from(newCourseInputs).map(input => parseInt(input.value));

            // Combine all excluded course IDs
            const excludedCourseIds = [...existingCourseIds, ...newCourseIds];


            // Filter courses based on search input and exclude existing courses
            const filteredCourses = courses.filter(course =>
                course.name.toLowerCase().includes(searchInput) &&
                !excludedCourseIds.includes(course.id)
            );

            // Clear previous results
            searchResults.innerHTML = '';

            // Display filtered results
            if (searchInput && filteredCourses.length) {
                searchResults.style.display = 'block';
                filteredCourses.forEach(course => {
                    const resultItem = document.createElement('li');
                    resultItem.classList.add('list-group-item', 'cursor-pointer', 'py-1', 'px-2', 'large');
                    resultItem.textContent = course.name;
                    resultItem.onclick = () => addCourseToList(modalId, course);
                    searchResults.appendChild(resultItem);
                });
            } else {
                searchResults.style.display = 'none';
            }
        }


        function addCourseToList(modalId, course) {
            const courseId = course.id;
            const courseName = course.name;

            // Check if the course is already added
            const alreadyExists = document.querySelectorAll(`#newCoursesList${modalId} input[value="${courseId}"]`).length >
                0;

            if (alreadyExists) {
                alert('This course is already added.');
                return;
            }

            // Create list item with hidden input
            const listItem = document.createElement('li');
            listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');

            const courseText = document.createElement('span');
            courseText.textContent = courseName;

            const removeButton = document.createElement('button');
            removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-new-course');
            removeButton.textContent = 'Remove';
            removeButton.onclick = function() {
                listItem.remove();
            };

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'new_courses[]';
            hiddenInput.value = courseId;

            // Append elements to the list item
            listItem.appendChild(courseText);
            const buttonContainer = document.createElement('div');
            buttonContainer.appendChild(hiddenInput);
            buttonContainer.appendChild(removeButton);
            listItem.appendChild(buttonContainer);

            // Append the list item to the new courses list
            document.getElementById(`newCoursesList${modalId}`).appendChild(listItem);

            // Clear the search input and hide results
            document.getElementById(`courseSearch${modalId}`).value = '';
            document.getElementById(`searchResults${modalId}`).style.display = 'none';
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.remove-existing-course-with-confirm').forEach(button => {
                button.addEventListener('click', function() {
                    const confirmed = confirm('Are you sure you want to remove this course?');
                    if (!confirmed) return;

                    const row = this.closest('tr');
                    if (row) {
                        row.remove();
                    }
                });
            });
        });
    </script>



@endsection
