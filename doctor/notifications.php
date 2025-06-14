<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
  header('location: ./index.php');
}

?>
<html lang="en">
<?php
$title = 'Settings | Appointment';
$notification = 'active';
include '../includes/head.php';
?>

<body>
  <?php
  require_once('../includes/header-doctor.php');
  ?>

  <div class="container-fluid">
    <div class="row">
      <?php
      require_once('../includes/sidepanel-doctor.php');
      ?>


      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <section id="notification" class="my-3">

          <div class="container">
            <div class="row justify-content-center">
              <div class="col-12 col-md-9 shadow-lg p-3 rounded-2">

                <li class="header mx-3 d-flex align-items-center justify-content-between">
                  <h4>Notification</h4>
                  <div class="dropdown">
                    <button class="btn btn-link btn-light p-0" type="button" id="mark-all" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bx bx-dots-horizontal-rounded fs-3 link-dark"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="mark-all">
                      <li><a class="dropdown-item" href="#">Mark all as read</a></li>
                      <li><a class="dropdown-item" href="#">Another action</a></li>
                      <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                  </div>
                </li>

                <hr>

                <div class="list-group rounded-0">
                  <a href="#" class="list-group-item list-group-item-action border-0">
                    <li class="d-flex align-items-center">
                      <i class='bx bx-check-circle me-3 fs-1 text-success'></i>
                      <div>
                        <div class="fw-bold fs-4">Operation Successful</div>
                        <small class="text-muted">Your request was processed successfully.</small>
                      </div>
                    </li>
                  </a>
                  <a href="#" class="list-group-item list-group-item-action border-0">
                    <li class="d-flex align-items-center">
                      <i class='bx bx-x-circle me-3 fs-1 text-danger'></i>
                      <div>
                        <div class="fw-bold fs-4">Operation Failed</div>
                        <small class="text-muted">There was an error processing your request.</small>
                      </div>
                    </li>
                  </a>
                  <a href="#" class="list-group-item list-group-item-action border-0">
                    <li class="d-flex align-items-center">
                      <i class='bx bx-error-circle me-3 fs-1 text-warning'></i>
                      <div>
                        <div class="fw-bold fs-4">Warning</div>
                        <small class="text-muted">Please check the details carefully.</small>
                      </div>
                    </li>
                  </a>
                  <a href="#" class="list-group-item list-group-item-action border-0">
                    <li class="d-flex align-items-center">
                      <i class='bx bx-info-circle me-3 fs-1 text-info'></i>
                      <div>
                        <div class="fw-bold fs-4">Information</div>
                        <small class="text-muted">Here is some important information.</small>
                      </div>
                    </li>
                  </a>
                  <a href="#" class="list-group-item list-group-item-action border-0">
                    <li class="d-flex align-items-center">
                      <i class='bx bx-alarm me-3 fs-1 text-warning'></i>
                      <div>
                        <div class="fw-bold fs-4">Reminder</div>
                        <small class="text-muted">Lorem ipsum dolor sit amet consectetur.</small>
                      </div>
                    </li>
                  </a>

                  <hr class="my-3">

                  <a href="./notifications">
                    <button type="button" class="btn btn-outline-primary w-100">See previous notification</button>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</body>

</html>