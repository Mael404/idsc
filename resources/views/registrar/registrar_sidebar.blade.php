<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('registrar.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/idslogo.png') }}" alt="Logo"
                style="width: 55px; height: auto; filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.8));">
        </div>
        <div class="sidebar-brand-text mx-3">IDSC</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-1">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('registrar.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Enrollment</div>

    <!-- Enrollment Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEnrollment"
            aria-expanded="true" aria-controls="collapseEnrollment">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Enrollment</span>
        </a>
        <div id="collapseEnrollment" class="collapse" aria-labelledby="headingEnrollment" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Enrollment Actions:</h6>
                <a class="collapse-item" href="{{ route('registrar.records.quick_search') }}">Manage Enrollment</a>
                <a class="collapse-item" href="{{ route('registrar.records.bulk_upload') }}">Pending Applications</a>
            </div>
        </div>
    </li>

    <!-- Student Records Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRecords"
            aria-expanded="true" aria-controls="collapseRecords">
            <i class="fas fa-fw fa-address-card"></i>
            <span>Student Records</span>
        </a>
        <div id="collapseRecords" class="collapse" aria-labelledby="headingRecords" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Records:</h6>
                <a class="collapse-item" href="{{ route('registrar.records.quick_search') }}">Search Records</a>
                <a class="collapse-item" href="{{ route('registrar.records.bulk_upload') }}">Update Records</a>
            </div>
        </div>
    </li>

    <!-- Document Requests Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRequests"
            aria-expanded="true" aria-controls="collapseRequests">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Document Requests</span>
        </a>
        <div id="collapseRequests" class="collapse" aria-labelledby="headingRequests" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Requests:</h6>
                <a class="collapse-item" href="{{ route('registrar.requests.express_processing') }}">View Requests</a>
                <a class="collapse-item" href="{{ route('registrar.requests.express_processing') }}">Process Request</a>
                <a class="collapse-item" href="{{ route('registrar.requests.notify_student') }}">Notify Student</a>
            </div>
        </div>
    </li>

    <!-- Archive Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseArchive"
            aria-expanded="true" aria-controls="collapseArchive">
            <i class="fas fa-fw fa-archive"></i>
            <span>Archive</span>
        </a>
        <div id="collapseArchive" class="collapse" aria-labelledby="headingArchive" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Archive Options:</h6>
                <a class="collapse-item" href="{{ route('registrar.archive.old_student_records') }}">Archived Records</a>
                <a class="collapse-item" href="{{ route('registrar.archive.disposal_log') }}">Disposal Log</a>
            </div>
        </div>
    </li>

    <!-- Reports Menu -->
    <li class="nav-item">
        <a class="nav-link" href="reports.html">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Reports</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
