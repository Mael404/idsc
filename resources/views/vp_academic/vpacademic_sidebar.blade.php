<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/idslogo.png') }}" alt="Logo"
                style="width: 55px; height: auto; filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.8));">
        </div>
        <div class="sidebar-brand-text mx-3">IDSC Registrar</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-1">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Enrollment</div>

    <!-- Enrollment -->
    <li class="nav-item {{ request()->is('enrollment*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEnrollment"
            aria-expanded="{{ request()->is('enrollment*') ? 'true' : 'false' }}" aria-controls="collapseEnrollment">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Enrollment</span>
        </a>
        <div id="collapseEnrollment" class="collapse {{ request()->is('enrollment*') ? 'show' : '' }}"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('enrollment/manage') ? 'active' : '' }}"
                   href="{{ url('enrollment/manage') }}">Manage Enrollment</a>
                <a class="collapse-item {{ request()->is('enrollment/pending') ? 'active' : '' }}"
                   href="{{ url('enrollment/pending') }}">Pending Applications</a>
            </div>
        </div>
    </li>

    <!-- Student Records -->
    <li class="nav-item {{ request()->is('records*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRecords"
            aria-expanded="{{ request()->is('records*') ? 'true' : 'false' }}" aria-controls="collapseRecords">
            <i class="fas fa-fw fa-address-card"></i>
            <span>Student Records</span>
        </a>
        <div id="collapseRecords" class="collapse {{ request()->is('records*') ? 'show' : '' }}"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('records/search') ? 'active' : '' }}"
                   href="{{ url('records/search') }}">Search Records</a>
                <a class="collapse-item {{ request()->is('records/update') ? 'active' : '' }}"
                   href="{{ url('records/update') }}">Update Records</a>
            </div>
        </div>
    </li>

    <!-- Document Requests -->
    <li class="nav-item {{ request()->is('requests*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRequests"
            aria-expanded="{{ request()->is('requests*') ? 'true' : 'false' }}" aria-controls="collapseRequests">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Document Requests</span>
        </a>
        <div id="collapseRequests" class="collapse {{ request()->is('requests*') ? 'show' : '' }}"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('requests/view') ? 'active' : '' }}"
                   href="{{ url('requests/view') }}">View Requests</a>
                <a class="collapse-item {{ request()->is('requests/process') ? 'active' : '' }}"
                   href="{{ url('requests/process') }}">Process Request</a>
                <a class="collapse-item {{ request()->is('requests/notify') ? 'active' : '' }}"
                   href="{{ url('requests/notify') }}">Notify Student</a>
            </div>
        </div>
    </li>

    <!-- Archive -->
    <li class="nav-item {{ request()->is('archive*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseArchive"
            aria-expanded="{{ request()->is('archive*') ? 'true' : 'false' }}" aria-controls="collapseArchive">
            <i class="fas fa-fw fa-archive"></i>
            <span>Archive</span>
        </a>
        <div id="collapseArchive" class="collapse {{ request()->is('archive*') ? 'show' : '' }}"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('archive/records') ? 'active' : '' }}"
                   href="{{ url('archive/records') }}">Archived Records</a>
                <a class="collapse-item {{ request()->is('archive/disposal') ? 'active' : '' }}"
                   href="{{ url('archive/disposal') }}">Disposal Log</a>
            </div>
        </div>
    </li>

    <!-- Reports -->
    <li class="nav-item {{ request()->is('reports') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('reports') }}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Reports</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
