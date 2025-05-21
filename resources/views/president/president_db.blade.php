@extends('layouts.main')

@section('tab_title', 'President Dashboard')
@section('president_sidebar')
    @include('president.president_sidebar')
@endsection

@section('content')

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            @include('layouts.topbar')

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">President's Dashboard</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-download fa-sm text-white-50"></i> Download Financial Report
                    </a>
                </div>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">📊 Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                        </a>
                    </div>

                    <!-- Row: Revenue Dashboard -->
                    <div class="row">
                        <!-- Line Chart for Monthly/Yearly Trends -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">💰 Revenue Trends</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart for Scholarship/Discount Impact -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">🎓 Scholarships vs Discounts</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="scholarshipPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row: Enrollment Analytics -->
                    <div class="row">
                        <!-- Heatmap-style Enrollment Table -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">📊 Program-wise Enrollment Heatmap</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered text-center">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Program</th>
                                                <th>1st Year</th>
                                                <th>2nd Year</th>
                                                <th>3rd Year</th>
                                                <th>4th Year</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>BSIT</td>
                                                <td class="bg-success text-white">120</td>
                                                <td class="bg-warning text-dark">95</td>
                                                <td class="bg-danger text-white">70</td>
                                                <td class="bg-success text-white">100</td>
                                            </tr>
                                            <tr>
                                                <td>BSBA</td>
                                                <td class="bg-success text-white">90</td>
                                                <td class="bg-warning text-dark">65</td>
                                                <td class="bg-danger text-white">40</td>
                                                <td class="bg-success text-white">80</td>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row: Financial Alerts -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 bg-danger">
                                    <h6 class="m-0 font-weight-bold text-white">🚨 Top 10 Unpaid Balances (₱10,000+)</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Student A <span class="badge badge-danger badge-pill">₱15,000</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Student B <span class="badge badge-danger badge-pill">₱12,500</span>
                                        </li>
                                        <!-- Add more unpaid entries -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

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
