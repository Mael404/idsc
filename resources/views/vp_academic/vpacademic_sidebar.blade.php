<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/idslogo.png') }}" alt="Logo"
                style="width: 55px; height: auto; filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.8));">
        </div>
        <div class="sidebar-brand-text mx-3">IDSC <sup></sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-1">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('vpadmin_dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('vpadmin_dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Sidebar Menu:
    </div>

    <!-- Nav Item - Scheduling -->
    <li class="nav-item {{ request()->is('scheduling/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseScheduling"
            aria-expanded="{{ request()->is('scheduling/*') ? 'true' : 'false' }}" aria-controls="collapseScheduling">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Scheduling</span>
        </a>
        <div id="collapseScheduling" class="collapse {{ request()->is('scheduling/*') ? 'show' : '' }}"
            aria-labelledby="headingScheduling" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('scheduling/room-assignment') ? 'active' : '' }}"
                    href="{{ url('scheduling/room-assignment') }}">Room Assignment</a>
                <a class="collapse-item {{ request()->is('scheduling/faculty-load') ? 'active' : '' }}"
                    href="{{ url('scheduling/faculty-load') }}">Faculty Load Board</a>
                <a class="collapse-item {{ request()->is('scheduling/schedule-classes') ? 'active' : '' }}"
                    href="{{ url('scheduling/schedule-classes') }}">Schedule Classes</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Faculty Evaluation Radar -->
    <li class="nav-item {{ request()->is('faculty/evaluation-radar') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('faculty/evaluation-radar') }}">
            <i class="fas fa-fw fa-bullseye"></i>
            <span>Faculty Evaluation Radar</span>
        </a>
    </li>

    <!-- Nav Item - Analytics -->
    <li class="nav-item {{ request()->is('analytics/*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('analytics') }}">
            <i class="fas fa-fw fa-chart-pie"></i>
            <span>Analytics</span>
        </a>
    </li>

    <!-- Nav Item - Academic Setup (with dropdown) -->
    <li
        class="nav-item {{ request()->is('programs*') || request()->is('courses*') || request()->is('year*') || request()->is('semester*') || request()->is('program-mapping*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#academicSetup"
            aria-expanded="true" aria-controls="academicSetup">
            <i class="fas fa-fw fa-university"></i>
            <span>Academic Setup</span>
        </a>
        <div id="academicSetup"
            class="collapse {{ request()->is('programs*') || request()->is('courses*') || request()->is('year*') || request()->is('semester*') || request()->is('program-mapping*') ? 'show' : '' }}"
            aria-labelledby="headingAcademic" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('programs') || request()->is('programs/*') ? 'active' : '' }}"
                    href="{{ url('programs') }}"> Manage Programs</a>
                <a class="collapse-item {{ request()->is('courses') || request()->is('courses/*') ? 'active' : '' }}"
                    href="{{ url('courses') }}"> Manage Subjects</a>
                <a class="collapse-item {{ request()->is('year') || request()->is('year/*') ? 'active' : '' }}"
                    href="{{ url('year') }}"> Manage Year</a>
                <a class="collapse-item {{ request()->is('semester') || request()->is('semester/*') ? 'active' : '' }}"
                    href="{{ url('semester') }}"> Manage Semester</a>
                <a class="collapse-item {{ request()->is('program-mapping') || request()->is('program-mapping/*') ? 'active' : '' }}"
                    href="{{ url('program-mapping') }}"> Program Mapping</a>
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
