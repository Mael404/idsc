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


                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">📊 Balance Due</h6>
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
                                    <h6 class="m-0 font-weight-bold text-primary"> Program-wise Enrollment Heatmap</h6>
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
                                    <h6 class="m-0 font-weight-bold text-white"> Top 10 Unpaid Balances (₱10,000+)</h6>
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

            <!-- End of Main Content -->

            @include('layouts.footer')

        </div>
        <!-- End of Content Wrapper -->
    @endsection



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('scholarshipPieChart').getContext('2d');

            // Fetch balance due data from the backend
            fetch('/api/balance-due') // Update with the correct endpoint
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.semester); // X-axis labels (semesters)
                    const balanceDue = data.map(item => item.total_balance_due); // Values (total balance due)

                    // Render the pie chart
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Balance Due',
                                data: balanceDue,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching balance due data:', error));
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');

            // Fetch revenue data from the backend
            fetch('/api/revenue-trends') // Update with the correct endpoint
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.semester); // X-axis labels (semesters)
                    const revenue = data.map(item => item.total_revenue); // Y-axis values (total revenue)

                    // Render the chart
                    new Chart(ctx, {
                        type: 'line', // Use 'bar' or 'line' as desired
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Revenue',
                                data: revenue,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching revenue trends:', error));
        });
    </script>
