<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
  header('location: ./index.php');
}

include_once './helpers/data_masking.php';
require_once '../classes/account.class.php';

$account = new Account();
$record = $account->fetchUserData($_GET['account_id']);

?>

<html lang="en">
<?php
$title = 'Campuses | View User';
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

  <section id="add_campus" class="page-container">
    <div class="row">

      <div class="col-2"></div>

      <div class="col-12 col-lg-8">
        <form>
          <div class="border shadow p-4 mb-5 bg-body rounded">
            <div class="d-flex align-items-center">
              <button class="btn p-0 me-auto" onclick="event.preventDefault(); window.history.back();">
                <i class='bx bx-chevron-left-circle fs-3 link'></i>
              </button>
              <h4 class="text-center w-100 mb-0"><?= $record['fullname'] ?></h4></span></h5>
            </div>

            <hr class="mx-3 my-4">

            <div class="row row-cols-1 row-cols-lg-2">
              <div class="col d-flex ">
                <strong class="me-2">Account-ID:</strong>
                <p><?= $record['account_id'] ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Email:</strong>
                <p><?= maskEmail($record['email']) ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Phone:</strong>
                <p><?= maskPhone($record['contact']) ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Date of Birth:</strong>
                <p><?= $record['birthdate'] ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Registration Date:</strong>
                <p><?= $record['is_created'] ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Verification Status:</strong>
                <p><?= $record['verification_status'] ?></p>
              </div>

              <!-- <div class="col d-flex ">
                <strong class="me-2">Departmnt:</strong>
                <p>CCS</p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Number of Appointment(s):</strong>
                <p>01</p>
              </div> -->
            </div>

            <hr class="mb-3">

            <div class="col d-flex align-items-center">
              <strong class="me-3">Profile photo:</strong>
              <img src="<?= $record['account_image'] ?>" alt="Profile Photo" class="rounded-2" width="100" height="100">
            </div>

            <!-- <div class="row">
              <div class="col d-flex ">
                <strong class="me-2">Allergies:</strong>
                <p>04</p>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="row row-cols-2 row-cols-md-3">
                    <div class="col">
                      <div class="d-flex mx-1 mx-md-3">
                        <p class="m-0 text-muted text-start">
                          Peicillin -
                          <span class="ms-1">
                            <p class="m-0 text-danger">High</p>
                          </span>
                        </p>
                      </div>
                    </div>
                    <div class="col">
                      <div class="d-flex mx-1 mx-md-3">
                        <p class="m-0 text-muted text-start">
                          Dust -
                          <span class="ms-1">
                            <p class="m-0 text-warning">Medium</p>
                          </span>
                        </p>
                      </div>
                    </div>
                    <div class="col">
                      <div class="d-flex mx-1 mx-md-3">
                        <p class="m-0 text-muted text-start">
                          Pollen -
                          <span class="ms-1">
                            <p class="m-0 text-success">Low</p>
                          </span>
                        </p>
                      </div>
                    </div>
                    <div class="col">
                      <div class="d-flex mx-1 mx-md-3">
                        <p class="m-0 text-muted text-start">
                          CatFur -
                          <span class="ms-1">
                            <p class="m-0 text-warning">Medium</p>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </form>

      </div>

      <div class="col-2"></div>

    </div>
  </section>
</body>

</html>