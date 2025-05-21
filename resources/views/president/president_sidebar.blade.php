<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('president.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/idslogo.png') }}" alt="Logo"
                style="width: 55px; height: auto; filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.8));">
        </div>
        <div class="sidebar-brand-text mx-3">IDSC</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-1">

    <li class="nav-item {{ request()->is('president/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('president.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('president/revenue-trends') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('president.revenue-trends') }}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Revenue Trends</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('president/scholarships-discounts') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('president.scholarships-discounts') }}">
            <i class="fas fa-fw fa-pie-chart"></i>
            <span>Scholarships & Discounts</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('president/enrollment-heatmap') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('president.enrollment-heatmap') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Enrollment Heatmap</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('president/financial-alerts') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('president.financial-alerts') }}">
            <i class="fas fa-fw fa-exclamation-triangle"></i>
            <span>Financial Alerts</span>
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
