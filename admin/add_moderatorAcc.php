<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ./index.php');
}

require_once '../tools/functions.php';
require_once '../classes/account.class.php';
require_once '../classes/campus.class.php';

$account = new Account();

if (isset($_POST['add'])) {

  $account->email = htmlentities($_POST['email']);
  $account->password = htmlentities($_POST['password']);
  $account->firstname = ucfirst(strtolower(htmlentities($_POST['firstname'])));
  if (isset($_POST['middlename'])) {
    $account->middlename = ucfirst(strtolower(htmlentities($_POST['middlename'])));
  } else {
    $account->middlename = '';
  }
  $account->campus_id = htmlentities($_POST['campus']);
  $account->gender = htmlentities($_POST['gender']);
  $account->contact = htmlentities($_POST['contact']);
  $account->lastname = ucfirst(strtolower(htmlentities($_POST['lastname'])));
  $account->user_role = 2; // user_role (0 = admin, 1 = mod, 2 = user)

  if (
    validate_field($account->email) &&
    validate_field($account->password) &&
    validate_field($account->firstname) &&
    validate_field($account->lastname) &&
    validate_password($account->gender) &&
    validate_field($account->contact) &&
    validate_password($account->campus_id) &&
    validate_password($account->password) &&
    validate_cpw($account->password, $_POST['confirm-password']) &&
    validate_email($account->email) == 'success' && !$account->is_email_exist() &&
    validate_wmsu_email($account->email)
  ) {
    if ($account->add_mod()) {
      $success = 'success';
    } else {
      echo 'An error occured while adding in the database.';
    }
  } else {
    $success = 'failed';
  }
}

?>

<html lang="en">
<?php
$title = 'Campuses | Add Staff';
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

      <div class="col-12 col-md-8">
        <form method="post" action="">
          <div class="border shadow p-3 mb-5 bg-body rounded">
            <h3 class="text-center">Add Moderator</h3>

            <hr class="my-3 mx-4">

            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" required placeholder="Enter your username" value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>">
              <?php
              $new_account = new Account();
              if (isset($_POST['username'])) {
                $new_account->email = htmlentities($_POST['username']);
              } else {
                $new_account->email = '';
              }
              if ($new_account->is_email_exist() && $_POST['username'] && $success !== 'success') {
              ?>
                <p class="text-dark m-0 ps-2">Username already exists.</p>
              <?php
              }
              ?>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
              <?php
              if (isset($_POST['password']) && validate_password($_POST['password']) !== "success") {
              ?>
                <p class="text-dark m-0 ps-2"><?= validate_password($_POST['password']) ?></p>
              <?php
              }
              ?>
            </div>
            <div class="mb-3">
              <label for="confirm-password" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirm-password" name="confirm-password" required placeholder="Confirm your password" value="<?= isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '' ?>">
              <?php
              if (isset($_POST['password']) && isset($_POST['confirm-password']) && !validate_cpw($_POST['password'], $_POST['confirm-password'])) {
              ?>
                <p class="text-dark m-0 ps-2">Password did not match.</p>
              <?php
              }
              ?>
            </div>
            <input type="submit" class="btn btn-primary text-light w-100" name="signup" value="Sign Up">
            <div class="d-flex justify-content-end mt-3">
              <a href="./moderatorsAcc" class="btn btn-secondary me-2 link-light">Cancel</a>
              <button type="submit" class="btn btn-primary link-light" name="add">Add</button>
            </div>
          </div>
        </form>

      </div>

      <div class="col-2"></div>

    </div>


  </section>
  <?php
  if (isset($_POST['add']) && $success == 'success') {
  ?>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Moderator account is successfully created!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row d-flex">
              <div class="col-12 text-center">
                <a href="./moderatorsAcc" class="text-decoration-none text-dark">
                  <p class="m-0 text-primary fw-bold">Login to verify the moderator account.</p>
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

</html>