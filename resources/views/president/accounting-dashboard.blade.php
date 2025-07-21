@extends('layouts.main')

@section('tab_title', 'Accounting Dashboard')
@section('president_sidebar')
@include('president.president_sidebar')
@endsection

@section('content')

<div id="content-wrapper" class="d-flex flex-column">

    <div id="content">

        @include('layouts.topbar')

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="mb-4 d-sm-flex align-items-center justify-content-between">
                <h1 class="mb-0 text-gray-800 h3">Accountant Dashboard</h1>
                <a href="#" class="shadow-sm d-none d-sm-inline-block btn btn-sm btn-primary">
                    <i class="fas fa-download fa-sm text-white-50"></i> Download Financial Report
                </a>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Total Tuition Fees -->
                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 text-xs font-weight-bold text-primary text-uppercase">
                                        Total Initial Fees
                                    </div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">
                                        {{ number_format($totalInitialFees, 2) }}

                                    </div>

                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-wallet fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outstanding Balances -->
                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-danger h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 text-xs font-weight-bold text-danger text-uppercase">
                                        Outstanding Balances</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">
                                        {{ number_format($outstandingBalances, 2) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-exclamation-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 text-xs font-weight-bold text-success text-uppercase">
                                        Recent Payments</div>
                                    <div class="mb-0 text-gray-800 h6">
                                        @foreach ($recentPayments as $payment)
                                        OR#: {{ $payment->or_number }} -
                                        {{ number_format($payment->amount, 2) }}<br>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-receipt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Full Payments -->
                <div class="mb-4 col-xl-3 col-md-6">
                    <div class="py-2 shadow card border-left-warning h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="mr-2 col">
                                    <div class="mb-1 text-xs font-weight-bold text-warning text-uppercase">
                                        Fully Paid Students</div>
                                    <div class="mb-0 text-gray-800 h5 font-weight-bold">
                                        {{ $fullPaymentsCount }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="text-gray-300 fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Content Row -->
            <div class="row">

                <!-- Balance Due Bar Chart (8 columns) -->
                <div class="mb-4 col-xl-8 col-lg-7">
                    <div class="shadow card">
                        <div class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
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
                <div class="mb-4 col-xl-4 col-lg-5">
                    <div class="shadow card">
                        <div class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
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