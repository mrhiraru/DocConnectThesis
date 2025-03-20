<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
  header('location: ../index.php');
  exit();
}

$birthdate = isset($_SESSION['birthdate']) ? date('Y-m-d', strtotime($_SESSION['birthdate'])) : "";

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$account_class = new Account();
$account_class->account_id = $_SESSION['account_id'];

if (isset($_POST['change_password'])) {
  $old_password = $_POST['old_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_new_password'];

  if ($account_class->change_password($old_password, $new_password, $confirm_password)) {
    $success = 'Password changed successfully.';
  } else {
    $success = 'Failed to change password. Please check your old password and ensure the new passwords match.';
  }
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