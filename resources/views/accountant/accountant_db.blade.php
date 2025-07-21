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
                                            Total Initial Fees
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ number_format($totalInitialFees, 2) }}

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

                    <!-- Balance Due Bar Chart (8 columns) -->
                    <div class="col-xl-8 col-lg-7 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Balance Due per Program</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height: 450px;">
                                    <canvas id="balanceDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Initial Payments Pie Chart (4 columns) -->
                    <div class="col-xl-4 col-lg-5 mb-4">
                        <div class="card shadow">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Initial Payments per Program</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height: 450px;">
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
    const programData = @json($programFinancials);
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const labels = programData.map(item => item.program_name);
        const initialPayments = programData.map(item => parseFloat(item.total_initial_payment));
        const balances = programData.map(item => parseFloat(item.total_balance_due));

        // 📊 Balance Due Bar Chart (8 cols)
        const ctxBalance = document.getElementById('balanceDistributionChart').getContext('2d');
        new Chart(ctxBalance, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Balance Due',
                    data: balances,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    barPercentage: 0.6,
                    maxBarThickness: 100
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Balance Due per Program',
                        font: {
                            size: 18
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1000
                        }
                    }
                }
            }
        });

        // 🥧 Initial Payments Pie Chart (4 cols)
        const ctxInitial = document.getElementById('paymentSourcesChart').getContext('2d');
        new Chart(ctxInitial, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Initial Payment',
                    data: initialPayments,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(201, 203, 207, 0.7)'
                    ],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Initial Payments per Program',
                        font: {
                            size: 18
                        }
                    },
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    });
</script>
