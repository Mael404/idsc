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
    <li class="nav-item {{ request()->is('registrar/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('registrar.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Enrollment</div>

    <!-- Enrollment Menu -->
    <li class="nav-item {{ request()->is('registrar/enrollment/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEnrollment"
           aria-expanded="{{ request()->is('registrar/enrollment/*') ? 'true' : 'false' }}" aria-controls="collapseEnrollment">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Enrollment</span>
        </a>
        <div id="collapseEnrollment" class="collapse {{ request()->is('registrar/enrollment/*') ? 'show' : '' }}"
             aria-labelledby="headingEnrollment" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Enrollment Actions:</h6>
                <a class="collapse-item {{ request()->routeIs('registrar.enrollment.manage') ? 'active' : '' }}"
                   href="{{ route('registrar.enrollment.manage') }}">Manage Enrollment</a>
                <a class="collapse-item {{ request()->routeIs('registrar.enrollment.pending') ? 'active' : '' }}"
                   href="{{ route('registrar.enrollment.pending') }}">Pending Applications</a>
            </div>
        </div>
    </li>

    <!-- Student Records Menu -->
    <li class="nav-item {{ request()->is('registrar/records/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRecords"
           aria-expanded="{{ request()->is('registrar/records/*') ? 'true' : 'false' }}" aria-controls="collapseRecords">
            <i class="fas fa-fw fa-address-card"></i>
            <span>Student Records</span>
        </a>
        <div id="collapseRecords" class="collapse {{ request()->is('registrar/records/*') ? 'show' : '' }}"
             aria-labelledby="headingRecords" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Records:</h6>
                <a class="collapse-item {{ request()->routeIs('registrar.records.search') ? 'active' : '' }}"
                   href="{{ route('registrar.records.search') }}">Search Records</a>
                <a class="collapse-item {{ request()->routeIs('registrar.records.update') ? 'active' : '' }}"
                   href="{{ route('registrar.records.update') }}">Update Records</a>
            </div>
        </div>
    </li>

    <!-- Document Requests Menu -->
    <li class="nav-item {{ request()->is('registrar/requests/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRequests"
           aria-expanded="{{ request()->is('registrar/requests/*') ? 'true' : 'false' }}" aria-controls="collapseRequests">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Document Requests</span>
        </a>
        <div id="collapseRequests" class="collapse {{ request()->is('registrar/requests/*') ? 'show' : '' }}"
             aria-labelledby="headingRequests" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Requests:</h6>
                <a class="collapse-item {{ request()->routeIs('registrar.requests.express_processing') ? 'active' : '' }}"
                   href="{{ route('registrar.requests.express_processing') }}">View Requests</a>
                <a class="collapse-item {{ request()->routeIs('registrar.requests.express_processing') ? 'active' : '' }}"
                   href="{{ route('registrar.requests.express_processing') }}">Process Request</a>
                <a class="collapse-item {{ request()->routeIs('registrar.requests.notify_student') ? 'active' : '' }}"
                   href="{{ route('registrar.requests.notify_student') }}">Notify Student</a>
            </div>
        </div>
    </li>

    <!-- Archive Menu -->
    <li class="nav-item {{ request()->is('registrar/archive/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseArchive"
           aria-expanded="{{ request()->is('registrar/archive/*') ? 'true' : 'false' }}" aria-controls="collapseArchive">
            <i class="fas fa-fw fa-archive"></i>
            <span>Archive</span>
        </a>
        <div id="collapseArchive" class="collapse {{ request()->is('registrar/archive/*') ? 'show' : '' }}"
             aria-labelledby="headingArchive" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Archive Options:</h6>
                <a class="collapse-item {{ request()->routeIs('registrar.archive.old_student_records') ? 'active' : '' }}"
                   href="{{ route('registrar.archive.old_student_records') }}">Archived Records</a>
                <a class="collapse-item {{ request()->routeIs('registrar.archive.disposal_log') ? 'active' : '' }}"
                   href="{{ route('registrar.archive.disposal_log') }}">Disposal Log</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
