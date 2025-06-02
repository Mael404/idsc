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

                <!-- ✏️ Edit Form -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="{{ route('registrar.updateStudent', $admission->student_id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input name="first_name" value="{{ $admission->first_name }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input name="middle_name" value="{{ $admission->middle_name }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input name="last_name" value="{{ $admission->last_name }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input name="contact_number" value="{{ $admission->contact_number }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input name="email" value="{{ $admission->email }}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="scholarship_id">Scholarship</label>
                                <select name="scholarship_id" class="form-control">
                                    <option value="">None</option>
                                    @foreach ($scholarships as $scholarship)
                                        <option value="{{ $scholarship->id }}" {{ $admission->scholarship_id == $scholarship->id ? 'selected' : '' }}>
                                            {{ $scholarship->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="region">Region</label>
                                <select name="region" class="form-control">
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}" {{ $admission->region == $region->id ? 'selected' : '' }}>
                                            {{ $region->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Add other fields as needed -->

                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ route('registrar.newEnrollment') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
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
