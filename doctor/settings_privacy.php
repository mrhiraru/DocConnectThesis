<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
  header('location: ./index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Settings | Privacy And Security';
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
            <form>
              <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-group mb-2">
                    <label for="oldPass">Old Password</label>
                    <input type="password" class="form-control" id="oldPass" name="oldPass">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12 col-md-6 mb-3">
                  <div class="form-group mb-2">
                    <label for="newPass">New Password</label>
                    <input type="password" class="form-control" id="newPass" name="newPass">
                  </div>
                </div>
                <div class="col-12 col-md-6 mb-3">
                  <div class="form-group mb-2">
                    <label for="confirm_newPass">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_newPass" name="confirm_newPass">
                  </div>
                </div>
              </div>

              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="togglePassword">
                <label for="togglePassword" class="form-check-label" id="togglePasswordLabel">Show Password</label>
              </div>

              <!-- Controls for data sharing and patient data visibility -->
              <!-- <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-group mb-2">
                    <label for="dataPrivacy">Data Privacy</label>
                    <input type="text" class="form-control" id="dataPrivacy" name="dataPrivacy">
                  </div>
                </div>
              </div> -->

              <!-- Save Button -->
              <button type="submit" class="btn btn-primary text-light">Update</button>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="../js/doctor/settings_privacy.js"></script>
</body>