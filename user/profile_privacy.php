<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
  exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
  header('location: ../index.php');
  exit();
}

$birthdate = isset($_SESSION['birthdate']) ? date('Y-m-d', strtotime($_SESSION['birthdate'])) : "";

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$account_class = new Account();
$account_class->account_id = $_SESSION['account_id'];

// Handle password change
if (isset($_POST['change_password'])) {
  $old_password = $_POST['old_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_new_password'];

  // Call the change_password method
  $result = $account_class->change_password($old_password, $new_password, $confirm_password);

  // Set session messages based on the result
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

  // Redirect to the same page to display the message
  header('location: profile_privacy.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | Profile';
include '../includes/head.php';
?>

<body>
  <?php
  require_once('../includes/header.php');
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
  <section id="profile" class="page-container">
    <div class="container py-5">

      <div class="row">
        <?php include 'profile_left.php'; ?>

        <div class="col-lg-9">
          <?php
          $privacy = 'active';
          $aPrivacy = 'page';
          $cPrivacy = 'text-dark';

          include 'profile_nav.php';

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

          <div class="card bg-body-tertiary mb-4">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <i class='bx bxs-key text-primary display-6 me-2'></i>
                <h4 class="mb-0">Password</h4>
              </div>
              <hr class="my-2" style="height: 2.5px;">
              <form action="profile_privacy.php" method="post">
                <div class="row mb-3">
                  <div class="col-md-8 mb-3 mb-md-0">
                    <label for="oldPassword" class="form-label text-black-50">Old Password</label>
                    <input type="password" class="form-control bg-light border border-dark" id="oldPassword" name="old_password" required>
                  </div>
                  <div class="col-md-8 mb-3 mb-md-0">
                    <label for="newPassword" class="form-label text-black-50">New Password</label>
                    <input type="password" class="form-control bg-light border border-dark" id="newPassword" name="new_password" required>
                  </div>
                  <div class="col-md-8">
                    <label for="confirmNewPassword" class="form-label text-black-50">Confirm New Password</label>
                    <input type="password" class="form-control bg-light border border-dark" id="confirmNewPassword" name="confirm_new_password" required>
                  </div>
                </div>

                <div class="form-check mb-3">
                  <input type="checkbox" class="form-check-input" id="togglePassword">
                  <label for="togglePassword" class="form-check-label" id="togglePasswordLabel">Show Password</label>
                </div>

                <div class="text-end">
                  <input type="submit" class="btn btn-primary text-light" name="change_password" value="Change Password">
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="../js/user/profile_privacy.js"></script>

  <?php
  require_once('../includes/footer.php');
  ?>

</body>

</html>