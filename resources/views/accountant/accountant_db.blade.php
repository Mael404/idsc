@extends('layouts.main')

@section('tab_title', 'Accountant Dashboard')
@section('accountant_sidebar')
    @include('accountant.accountant_sidebar')
@endsection

@section('content')

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            @include('layouts.topbar')

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Accountant Dashboard</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-download fa-sm text-white-50"></i> Download Financial Report
                    </a>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Total Tuition Fees -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Tuition Fees</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ number_format($totalTuitionFees, 2) }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-wallet fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Outstanding Balances -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Outstanding Balances</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ number_format($outstandingBalances, 2) }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Payments -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Recent Payments</div>
                                        <div class="h6 mb-0 text-gray-800">
                                            @foreach ($recentPayments as $payment)
                                                OR#: {{ $payment->or_number }} -
                                                {{ number_format($payment->amount, 2) }}<br>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-receipt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Payments -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Fully Paid Students</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $fullPaymentsCount }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Balance Distribution Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Balance Distribution</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="balanceDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Sources Pie Chart -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Payment Sources</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="paymentSourcesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>


            <!-- End Page Content -->

        </div>
        <!-- End of Main Content -->

        @include('layouts.footer')

    </div>
    <!-- End of Content Wrapper -->
@endsection

<script>
    const balanceDistributionData = @json($balanceDistributionData);
    const paymentSourcesData = @json($paymentSourcesData);
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Prepare Balance Distribution Data
        const balanceLabels = balanceDistributionData.map(data => data.month);
        const collectedData = balanceDistributionData.map(data => data.collected);
        const outstandingData = balanceDistributionData.map(data => data.outstanding);

        // Balance Distribution Chart
        var balanceCtx = document.getElementById("balanceDistributionChart").getContext("2d");
        new Chart(balanceCtx, {
            type: "bar",
            data: {
                labels: balanceLabels,
                datasets: [{
                        label: "Tuition Fees Collected",
                        backgroundColor: "#4e73df",
                        hoverBackgroundColor: "#2e59d9",
                        data: collectedData,
                    },
                    {
                        label: "Outstanding Balances",
                        backgroundColor: "#e74a3b",
                        hoverBackgroundColor: "#c0392b",
                        data: outstandingData,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                    },
                    y: {
                        ticks: {
                            beginAtZero: true,
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: true
                    },
                },
            },
        });

        // Prepare Payment Sources Data
        const paymentLabels = paymentSourcesData.map(data => data.remarks);
        const paymentCounts = paymentSourcesData.map(data => data.count);

        // Payment Sources Pie Chart
        var paymentCtx = document.getElementById("paymentSourcesChart").getContext("2d");
        new Chart(paymentCtx, {
            type: "pie",
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentCounts,
                    backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc", "#f6c23e"],
                    hoverBackgroundColor: ["#2e59d9", "#17a673", "#2c9faf", "#f39c12"],
                }, ],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom"
                    },
                },
            },
        });
    });
</script>
