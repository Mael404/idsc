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

    <!-- Dashboard -->
    <li class="nav-item {{ request()->routeIs('president.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('president.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Analytics Submenu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAnalytics"
            aria-expanded="false" aria-controls="collapseAnalytics">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Analytics</span>
        </a>
        <div id="collapseAnalytics" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('president.revenue-trends') ? 'active' : '' }}" href="{{ route('president.revenue-trends') }}">Revenue Trends</a>
                <a class="collapse-item {{ request()->routeIs('president.scholarships-discounts') ? 'active' : '' }}" href="{{ route('president.scholarships-discounts') }}">Scholarships & Discounts</a>
                <a class="collapse-item {{ request()->routeIs('president.enrollment-heatmap') ? 'active' : '' }}" href="{{ route('president.enrollment-heatmap') }}">Enrollment Heatmap</a>
                <a class="collapse-item {{ request()->routeIs('president.financial-alerts') ? 'active' : '' }}" href="{{ route('president.financial-alerts') }}">Financial Alerts</a>
            </div>
        </div>
    </li>

    <!-- Reports Dropdown/Submenu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
            aria-expanded="false" aria-controls="collapseReports">
            <i class="fas fa-fw fa-folder-open"></i>
            <span>Reports</span>
        </a>
        <div id="collapseReports" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#">Monthly</a>
                <a class="collapse-item" href="#">Annual</a>
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
