  <!-- BACK END FUNCTION IS IN THE BILLING CONTROLLER -->
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

                  <!-- Page Headifng -->
                  <div class="d-sm-flex align-items-center justify-content-between mb-4">
                      <h1 class="h3 mb-0 text-gray-800">President's Dashboard</h1>
                      <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                          <i class="fas fa-download fa-sm text-white-50"></i> Download Financial Report
                      </a>
                  </div>

                  <!-- Example: Export for Daily Sales -->
                  <button class="btn btn-success btn-sm mb-2" onclick="exportTableToExcel('dailySalesTable', 'Daily_Sales')">
                      Export Daily Sales
                  </button>

                  <!-- Example: Export for Enrollment Heatmap -->
                  <button class="btn btn-success btn-sm mb-2"
                      onclick="exportTableToExcel('enrollmentHeatmap', 'Enrollment_Heatmap')">
                      Export Enrollment Heatmap
                  </button>

                  <!-- Example: Export for Unpaid Balances -->
                  <button class="btn btn-success btn-sm mb-2" onclick="exportListToExcel('unpaidList', 'Unpaid_Balances')">
                      Export Unpaid Balances
                  </button>

                  <!-- Row: Revenue Dashboard -->
                  <div class="row">
                      <!-- Left Column: Two Small Cards -->
                      <div class="col-xl-4 col-lg-5">
                          <!-- Balance Due Card -->
                          <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                  <h6 class="m-0 font-weight-bold text-primary">📊 Balance Due</h6>
                              </div>
                              <div class="card-body">
                                  <canvas id="scholarshipPieChart"></canvas>
                              </div>
                          </div>

                          <!-- Daily Sales Card -->
                          <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                  <h6 class="m-0 font-weight-bold text-primary">💵 Daily Sales (Current Semester)</h6>
                              </div>
                              <div class="card-body">
                                  @php
                                      $activeSemester = \App\Models\SchoolYear::where('is_active', 1)->first();

                                      $totalSales = \App\Models\Payment::where(
                                          'school_year',
                                          $activeSemester->name ?? '',
                                      )
                                          ->where('semester', $activeSemester->semester ?? '')
                                          ->sum('amount');

                                      $regularPayments = \App\Models\Payment::where(
                                          'school_year',
                                          $activeSemester->name ?? '',
                                      )
                                          ->where('semester', $activeSemester->semester ?? '')
                                          ->whereNull('payment_type')
                                          ->sum('amount');

                                      $otherPayments = \App\Models\Payment::where(
                                          'school_year',
                                          $activeSemester->name ?? '',
                                      )
                                          ->where('semester', $activeSemester->semester ?? '')
                                          ->where('payment_type', 'others')
                                          ->sum('amount');
                                  @endphp

                                  <div class="table-responsive">
                                      <table class="table table-bordered" id="dailySalesTable">

                                          <thead>
                                              <tr>
                                                  <th>Payment Type</th>
                                                  <th>Amount</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <tr>
                                                  <td>Regular Payments</td>
                                                  <td>₱{{ number_format($regularPayments, 2) }}</td>
                                              </tr>
                                              <tr>
                                                  <td>Other Payments</td>
                                                  <td>₱{{ number_format($otherPayments, 2) }}</td>
                                              </tr>
                                              <tr class="font-weight-bold">
                                                  <td>Total Sales</td>
                                                  <td>₱{{ number_format($totalSales, 2) }}</td>
                                              </tr>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div class="mt-3 text-muted">
                                      <small>Current Semester: {{ $activeSemester->name ?? '' }} -
                                          {{ $activeSemester->semester ?? '' }}</small>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <!-- Right Column: Revenue Chart -->
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
                                  <table class="table table-bordered text-center" id="enrollmentHeatmap">

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
                                          @foreach ($programs as $program)
                                              <tr>
                                                  <td>{{ $program->name }}</td>
                                                  @foreach ($yearLevels as $year)
                                                      @php
                                                          $count = $enrollmentData[$program->name][$year->name] ?? 0;
                                                          // Determine heatmap color
                                                          $class =
                                                              $count >= 100
                                                                  ? 'bg-success text-white'
                                                                  : ($count >= 60
                                                                      ? 'bg-warning text-dark'
                                                                      : 'bg-danger text-white');
                                                      @endphp
                                                      <td class="{{ $class }}">{{ $count }}</td>
                                                  @endforeach
                                              </tr>
                                          @endforeach
                                      </tbody>

                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Row: Financial Alerts -->
                  <div class="row mt-4">
                      <div class="col-xl-12">
                          <div class="card shadow mb-4">
                              <div class="card-header py-3 bg-danger">
                                  <h6 class="m-0 font-weight-bold text-white">
                                      Top 10 Unpaid Balances (₱10,000+) — {{ $activeSY->name ?? 'No Active School Year' }}
                                  </h6>
                              </div>
                              <div class="card-body">
                                  @if ($topUnpaid->isEmpty())
                                      <p class="text-muted">No unpaid balances over ₱10,000 found.</p>
                                  @else
                                      <ul class="list-group" id="unpaidList">

                                          @foreach ($topUnpaid as $billing)
                                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                                  {{ $billing->student->full_name ?? $billing->student_id }}
                                                  <span
                                                      class="badge badge-danger badge-pill">₱{{ number_format($billing->balance_due, 2) }}</span>
                                              </li>
                                          @endforeach
                                      </ul>
                                  @endif
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
      </div>
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
                  // Combine school_year and semester for labels
                  const labels = data.map(item => `${item.school_year} - ${item.semester}`);
                  const revenue = data.map(item => item.total_revenue);

                  // Render the chart
                  new Chart(ctx, {
                      type: 'line',
                      data: {
                          labels: labels,
                          datasets: [{
                              label: 'Total Revenue',
                              data: revenue,
                              backgroundColor: 'rgba(54, 162, 235, 0.2)',
                              borderColor: 'rgba(54, 162, 235, 1)',
                              borderWidth: 1,
                              tension: 0.1 // Makes the line slightly curved
                          }]
                      },
                      options: {
                          responsive: true,
                          plugins: {
                              legend: {
                                  position: 'top',
                              },
                              tooltip: {
                                  callbacks: {
                                      label: function(context) {
                                          return `Revenue: ₱${context.parsed.y.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                                      }
                                  }
                              }
                          },
                          scales: {
                              y: {
                                  beginAtZero: true,
                                  ticks: {
                                      callback: function(value) {
                                          return '₱' + value.toLocaleString('en-PH');
                                      }
                                  }
                              }
                          }
                      }
                  });
              })
              .catch(error => console.error('Error fetching revenue trends:', error));
      });
  </script>
<script>
    function exportTableToExcel(tableId, filename = '') {
        let table = document.getElementById(tableId);
        let wb = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});
        XLSX.writeFile(wb, filename + ".xlsx");
    }

    function exportListToExcel(listId, filename = '') {
        const list = document.getElementById(listId);
        const rows = [['Name', 'Balance']];

        list.querySelectorAll('li').forEach(item => {
            const name = item.childNodes[0].textContent.trim();
            const balance = item.querySelector('span').textContent.trim();
            rows.push([name, balance]);
        });

        const ws = XLSX.utils.aoa_to_sheet(rows);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
        XLSX.writeFile(wb, filename + ".xlsx");
    }
</script>
