<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
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

?>

<html lang="en">
<?php
$title = 'Admin | Dashboard';
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
        </div>
        <div class="col-12 col-lg-8">
          <div class="card h-100">
            <div class="card-header">
              <h4 class="text-center">Appoint Management</h4>
            </div>
            <div class="card-cody">
          
              <div class="p-3" id="nav-tabContent">
                <select id="yearSelect" class="form-select form-select-sm w-25" aria-label="form-select-sm example">
                  <option value="1">2021-2022</option>
                  <option value="2">2022-2023</option>
                  <option value="3">2023-2024</option>
                </select>
  
                <div class="tab-pane fade active show" id="nav-campus" role="tabpanel" aria-labelledby="nav-campus-tab">
                  <canvas id="campusChart" class="chart" role="img"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>

  <script src="./js/analytics-lineChart.js"></script>

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
  </script>

</body>

</html>