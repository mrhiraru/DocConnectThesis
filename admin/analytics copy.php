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
$canceledAppointments = $appointmentStats['canceledAppointments'];
$pendingAppointments = $appointmentStats['pendingAppointments'];
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
      <div class="row mb-4">
        <div class="col-lg-6">
          <h4>User Statistics</h4>
          <canvas id="userStatsChart"></canvas>
        </div>
        <div class="col-lg-6">
          <ul>
            <li>Total Users: <strong id="totalUsers"></strong></li>
            <li>Active Users (Monthly): <strong id="activeUsers"></strong></li>
            <li>New Signups: <strong id="newSignups"></strong></li>
          </ul>
        </div>
      </div>

      <!-- Appointment Type Breakdown -->
      <div class="row mb-4">
        <div class="col-lg-6">
          <h4>Appointment Types</h4>
          <canvas id="appointmentTypeChart"></canvas>
        </div>
        <div class="col-lg-6">
          <ul>
            <li>Face-to-Face Appointments: <strong id="faceToFaceCount"></strong></li>
            <li>Online Appointments: <strong id="onlineCount"></strong></li>
          </ul>
        </div>
      </div>

      <!-- Appointment Insights -->
      <div class="row mb-4">
        <div class="col-lg-6">
          <h4>Appointment Insights</h4>
          <canvas id="appointmentChart"></canvas>
        </div>
        <div class="col-lg-6">
          <ul>
            <li>Total Appointments: <strong id="totalAppointments"></strong></li>
            <li>Average Duration: <strong id="avgDuration"></strong></li>
            <li>No-Show Rate: <strong id="noShowRate"></strong></li>
          </ul>
        </div>
      </div>

      <!-- Doctor Activity Reports -->
      <div class="row mb-4">
        <div class="col-lg-6">
          <h4>Doctor Activity</h4>
          <canvas id="doctorActivityChart"></canvas>
        </div>
        <div class="col-lg-6">
          <ul>
            <li>Active Doctors: <strong id="activeDoctors"></strong></li>
            <li>Avg. Response Time: <strong id="avgResponseTime"></strong></li>
          </ul>
        </div>
      </div>

      <!-- Health Concerns & Trends -->
      <div class="row mb-4">
        <div class="col-lg-6">
          <h4>Health Concerns & Trends</h4>
          <canvas id="healthConcernsChart"></canvas>
        </div>
        <div class="col-lg-6">
          <ul>
            <li>Top Health Concern: <strong id="topConcern"></strong></li>
            <li>Seasonal Trends: <strong id="seasonalTrends"></strong></li>
          </ul>
        </div>
      </div>

      <!-- System Performance & Security -->
      <div class="row mb-4">
        <div class="col-lg-6">
          <h4>System Performance</h4>
          <canvas id="systemPerformanceChart"></canvas>
        </div>
        <div class="col-lg-6">
          <ul>
            <li>Server Uptime: <strong id="serverUptime"></strong></li>
            <li>Error Rate: <strong id="errorRate"></strong></li>
          </ul>
        </div>
      </div>
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

      new Chart(document.getElementById("appointmentTypeChart"), {
        type: "pie",
        data: {
          labels: ["Face-to-Face", "Online"],
          datasets: [{
            data: [300, 200],
            backgroundColor: ["#4CAF50", "#2196F3"]
          }]
        }
      });

      // Appointment Insights Chart
      var totalAppointments = <?php echo json_encode($totalAppointments); ?>;
      var completedAppointments = <?php echo json_encode($completedAppointments); ?>;
      var canceledAppointments = <?php echo json_encode($canceledAppointments); ?>;
      var pendingAppointments = <?php echo json_encode($pendingAppointments); ?>;

      // Update text content with real data
      document.getElementById("totalAppointments").textContent = totalAppointments;
      document.getElementById("avgDuration").textContent = noShowRate; // Example placeholder
      document.getElementById("noShowRate").textContent = avgDuration; // Example placeholder

      // Appointment Insights Chart
      new Chart(document.getElementById("appointmentChart"), {
        type: "pie",
        data: {
          labels: ["Completed", "Canceled", "Pending"],
          datasets: [{
            data: [completedAppointments, canceledAppointments, pendingAppointments],
            backgroundColor: ["#8BC34A", "#F44336", "#FFEB3B"]
          }]
        }
      });

      // Doctor Activity Chart
      new Chart(document.getElementById("doctorActivityChart"), {
        type: "line",
        data: {
          labels: ["Jan", "Feb", "Mar", "Apr", "May"],
          datasets: [{
            label: "Appointments Per Doctor",
            data: [50, 60, 80, 90, 120],
            borderColor: "#673AB7",
            fill: false
          }]
        }
      });

      // Health Concerns & Trends Chart
      new Chart(document.getElementById("healthConcernsChart"), {
        type: "doughnut",
        data: {
          labels: ["Flu", "Anxiety", "Injuries", "Other"],
          datasets: [{
            data: [200, 150, 50, 100],
            backgroundColor: ["#FF5722", "#03A9F4", "#8BC34A", "#9E9E9E"]
          }]
        }
      });

      // System Performance Chart
      new Chart(document.getElementById("systemPerformanceChart"), {
        type: "bar",
        data: {
          labels: ["Server Uptime", "Load Time", "Error Rate"],
          datasets: [{
            label: "Performance Metrics",
            data: [99.8, 1.2, 0.5],
            backgroundColor: ["#00BCD4", "#FFEB3B", "#F44336"]
          }]
        }
      });

      // Dynamic text updates
      document.getElementById("totalAppointments").textContent = "450";
      document.getElementById("avgDuration").textContent = "25 min";
      document.getElementById("noShowRate").textContent = "15%";
      document.getElementById("activeDoctors").textContent = "120";
      document.getElementById("avgResponseTime").textContent = "5 min";
      document.getElementById("topConcern").textContent = "Flu";
      document.getElementById("seasonalTrends").textContent = "High flu cases in winter";
      document.getElementById("serverUptime").textContent = "99.8%";
      document.getElementById("errorRate").textContent = "0.5%";
    });
  </script>

</body>

</html>