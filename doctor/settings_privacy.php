<?php
session_start();

require_once('../classes/account.class.php');

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
  exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
  header('location: ./index.php');
  exit();
}

$account_class = new Account();
$account_class->account_id = $_SESSION['account_id'];

if (isset($_POST['change_password'])) {
  $old_password = $_POST['old_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_new_password'];

  $result = $account_class->change_password($old_password, $new_password, $confirm_password);

  if ($result === "success") {
    $_SESSION['message'] = "Password changed successfully.";
    $_SESSION['message_type'] = "success";
  } elseif ($result === "error_old_password") {
    $_SESSION['message'] = "Old password is incorrect.";
    $_SESSION['message_type'] = "error";
  } elseif ($result === "error_mismatch") {
    $_SESSION['message'] = "New password and confirm password do not match.";
    $_SESSION['message_type'] = "error";
  } else {
    $_SESSION['message'] = "Failed to change password. Please try again.";
    $_SESSION['message_type'] = "error";
  }

  header('location: settings_privacy.php');
  exit();
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
      <style>
        .alert-custom {
          border-radius: 8px;
          padding: 15px;
          margin-bottom: 20px;
          font-size: 14px;
        }

        .alert-success {
          background-color: #d4edda;
          border-color: #c3e6cb;
          color: #155724;
        }

        .alert-error {
          background-color: #f8d7da;
          border-color: #f5c6cb;
          color: #721c24;
        }
      </style>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Account Settings</h1>
        </div>

        <?php
        require_once('../includes/doctorSetting_Nav.php')
        ?>

        <div class="card bg-body-tertiary mb-4">
          <div class="card-body">
            <?php
            if (isset($_SESSION['message'])) {
              $message_type = $_SESSION['message_type'];
              $message = $_SESSION['message'];
              unset($_SESSION['message']);
              unset($_SESSION['message_type']);

              $alert_class = ($message_type === 'success') ? 'alert-success' : 'alert-error';
              echo "<div class='alert $alert_class alert-custom alert-dismissible fade show' role='alert'>
                              $message
                              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>
                            ";
            }
            ?>
            <form method="POST" action="">
              <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-group mb-2">
                    <label for="oldPass">Old Password</label>
                    <input type="password" class="form-control" id="oldPass" name="old_password" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12 col-md-6 mb-3">
                  <div class="form-group mb-2">
                    <label for="newPass">New Password</label>
                    <input type="password" class="form-control" id="newPass" name="new_password" required>
                  </div>
                </div>
                <div class="col-12 col-md-6 mb-3">
                  <div class="form-group mb-2">
                    <label for="confirm_newPass">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_newPass" name="confirm_new_password" required>
                  </div>
                </div>
              </div>

              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="togglePassword">
                <label for="togglePassword" class="form-check-label" id="togglePasswordLabel">Show Password</label>
              </div>

              <button type="submit" name="change_password" class="btn btn-primary text-light">Update</button>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="../js/doctor/settings_privacy.js"></script>
</body>

</html>