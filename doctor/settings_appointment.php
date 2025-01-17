<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
  header('location: ../index.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$account = new Account();
if (isset($_POST['save'])) {
  $account->start_wt = htmlentities($_POST['start_wt']);
  $account->end_wt = htmlentities($_POST['end_wt']);
  $account->start_day = htmlentities($_POST['start_day']);
  $account->end_day = htmlentities($_POST['end_day']);
  $account->account_id = $_SESSION['account_id'];


  if (validate_field($account->start_wt &&
    $account->end_wt)) {
    if ($account->update_working_time()) {
      $success = 'success';

      $_SESSION['start_wt'] = $account->start_wt;
      $_SESSION['end_wt'] = $account->end_wt;
      $_SESSION['start_day'] = $account->start_day;
      $_SESSION['end_day'] = $account->end_day;
    } else {
      echo 'An error occured while adding in the database.';
    }
  } else {
    $success = 'failed';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Settings | Appointment';
$setting = 'active';
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
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Account Settings</h1>
        </div>

        <?php
        require_once('../includes/doctorSetting_Nav.php')
        ?>

        <div class="card bg-body-tertiary mb-4">
          <div class="card-body">
            <form method="post" action="">

              <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-group mb-2">
                    <label for="work-hours">Day</label>
                    <div class="d-flex align-items-center">
                      <select id="start_day" class="form-select" name="start_day" required>
                        <option value="">Select Day </option>
                        <?= $_SESSION['start_day'] ?>
                        <option value="Sunday" <?php if ((isset($_POST['start_day']) && $_POST['start_day'] == "Sunday")) {
                                                  echo 'selected';
                                                } else if ($_SESSION['start_day'] == "Sunday") {
                                                  echo "selected";
                                                } ?>>Sunday</option>
                        <option value="Monday" <?php if ((isset($_POST['start_day']) && $_POST['start_day'] == "Monday")) {
                                                  echo 'selected';
                                                } else if ($_SESSION['start_day'] == "Monday") {
                                                  echo "selected";
                                                } ?>>Monday</option>
                        <option value="Tuesday" <?php if ((isset($_POST['start_day']) && $_POST['start_day'] == "Tuesday")) {
                                                  echo 'selected';
                                                } else if ($_SESSION['start_day'] == "Tuesday") {
                                                  echo "selected";
                                                } ?>>Tuesday</option>
                        <option value="Wednesday" <?php if ((isset($_POST['start_day']) && $_POST['start_day'] == "Wednesday")) {
                                                    echo 'selected';
                                                  } else if ($_SESSION['start_day'] == "Wednesday") {
                                                    echo "selected";
                                                  } ?>>Wednesday</option>
                        <option value="Thursday" <?php if ((isset($_POST['start_day']) && $_POST['start_day'] == "Thursday")) {
                                                    echo 'selected';
                                                  } else if ($_SESSION['start_day'] == "Thursday") {
                                                    echo "selected";
                                                  } ?>>Thursday</option>
                        <option value="Friday" <?php if ((isset($_POST['start_day']) && $_POST['start_day'] == "Friday")) {
                                                  echo 'selected';
                                                } else if ($_SESSION['start_day'] == "Monday") {
                                                  echo "selected";
                                                } ?>>Friday</option>
                        <option value="Saturday" <?php if ((isset($_POST['start_day']) && $_POST['start_day'] == "Saturday")) {
                                                    echo 'selected';
                                                  } else if ($_SESSION['start_day'] == "Saturday") {
                                                    echo "selected";
                                                  } ?>>Saturday</option>
                      </select>
                      <p class="m-0 mx-3"> to </p>
                      <select id="end_day" class="form-select" name="end_day" required>
                        <option value="">Select Day </option>
                        <option value="Sunday" <?php if ((isset($_POST['end_day']) && $_POST['end_day'] == "Sunday")) {
                                                  echo 'selected';
                                                } else if ($_SESSION['end_day'] == "Sunday") {
                                                  echo "selected";
                                                } ?>>Sunday</option>
                        <option value="Monday" <?php if ((isset($_POST['end_day']) && $_POST['end_day'] == "Monday")) {
                                                  echo 'selected';
                                                } else if ($_SESSION['end_day'] == "Monday") {
                                                  echo "selected";
                                                } ?>>Monday</option>
                        <option value="Tuesday" <?php if ((isset($_POST['end_day']) && $_POST['end_day'] == "Tuesday")) {
                                                  echo 'selected';
                                                } else if ($_SESSION['end_day'] == "Tuesday") {
                                                  echo "selected";
                                                } ?>>Tuesday</option>
                        <option value="Wednesday" <?php if ((isset($_POST['end_day']) && $_POST['end_day'] == "Wednesday")) {
                                                    echo 'selected';
                                                  } else if ($_SESSION['end_day'] == "Wednesday") {
                                                    echo "selected";
                                                  } ?>>Wednesday</option>
                        <option value="Thursday" <?php if ((isset($_POST['end_day']) && $_POST['end_day'] == "Thursday")) {
                                                    echo 'selected';
                                                  } else if ($_SESSION['end_day'] == "Thursday") {
                                                    echo "selected";
                                                  } ?>>Thursday</option>
                        <option value="Friday" <?php if ((isset($_POST['end_day']) && $_POST['end_day'] == "Friday")) {
                                                  echo 'selected';
                                                } else if ($_SESSION['end_day'] == "Friday") {
                                                  echo "selected";
                                                } ?>>Friday</option>
                        <option value="Saturday" <?php if ((isset($_POST['end_day']) && $_POST['end_day'] == "Saturday")) {
                                                    echo 'selected';
                                                  } else if ($_SESSION['end_day'] == "Saturday") {
                                                    echo "selected";
                                                  } ?>>Saturday</option>
                      </select>
                    </div>
                    <?php
                    if ((isset($_POST['start_day']) && !validate_field($_POST['start_day'])) && (isset($_POST['end_day']) && !validate_field($_POST['end_day']))) {
                    ?>
                      <p class="text-dark m-0 ps-2">Working day is required.</p>
                    <?php
                    } else  if (isset($_POST['start_day']) && !validate_field($_POST['start_day'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">Start day is required.</p>
                    <?php
                    } else  if (isset($_POST['end_day']) && !validate_field($_POST['end_day'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">End day is required.</p>
                    <?php
                    }
                    ?>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-group mb-2">
                    <label for="work-hours">Working Hours</label>
                    <div class="d-flex align-items-center">
                      <input type="time" class="form-control" id="work-hours" name="start_wt" placeholder="" value="<?= (isset($_POST['start_wt'])) ? $_POST['start_wt'] : $_SESSION['start_wt'] ?>">
                      <p class="m-0 mx-3"> to </p>
                      <input type="time" class="form-control" id="work-hours" name="end_wt" placeholder="" value="<?= (isset($_POST['end_wt'])) ? $_POST['end_wt'] : $_SESSION['end_wt'] ?>">
                    </div>
                    <?php
                    if ((isset($_POST['start_wt']) && !validate_field($_POST['start_wt'])) && (isset($_POST['end_wt']) && !validate_field($_POST['end_wt']))) {
                    ?>
                      <p class="text-dark m-0 ps-2">Working time is required.</p>
                    <?php
                    } else  if (isset($_POST['start_wt']) && !validate_field($_POST['start_wt'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">Start working time is required.</p>
                    <?php
                    } else  if (isset($_POST['end_wt']) && !validate_field($_POST['end_wt'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">End working time is required.</p>
                      <?php
                    } else if (isset($_POST['start_wt']) && isset($_POST['end_wt'])) {
                      if (!validate_time($_POST['start_wt'], $_POST['end_wt'])) {
                      ?>
                        <p class="text-dark m-0 ps-2">Start time must be earlier than end time.</p>
                    <?php
                      }
                    }
                    ?>
                  </div>
                </div>
              </div>

              <!-- <div class="row">
                <div class="col-12 col-md-6 mb-3">
                  <div class="form-group mb-2">
                    <label for="appointment-type">Appointment Types</label>
                    <div class="d-flex">
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="in_person" checked>
                        <label class="form-check-label" for="in_person">
                          In-person
                        </label>
                      </div>
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="virtual_consultation">
                        <label class="form-check-label" for="virtual_consultation">
                          Virtual Consultation
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6 mb-3">
                  <div class="form-group mb-2">
                    <label for="appointment-limit">Appointment Limits</label>
                    <input type="number" class="form-control" id="appointment-limit" name="appointment-limit" min="1" max="30">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-group mb-2">
                    <label for="notification">Notification</label>
                    <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                      <input class="form-check-input" type="checkbox" value="" id="notification" checked>
                      <label class="form-check-label" for="notification">
                        Enable notifications
                      </label>
                    </div>
                  </div>
                </div>
              </div> -->

              <!-- Save Button -->
              <input type="submit" class="btn btn-primary text-light" name="save" value="Save Changes">
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
  <?php
  if (isset($_POST['save']) && $success == 'success') {
  ?>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Schedule date is successfully updated!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row d-flex">
              <div class="col-12 text-center">
                <a href="./settings_appointment" class="text-decoration-none text-dark">
                  <p class="m-0 text-primary fw-bold">Click to Continue.</p>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  }
  ?>

  <script src="../js/main.js"></script>
</body>