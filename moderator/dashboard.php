<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
  header('location: ./login.php');
}

require_once('../tools/functions.php');
require_once '../classes/account.class.php';
$account = new Account();

$user_summarry = $account->fetch_user_summary();

$appointmentStats = $account->fetch_appointment_statistics();

$totalAppointments = $appointmentStats['totalAppointments'];
$completedAppointments = $appointmentStats['completedAppointments'];
$cancelledAppointments = $appointmentStats['cancelledAppointments'];
$pendingAppointments = $appointmentStats['pendingAppointments'];
$avgDuration = $appointmentStats['avgDuration'];

$usersPerCampusPerYear = $account->fetch_users_per_campus_per_year();


?>

<html lang="en">
<?php
$title = 'Moderator | Dashboard';
include './includes/admin_head.php';
function getCurrentPage()
{
  return basename($_SERVER['PHP_SELF']);
}
?>

<body>
  <?php
  require_once('./includes/admin_header.php');
  ?>
  <?php
  require_once('./includes/admin_sidepanel.php');
  ?>

  <section id="dashboard" class="page-container">
    <h1 class="text-center">Overview</h1>

    <div class="container">
      <div class="row flex-md-nowrap row-cols-1 row-cols-md-4 mx-0 me-md-5 mb-4">
        <div class="col bg-primary mx-0 mx-md-2 p-3 text-white rounded-3 mb-3 mb-md-0">
          <div class="row g-3" style="height: 100%;">
            <div class="col-6 d-flex align-items-end justify-content-start">
              <i class='bx bx-user'></i>
            </div>
            <div class="col-6 d-flex flex-column align-items-end justify-content-start">
              <p class="fs-1 m-0"><?php echo $user_summarry['totalUsers']; ?></p>
              <p>Total Users</p>
            </div>
          </div>
        </div>

        <div class="col bg-primary mx-0 mx-md-2 p-3 text-white rounded-3 mb-3 mb-md-0">
          <div class="row g-3">
            <div class="col-6 d-flex align-items-end justify-content-start">
              <i class='bx bx-user-check'></i>
            </div>
            <div class="col-6 text-end">
              <p class="fs-1 m-0"><?php echo $user_summarry['totalActiveUsers']; ?></p>
              <p>Total Active Users</p>
            </div>
          </div>
        </div>

        <div class="col bg-primary mx-0 mx-md-2 p-3 text-white rounded-3 mb-3 mb-md-0">
          <div class="row g-3">
            <div class="col-6 d-flex align-items-end justify-content-start">
              <i class='bx bx-user-plus'></i>
            </div>
            <div class="col-6 text-end">
              <p class="fs-1 m-0"><?php echo $user_summarry['totalPatients']; ?></p>
              <p>Total Patients</p>
            </div>
          </div>
        </div>

        <div class="col bg-primary mx-0 mx-md-2 p-3 text-white rounded-3 mb-3 mb-md-0">
          <div class="row g-3">
            <div class="col-6 d-flex align-items-end justify-content-start">
              <i class='bx bx-user-plus'></i>
            </div>
            <div class="col-6 text-end">
              <p class="fs-1 m-0"><?php echo $user_summarry['totalDoctors']; ?></p>
              <p>Total Doctors</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container px-0 px-md-2">
      <div class="row mx-2 mb-3">
        <div class="col-12 col-lg-8">
          <div class="card h-100">
            <div class="card-header fs-4 text-center">Appoint Management</div>
            <div class="p-3">
              <h5>Appointment Overview</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">Total Appointments: <strong id="totalAppointments"></strong></li>
                <li class="list-group-item">Average Duration: <strong id="avgDuration"></strong></li>
                <li class="list-group-item">No-Show Rate: <strong id="noShowRate"></strong></li>
              </ul>
            </div>

            <hr>
            <div class="p-3">
              <h4 class="card-title">Appointment/s today</h4>
              <table id="appointment_table" class="table table_striped" style="width: 100%;">
                <thead>
                  <tr>
                    <th scope="col" width="3%">#</th>
                    <th scope="col">Patient Name</th>
                    <th scope="col">Doctor Name</th>
                    <th scope="col">Appointment Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $counter = 1;
                  foreach ($user_summarry['appointments'] as $item) {
                    $appointmentDateTime = $item['appointment_date'] . ' ' . $item['appointment_time'];
                  ?>
                    <tr>
                      <td><?= $counter ?></td>
                      <td><?= $item['patient_firstname'] . ' ' . $item['patient_lastname'] ?></td>
                      <td><?= $item['doctor_firstname'] . ' ' . $item['doctor_lastname'] ?></td>
                      <td><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></td>
                    </tr>
                  <?php
                    $counter++;
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card h-100">
            <div class="card-header fs-4 text-center">Appointment Insights</div>
            <div class="card-body">
              <canvas id="appointmentChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row mx-2 g-3">
        <div class="col-12 col-lg-4">
          <div class="card h-100">
            <div class="card-header fs-4 text-center">Campus Summary</div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item">Total Campuses: <strong id="totalCampuses"></strong></li>
                <li class="list-group-item">Total Users: <strong id="totalUsers"></strong></li>
                <li class="list-group-item">Top Campus: <strong id="topCampus"></strong></li>
                <li class="list-group-item">Year Range: <strong id="selectedYearRange"></strong></li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-8">
          <div class="card h-100">
            <div class="card-header fs-4 text-center">Campus Insights</div>
            <div class="card-body">
              <div class="p-3" id="nav-tabContent">
                <select id="yearSelect" class="form-select form-select-sm w-25"></select>
                <div class="tab-pane fade active show">
                  <canvas id="campusChart" class="chart" role="img"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </section>
  <script>
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
    
    var usersPerCampusPerYear = <?php echo json_encode($usersPerCampusPerYear); ?>;

    document.addEventListener("DOMContentLoaded", function() {
      var usersPerCampusPerYear = <?php echo json_encode($usersPerCampusPerYear); ?>;

      var allYears = [...new Set(usersPerCampusPerYear.map(item => parseInt(item.year_created)))].sort((a, b) => a - b);
      var campuses = [...new Set(usersPerCampusPerYear.map(item => item.campus_name))];

      var colorPalette = [
        "#ff5733", "#2eb346", "#3357FF", "#FF33A6", "#A633FF",
        "#33FFF6", "#FFC733", "#FF3380", "#4DFF33", "#3380FF"
      ];

      var ctx = document.getElementById("campusChart").getContext("2d");
      var campusChart;
      var yearSelect = document.getElementById("yearSelect");

      function generateYearRanges() {
        let minYear = Math.min(...allYears);
        let maxYear = Math.max(...allYears);
        let currentYear = new Date().getFullYear();

        let options = "";
        let selectedRange = "";

        for (let start = minYear; start <= maxYear; start += 5) {
          let end = Math.min(start + 4, maxYear);
          let range = `${start}-${end}`;
          options += `<option value="${range}">${range}</option>`;

          if (currentYear >= start && currentYear <= end) {
            selectedRange = range;
          }
        }

        yearSelect.innerHTML = options;
        yearSelect.value = selectedRange || `${maxYear - 4}-${maxYear}`;
      }

      function getFilteredYears(selectedRange) {
        let [startYear, endYear] = selectedRange.split("-").map(Number);
        return allYears.filter(year => year >= startYear && year <= endYear);
      }

      function updateCampusSummary(selectedRange) {
        let [startYear, endYear] = selectedRange.split("-").map(Number);

        // Filter data for the selected year range
        let filteredData = usersPerCampusPerYear.filter(item => {
          let year = parseInt(item.year_created);
          return year >= startYear && year <= endYear;
        });

        // Calculate total users
        let totalUsers = filteredData.reduce((sum, item) => sum + parseInt(item.total_users), 0);

        // Calculate total campuses
        let campusesSet = new Set(filteredData.map(item => item.campus_name));
        let totalCampuses = campusesSet.size;

        // Calculate top campus
        let campusUserCounts = {};
        filteredData.forEach(item => {
          let campus = item.campus_name;
          let users = parseInt(item.total_users);
          if (!campusUserCounts[campus]) {
            campusUserCounts[campus] = 0;
          }
          campusUserCounts[campus] += users;
        });

        let topCampus = Object.keys(campusUserCounts).reduce((a, b) => campusUserCounts[a] > campusUserCounts[b] ? a : b, "N/A");

        // Update the DOM
        document.getElementById("totalUsers").textContent = totalUsers;
        document.getElementById("topCampus").textContent = topCampus;
        document.getElementById("totalCampuses").textContent = totalCampuses;
        document.getElementById("selectedYearRange").textContent = `${startYear} - ${endYear}`;
      }

      function updateChart(selectedRange) {
        let years = getFilteredYears(selectedRange);

        var datasets = campuses.map((campus, index) => {
          let dataPoints = years.map(year => {
            let record = usersPerCampusPerYear.find(item => item.campus_name === campus && item.year_created == year);
            return record ? record.total_users : 0;
          });

          return {
            label: campus,
            data: dataPoints,
            borderWidth: 2,
            fill: false,
            borderColor: colorPalette[index % colorPalette.length],
            backgroundColor: colorPalette[index % colorPalette.length] + "80",
            tension: 0.4
          };
        });

        if (campusChart) {
          campusChart.destroy();
        }

        campusChart = new Chart(ctx, {
          type: "line",
          data: {
            labels: years,
            datasets: datasets
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: "top"
              }
            },
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });

        updateCampusSummary(selectedRange);
      }

      generateYearRanges();
      updateChart(yearSelect.value);

      yearSelect.addEventListener("change", function() {
        updateChart(this.value);
      });

      // Initial campus summary update
      updateCampusSummary(yearSelect.value);
    });
  </script>


</body>

</html>