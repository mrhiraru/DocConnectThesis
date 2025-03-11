<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ./index.php');
}

require_once '../classes/account.class.php';
$account = new Account();

$userStats = $account->fetch_user_statistics();
$roleData = [];
$roleLabels = [
  0 => "Admins",
  1 => "Doctors",
  2 => "Moderators",
  3 => "Students",
  4 => "Alumni",
  5 => "Employees",
  6 => "Faculties",
  7 => "Dependents"
];

// Initialize role data with zero values
$roleCounts = array_fill(0, count($roleLabels), 0);

// Assign fetched values to the correct role index
foreach ($userStats['roles'] as $row) {
  $roleIndex = intval($row['user_role']);
  if (isset($roleLabels[$roleIndex])) {
    $roleCounts[$roleIndex] = intval($row['count']);
  }
}

$totalUsers = $userStats['totalUsers'];
$activeUsers = $userStats['activeUsers'];
$newSignups = $userStats['newSignups'];

$appointmentStats = $account->fetch_appointment_statistics();
$totalAppointments = $appointmentStats['totalAppointments'];
$completedAppointments = $appointmentStats['completedAppointments'];
$cancelledAppointments = $appointmentStats['cancelledAppointments'];
$pendingAppointments = $appointmentStats['pendingAppointments'];
$avgDuration = $appointmentStats['avgDuration'];

// Fetch doctor statistics
$doctorStats = $account->fetch_doctor_statistics();
$activeDoctors = $doctorStats['activeDoctors'];
$doctorTrends = $doctorStats['doctorTrends'];

$healthStats = $account->fetch_health_concerns_and_trends();
$topConcern = $healthStats['topConcern'];
$topConcernMonth = $healthStats['topConcernMonth'];
$healthConcernLabels = $healthStats['healthConcernLabels'];
$healthConcernData = $healthStats['healthConcernData'];

// Fetch diagnosis trends data
$year = isset($_GET['year']) ? $_GET['year'] : null;
$month = isset($_GET['month']) ? $_GET['month'] : null;
$diagnosisTrends = $account->fetch_diagnosis_trends($month, $year);

// Encode the data as JSON
$diagnosisTrendsJson = json_encode($diagnosisTrends);

?>

<html lang="en">
<?php
$title = 'Admin | Analytics & Reports';
include './includes/admin_head.php';
function getCurrentPage()
{
  return basename($_SERVER['PHP_SELF']);
}
?>

<body>
  <?php
  require_once('./includes/admin_header.php');
  require_once('./includes/admin_sidepanel.php');
  ?>

  <section id="analytics" class="page-container">
    <div class="container-fluid">
      <h2 class="text-center my-4">Analytics & Reports</h2>

      <!-- User Statistics -->
      <div class="row mb-lg-4">
        <div class="col-lg-5 mb-3 mb-lg-0">
          <div class="card h-100">
            <div class="card-header">
              <h4 class="card-title">User Statistics</h4>
            </div>
            <div class="card-body">
              <canvas id="userStatsChart"></canvas>
              <h4 class="card-title">User Overview</h4>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">Total Users: <strong id="totalUsers"></strong></li>
                <li class="list-group-item">Active Users (Monthly): <strong id="activeUsers"></strong></li>
                <li class="list-group-item">New Signups: <strong id="newSignups"></strong></li>
              </ul>
            </div>
          </div>
        </div>
        <!-- Appointment Insights -->
        <div class="col-lg-7 mb-3 mb-lg-0">
          <div class="card h-100">
            <div class="card-header">
              <h4 class="card-title">Doctor Activity</h4>
            </div>
            <div class="card-body">
              <canvas id="doctorActivityChart"></canvas>
              <h4 class="card-title">Doctor Overview</h4>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">Active Doctors: <strong id="activeDoctors"></strong></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <!-- Appointment Insights -->
        <div class="col-lg-6 mb-3 mb-lg-0">
          <div class="card h-100">
            <div class="card-header">
              <h4 class="card-title">Appointment Insights</h4>
            </div>
            <div class="card-body">
              <canvas id="appointmentChart"></canvas>
              <h4 class="card-title">Appointment Overview</h4>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">Total Appointments: <strong id="totalAppointments"></strong></li>
                <li class="list-group-item">Average Duration: <strong id="avgDuration"></strong></li>
                <li class="list-group-item">No-Show Rate: <strong id="noShowRate"></strong></li>
              </ul>
            </div>
          </div>
        </div>
        <!-- Health Concerns & Trends -->
        <div class="col-lg-6 mb-3 mb-lg-0">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h4 class="card-title">Health Concerns & Trends</h4>
            </div>
            <div class="card-body">
              <canvas id="healthConcernsChart"></canvas>
              <h4 class="card-title">Health Overview</h4>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">Top Health Concern: <strong id="topConcern"></strong></li>
                <li class="list-group-item">Top Health Concern This Month: <strong id="topConcernMonth"></strong></li>
                <!-- <li class="list-group-item">Seasonal Trends: <strong id="seasonalTrends"></strong></li> -->
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- Diagnosis Trends -->
      <div class="row mb-4">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Diagnosis Trends</h4>
            </div>
            <div class="card-body">
              <!-- Dropdowns for selecting year and month -->
              <div class="row mb-3">
                <div class="col-md-3">
                  <label for="yearSelect">Select Year:</label>
                  <select id="yearSelect" class="form-control">
                    <?php
                    $currentYear = date('Y');
                    for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                      echo "<option value='$i'>$i</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="monthSelect">Select Month:</label>
                  <select id="monthSelect" class="form-control">
                    <option value="">All Months</option>
                    <?php
                    $months = [
                      '01' => 'January',
                      '02' => 'February',
                      '03' => 'March',
                      '04' => 'April',
                      '05' => 'May',
                      '06' => 'June',
                      '07' => 'July',
                      '08' => 'August',
                      '09' => 'September',
                      '10' => 'October',
                      '11' => 'November',
                      '12' => 'December'
                    ];
                    foreach ($months as $key => $month) {
                      echo "<option value='$key'>$month</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>

              <!-- Bar Chart for Diagnosis Trends -->
              <canvas id="diagnosisTrendsChart" style="max-height: 400px;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- System Performance & Security -->
      <!-- <div class="row mb-4">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">System Performance</h4>
            </div>
            <div class="card-body">
              <canvas id="systemPerformanceChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Performance Overview</h4>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item">Server Uptime: <strong id="serverUptime"></strong></li>
                <li class="list-group-item">Error Rate: <strong id="errorRate"></strong></li>
              </ul>
            </div>
          </div>
        </div>
      </div> -->
    </div>
  </section>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var userStatsData = <?php echo json_encode($roleData); ?>;

      // User Statistics Chart
      var userStatsData = <?php echo json_encode($roleCounts); ?>;
      var totalUsers = <?php echo json_encode($totalUsers); ?>;
      var activeUsers = <?php echo json_encode($activeUsers); ?>;
      var newSignups = <?php echo json_encode($newSignups); ?>;

      // Update text content with real data
      document.getElementById("totalUsers").textContent = totalUsers;
      document.getElementById("activeUsers").textContent = activeUsers;
      document.getElementById("newSignups").textContent = newSignups;

      // User Statistics Chart
      new Chart(document.getElementById("userStatsChart"), {
        type: "bar",
        data: {
          labels: ["Admins", "Doctors", "Moderators", "Students", "Alumni", "Employees", "Faculties", "Dependents"],
          datasets: [{
            label: "Total Users",
            data: userStatsData,
            backgroundColor: ["#4CAF50", "#8BC34A", "#CDDC39", "#FFEB3B", "#FFC107", "#FF9800", "#2196F3", "#9C27B0"]
          }]
        }
      });

      // Appointment Insights Chart
      var totalAppointments = <?php echo json_encode($totalAppointments); ?>;
      var completedAppointments = <?php echo json_encode($completedAppointments); ?>;
      var cancelledAppointments = <?php echo json_encode($cancelledAppointments); ?>;
      var pendingAppointments = <?php echo json_encode($pendingAppointments); ?>;
      var avgDuration = <?php echo json_encode($avgDuration); ?>;

      document.getElementById("totalAppointments").textContent = totalAppointments;
      document.getElementById("avgDuration").textContent = avgDuration + " min";
      document.getElementById("noShowRate").textContent = cancelledAppointments;

      new Chart(document.getElementById("appointmentChart"), {
        type: "pie",
        data: {
          labels: ["Completed", "Cancelled", "Pending"],
          datasets: [{
            data: [completedAppointments, cancelledAppointments, pendingAppointments],
            backgroundColor: ["#8BC34A", "#F44336", "#FFEB3B"]
          }]
        }
      });

      // Doctor Activity Line Chart
      var activeDoctors = <?php echo json_encode($activeDoctors); ?>;
      var doctorTrends = <?php echo json_encode($doctorTrends); ?>;

      var trendLabels = doctorTrends.map(item => item.month);
      var trendData = doctorTrends.map(item => item.count);

      document.getElementById("activeDoctors").textContent = activeDoctors;

      new Chart(document.getElementById("doctorActivityChart"), {
        type: "line",
        data: {
          labels: trendLabels,
          datasets: [{
            label: "Active Doctors Over Time",
            data: trendData,
            borderColor: "#673AB7",
            backgroundColor: "rgba(103, 58, 183, 0.2)",
            fill: true
          }]
        }
      });

      // Health Concerns & Trends Chart
      document.getElementById("topConcern").textContent = <?php echo json_encode($topConcern); ?>;
      document.getElementById("topConcernMonth").textContent = <?php echo json_encode($topConcernMonth); ?>;

      new Chart(document.getElementById("healthConcernsChart"), {
        type: "doughnut",
        data: {
          labels: <?php echo json_encode($healthConcernLabels); ?>,
          datasets: [{
            data: <?php echo json_encode($healthConcernData); ?>,
            backgroundColor: ["#FF5722", "#03A9F4", "#8BC34A", "#9E9E9E"]
          }]
        }
      });
      // document.getElementById("seasonalTrends").textContent = "Seasonal trends data available";

      // System Performance Chart
      // new Chart(document.getElementById("systemPerformanceChart"), {
      //   type: "bar",
      //   data: {
      //     labels: ["Server Uptime", "Load Time", "Error Rate"],
      //     datasets: [{
      //       label: "Performance Metrics",
      //       data: [99.8, 1.2, 0.5],
      //       backgroundColor: ["#00BCD4", "#FFEB3B", "#F44336"]
      //     }]
      //   }
      // });

      // document.getElementById("serverUptime").textContent = "99.8%";
      // document.getElementById("errorRate").textContent = "0.5%";

      const yearSelect = document.getElementById('yearSelect');
      const monthSelect = document.getElementById('monthSelect');
      const diagnosisTrendsChartCanvas = document.getElementById('diagnosisTrendsChart');
      let diagnosisTrendsChart;

      const diagnosisTrends = <?php echo $diagnosisTrendsJson; ?>;

      function updateDiagnosisTrendsChart(conditionCounts) {
        const labels = [];
        const data = [];

        for (const [condition, count] of Object.entries(conditionCounts)) {
          if (count > 0) {
            labels.push(condition);
            data.push(count);
          }
        }

        if (diagnosisTrendsChart) {
          diagnosisTrendsChart.destroy();
        }

        diagnosisTrendsChart = new Chart(diagnosisTrendsChartCanvas, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Diagnosis Count',
              data: data,
              backgroundColor: ["#4CAF50", "#8BC34A", "#CDDC39", "#FFEB3B", "#FFC107", "#FF9800", "#2196F3", "#9C27B0"],
              borderColor: '#0D47A100',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            },
            responsive: true,
            maintainAspectRatio: false,
          }
        });
      }

      function fetchDiagnosisTrends(year, month) {
        const filteredData = diagnosisTrends.diagnosisTrends.filter(item => {
          const itemYear = item.month.substring(0, 4);
          const itemMonth = item.month.substring(5, 7);

          if (year && month) {
            return itemYear === year && itemMonth === month;
          } else if (year) {
            return itemYear === year;
          } else {
            return true;
          }
        });

        const conditionCounts = {
          ...diagnosisTrends.conditionCounts
        };
        for (const condition in conditionCounts) {
          conditionCounts[condition] = 0; // Reset counts
        }

        filteredData.forEach(item => {
          const conditions = item.diagnosis.split(',').map(cond => cond.trim());
          conditions.forEach(condition => {
            if (conditionCounts[condition] !== undefined) {
              conditionCounts[condition] += item.count;
            }
          });
        });

        updateDiagnosisTrendsChart(conditionCounts);
      }

      yearSelect.addEventListener('change', () => {
        const year = yearSelect.value;
        const month = monthSelect.value;
        fetchDiagnosisTrends(year, month);
      });

      monthSelect.addEventListener('change', () => {
        const year = yearSelect.value;
        const month = monthSelect.value;
        fetchDiagnosisTrends(year, month);
      });

      fetchDiagnosisTrends(yearSelect.value, monthSelect.value);
    });
  </script>

</body>

</html>