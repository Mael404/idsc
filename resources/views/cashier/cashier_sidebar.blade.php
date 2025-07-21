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
    <li class="nav-item {{ request()->is('cashier/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('cashier.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Queueing</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Payment Processing</div>

    <!-- Payment Processing Menu -->
    <li class="nav-item {{ request()->is('cashier/payment/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePayments"
            aria-expanded="true" aria-controls="collapsePayments">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Payments</span>
        </a>
        <div id="collapsePayments" class="collapse {{ request()->is('cashier/payment/*') ? 'show' : '' }}">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('cashier.payment.pending') ? 'active' : '' }}"
                    href="{{ route('cashier.payment.pending') }}">
                    Pending College
                </a>
                <a class="collapse-item {{ request()->routeIs('cashier.payment.shspending') ? 'active' : '' }}"
                    href="{{ route('cashier.payment.shspending') }}">
                    Pending SHS
                </a>
                <a class="collapse-item {{ request()->routeIs('cashier.payment.process') ? 'active' : '' }}"
                    href="{{ route('cashier.payment.process') }}">
                    Tuition fee college
                </a>
                <a class="collapse-item {{ request()->routeIs('cashier.payment.shs_pyment') ? 'active' : '' }}"
                    href="{{ route('cashier.payment.shs_pyment') }}">
                    Tuition fee SHS
                </a>
                <a class="collapse-item {{ request()->routeIs('cashier.payment.other') ? 'active' : '' }}"
                    href="{{ route('cashier.payment.other') }}">
                    Other Payments
                </a>
                <a class="collapse-item {{ request()->routeIs('cashier.payment.uniform') ? 'active' : '' }}"
                    href="{{ route('cashier.payment.uniform') }}">
                    Uniform Payments
                </a>
                <a class="collapse-item {{ request()->routeIs('cashier.payment.old') ? 'active' : '' }}"
                    href="{{ route('cashier.payment.old') }}">
                    Old Accounts
                </a>
            </div>

        </div>
    </li>




    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Reports</div>

    <!-- Payment Reports Menu -->
    <li class="nav-item {{ request()->is('cashier/reports/*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
            aria-expanded="{{ request()->is('cashier/reports/*') ? 'true' : 'false' }}"
            aria-controls="collapseReports">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Payment Reports</span>
        </a>
        <div id="collapseReports" class="collapse {{ request()->is('cashier/reports/*') ? 'show' : '' }}"
            aria-labelledby="headingReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Reports</h6>

                {{-- College Tuition Fee Payments --}}
                <a class="collapse-item {{ request()->routeIs('cashier.reports.index') ? 'active' : '' }}"
                    href="{{ route('cashier.reports.index') }}">
                    College Tuition Fee Payments
                </a>

                {{-- SHS Tuition Fee Payments --}}
                <a class="collapse-item {{ request()->routeIs('cashier.reports.shs') ? 'active' : '' }}"
                    href="{{ route('cashier.reports.shs') }}">
                    SHS Tuition Fee Payments
                </a>

                {{-- Other Payments --}}
                <a class="collapse-item {{ request()->routeIs('cashier.reports.other') ? 'active' : '' }}"
                    href="{{ route('cashier.reports.other') }}">
                    Other Payments
                </a>

                {{-- Uniform Payments --}}
                <a class="collapse-item {{ request()->routeIs('cashier.reports.uniform') ? 'active' : '' }}"
                    href="{{ route('cashier.reports.uniform') }}">
                    Uniform Payments
                </a>

                  {{-- Old Accounts Payments --}}
                <a class="collapse-item {{ request()->routeIs('cashier.reports.old') ? 'active' : '' }}"
                    href="{{ route('cashier.reports.old') }}">
                    Old Accounts Payments
                </a>
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