@extends('layouts.main')

@section('tab_title', 'Manage Subjects')
@section('vpacademic_sidebar')
    @include('vp_academic.vpacademic_sidebar')
@endsection

@section('content')
    <!-- Content Wrasssspper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            @include('layouts.topbar')

            <!-- Begin Page Cosntent -->
            <div class="container-fluid">
                @include('layouts.success-message')


                <!-- Page Heading with Button on Same Row -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Manage Subjects</h1>

                    <!-- Button to Open Add Course Form -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addCourseModal">
                        Add New Course
                    </button>
                </div>
                <!-- Edit/View Modal -->

                <div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg"> <!-- Make modal larger -->
                        <form method="POST" id="editCourseForm">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="editCourseModalLabel">View / Edit Course Details</h5>
                                    <button type="button" class="close text-white" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Course Code</label>
                                                <input type="text" class="form-control" id="modal-code" name="code"
                                                    required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Subject Name</label>
                                                <input type="text" class="form-control" id="modal-name" name="name"
                                                    required>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" id="modal-description" name="description" rows="3"></textarea>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Units</label>
                                                <input type="number" class="form-control" id="modal-units" step="0.1" name="units"
                                                    min="0" required>
                                            </div>

                                            
                                            <div class="col-md-4">
                                                <label class="form-label">Lecture Hours</label>
                                                <input type="number" step="0.1" class="form-control"
                                                    id="modal-lecture-hours" name="lecture_hours" >
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Lab Hours</label>
                                                <input type="number" step="0.1" class="form-control"
                                                    id="modal-lab-hours" name="lab_hours" >
                                            </div>

                                            <div class="col-12 mt-2">
                                                <fieldset class="border p-3 rounded">
                                                    <legend class="float-none w-auto px-2" style="font-size: 1rem;">
                                                        Prerequisites (optional)</legend>

                                                    <div class="form-group">
                                                        <!-- Search input -->
                                                        <input type="text" class="form-control mb-2"
                                                            id="edit-prerequisite-search"
                                                            placeholder="Search for a course to add as prerequisite...">

                                                        <!-- Search ressult list -->
                                                        <div id="edit-search-results" class="list-group"></div>

                                                        <!-- Selected prerequisites -->
                                                        <ul id="edit-selected-prerequisites" class="list-group mt-2"></ul>

                                                        <!-- Hidden inputs container -->
                                                        <div id="edit-prerequisite-hidden-inputs"></div>
                                                    </div>

                                                </fieldset>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal for Add Course Form -->
                <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg"> <!-- Make modal larger -->
                        <form action="{{ route('courses.store') }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="addCourseModalLabel">Add Course</h5>
                                
                                </div>

                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Course Code</label>
                                                <input type="text" name="code" class="form-control"
                                                    placeholder="Course Code" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Subject Name</label>
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Course Name" required>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" placeholder="Description"></textarea>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Units</label>
                                                <input type="number" name="units" step="0.1" class="form-control"
                                                    placeholder="Units" required>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Lecture Hours</label>
                                                <input type="number" name="lecture_hours" step="0.1"
                                                    class="form-control" placeholder="Lecture Hours">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Lab Hours</label>
                                                <input type="number" name="lab_hours" step="0.1"
                                                    class="form-control" placeholder="Lab Hours">
                                            </div>

                                            <div class="col-12 mt-2">
                                                <fieldset class="border p-3 rounded">
                                                    <legend class="float-none w-auto px-2" style="font-size: 1rem;">
                                                        Prerequisites (optional)
                                                    </legend>

                                                    <div class="form-group mb-3">
                                                        <input type="text" id="prerequisite-search"
                                                            class="form-control" placeholder="Search courses...">
                                                    </div>

                                                    <!-- Results container -->
                                                    <div id="search-results" class="list-group mb-3">
                                                        <!-- Search results will be dynamically inserted here -->
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Selected Prerequisites:</label>
                                                        <ul id="selected-prerequisites" class="list-group">
                                                            <!-- Selected prerequisites will be shown here -->
                                                        </ul>
                                                    </div>
                                                </fieldset>
                                            </div>

                                            <!-- Hidden inputs for form submission -->
                                            <div id="prerequisite-hidden-inputs"></div>





                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                              
                                    <button type="submit" class="btn btn-primary">Save Course</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



                <!-- Display Courses in Table -->
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="coursesTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Subject name</th>
                                                <th>Description</th>
                                                <th>Units</th>
                                              
                                                <th>Prerequisite</th> <!-- Added column for Prerequisite -->
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($courses as $course)
                                                <tr>
                                                    <td class="text-center">{{ $course->code }}</td>
                                                    <td>{{ $course->name }}</td>
                                                    <td>{{ $course->description }}</td>
                                                    <td>{{ $course->units }}</td>
                                                   

                                                    <!-- Show the prerequisite name if it exists -->
                                                    <td>
                                                        @if ($course->prerequisites->isNotEmpty())
                                                            {{ $course->prerequisites->pluck('code')->implode(', ') }}
                                                        @else
                                                            None
                                                        @endif
                                                    </td>


                                                    <td class="text-center">
                                                        <span
                                                            class="badge {{ $course->active ? 'badge-success' : 'badge-danger' }}">
                                                            {{ $course->active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center"
                                                            style="gap: 5px;">
                                                            <!-- Edit Button -->
                                                            <a href="javascript:void(0);"
                                                            class="btn btn-info btn-sm fixed-width-btn view-course-btn"
                                                            data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                                            data-id="{{ $course->id }}"
                                                            data-code="{{ $course->code }}"
                                                            data-name="{{ $course->name }}"
                                                            data-description="{{ $course->description }}"
                                                            data-units="{{ $course->units }}"
                                                            data-lecture-hours="{{ $course->lecture_hours }}"
                                                            data-lab-hours="{{ $course->lab_hours }}"
                                                            data-prerequisites='@json($course->prerequisites->map(function($prereq) {
                                                                return ['id' => $prereq->id, 'name' => $prereq->name];
                                                            }))'>
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        
                                                            <!-- Activate/Deactivate Button -->
                                                            <form
                                                                action="{{ route('courses.toggleActive', $course->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-warning btn-sm fixed-width-btn">
                                                                    <i
                                                                        class="fas {{ $course->active ? 'fa-times' : 'fa-check' }}"></i>
                                                                </button>
                                                            </form>

                                                            <!-- Delete Button -->
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm fixed-width-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $course->id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>

                                                        <!-- DELETE MODAL -->
                                                        <div class="modal fade" id="deleteModal{{ $course->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="deleteModalLabel{{ $course->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content border-danger">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title"
                                                                            id="deleteModalLabel{{ $course->id }}">
                                                                            Delete Course</h5>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete
                                                                        <strong>{{ $course->name }}</strong>
                                                                        ({{ $course->code }})
                                                                        ?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form method="POST"
                                                                            action="{{ route('courses.destroy', $course->id) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Cancel</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">Yes, Delete</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Page Content -->

        </div>
        <!-- End Page Content -->


        <!-- End of Main Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->
@endsection

@section('scripts')
    <script src="{{ asset('js/courses.js') }}"></script>

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
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('prerequisite-search');
            const searchResults = document.getElementById('search-results');
            const selectedList = document.getElementById('selected-prerequisites');
            const hiddenInputs = document.getElementById('prerequisite-hidden-inputs');

            // Load all courses
            const allCourses = @json($allCourses);
            let availableCourses = [...allCourses]; // copy, will update as user selects

            function renderSearchResults(searchTerm) {
                searchResults.innerHTML = '';

                if (searchTerm.length === 0) {
                    return;
                }

                const filtered = availableCourses.filter(course =>
                    course.name.toLowerCase().includes(searchTerm.toLowerCase())
                );

                filtered.forEach(course => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.classList.add('list-group-item', 'list-group-item-action');
                    button.setAttribute('data-id', course.id);
                    button.setAttribute('data-name', course.name);
                    button.innerText = course.name;
                    searchResults.appendChild(button);
                });

                if (filtered.length === 0) {
                    searchResults.innerHTML = `<div class="list-group-item disabled">No courses found</div>`;
                }
            }

            searchInput.addEventListener('input', function() {
                renderSearchResults(this.value);
            });

            searchResults.addEventListener('click', function(e) {
                if (e.target.tagName === 'BUTTON') {
                    const courseId = e.target.getAttribute('data-id');
                    const courseName = e.target.getAttribute('data-name');

                    if (document.getElementById('selected-' + courseId)) {
                        alert('This prerequisite is already added.');
                        return;
                    }

                    // Add to selected list
                    const listItem = document.createElement('li');
                    listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between',
                        'align-items-center');
                    listItem.id = 'selected-' + courseId;
                    listItem.innerHTML = `
                    ${courseName}
                    <button type="button" class="btn btn-sm btn-danger remove-prerequisite" data-id="${courseId}">
                        Remove
                    </button>
                `;
                    selectedList.appendChild(listItem);

                    // Add hidden input
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'prerequisite_id[]';
                    input.value = courseId;
                    input.id = 'input-' + courseId;
                    hiddenInputs.appendChild(input);


                    availableCourses = availableCourses.filter(course => course.id != courseId);


                    searchInput.value = '';
                    searchResults.innerHTML = '';
                }
            });


            selectedList.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-prerequisite')) {
                    const courseId = e.target.getAttribute('data-id');

                    document.getElementById('selected-' + courseId)?.remove();
                    document.getElementById('input-' + courseId)?.remove();


                    const course = allCourses.find(c => c.id == courseId);
                    if (course) {
                        availableCourses.push(course);
                    }
                }
            });
        });
    </script>

<script>
           const allCourses = @json($allCourses);
</script>

    

@endsection
