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
          <div class="row g-3">
            <div class="col-6 d-flex align-items-end justify-content-start">
              <i class='bx bx-user'></i>
            </div>
            <div class="col-6 text-end">
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
              <p>Total Active Users.</p>
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
      <div class="row mx-2">
        <div class="col-12 col-lg-4">
          <div class="border border-2 border-dark-subtle shadow-sm p-3 h-100">
            <h3 class="text-center mb-4">Appoint Management</h3>
            <h5>Appointments today: </h5>
          </div>
        </div>

        <div class="col-12 col-lg-8">
          <nav>
            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
              <button class="nav-link  active" id="nav-campus-tab" data-bs-toggle="tab" data-bs-target="#nav-campus" type="button" role="tab" aria-controls="nav-campus" aria-selected="true">Campus</button>
              <button class="nav-link" id="nav-type-tab" data-bs-toggle="tab" data-bs-target="#nav-type" type="button" role="tab" aria-controls="nav-type" aria-selected="false">Type</button>
            </div>
          </nav>
          <div class="tab-content p-3 border bg-white" id="nav-tabContent">
            <select id="yearSelect" class="form-select form-select-sm w-25" aria-label=".form-select-sm example">
              <option value="1">2021-2022</option>
              <option value="2">2022-2023</option>
              <option value="3">2023-2024</option>
            </select>

            <div class="tab-pane fade active show" id="nav-campus" role="tabpanel" aria-labelledby="nav-campus-tab">
              <canvas id="campusChart" class="chart" role="img"></canvas>
            </div>
            <div class="tab-pane fade" id="nav-type" role="tabpanel" aria-labelledby="nav-type-tab">
              <canvas id="typeChart" class="chart" role="img"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="./js/analytics-lineChart.js"></script>

</body>

</html>