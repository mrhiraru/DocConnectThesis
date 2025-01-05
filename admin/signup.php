<?php
session_start();

require_once '../tools/functions.php';
require_once '../classes/account.class.php';

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
  header('location: index.php');
} else if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2) {
  header('location: ./admin/index.php');
}

$account = new Account();

if (isset($_SESSION['user_role']) || $account->check_for_admin(0)) {
  header('location: ./login.php'); // change the location later
}

if (isset($_POST['signup'])) {

  $account->email = htmlentities($_POST['email']);
  $account->password = htmlentities($_POST['password']);
  $account->firstname = ucfirst(strtolower(htmlentities($_POST['firstname'])));
  if (isset($_POST['middlename'])) {
    $account->middlename = ucfirst(strtolower(htmlentities($_POST['middlename'])));
  } else {
    $account->middlename = '';
  }
  $account->lastname = ucfirst(strtolower(htmlentities($_POST['lastname'])));
  $account->user_role = 0; // user_role (0 = admin, 1 = doc, 2 = mod, 3 user)

  if (
    validate_field($account->email) &&
    validate_field($account->password) &&
    validate_field($account->firstname) &&
    validate_field($account->lastname) &&
    validate_password($account->password) &&
    validate_cpw($account->password, $_POST['confirm-password']) &&
    validate_email($account->email) == 'success' && !$account->is_email_exist() &&
    validate_wmsu_email($account->email)
  ) {
    if ($account->add_admin()) {
      $success = 'success';
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
$title = 'Admin | Signup';
include './includes/admin_head.php';
function getCurrentPage()
{
  return basename($_SERVER['PHP_SELF']);
}
?>

<body class="bg-danger d-flex align-items-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-6">
        <div class="card shadow-lg">
          <div class="card-header text-center">
            <h4>Admin Signup</h4>
          </div>

          <div class="card-body">
            <form action="" method="post">
              <div class="row">
                <div class="col-12 col-md-4 mb-3">
                  <label for="firstname" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="firstname" name="firstname" required placeholder="first name" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>">
                  <?php
                  if (isset($_POST['firstname']) && !validate_field($_POST['firstname'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">First name is required.</p>
                  <?php
                  }
                  ?>
                </div>
                <div class="col-12 col-md-4 mb-3">
                  <label for="middlename" class="form-label">Middle Name</label>
                  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="middle name" value="<?= isset($_POST['middlename']) ? $_POST['middlename'] : '' ?>">
                </div>
                <div class="col-12 col-md-4 mb-3">
                  <label for="lastname" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="lastname" name="lastname" required placeholder="last name" value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>">
                  <?php
                  if (isset($_POST['lastname']) && !validate_field($_POST['lastname'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">Last name is required.</p>
                  <?php
                  }
                  ?>
                </div>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                <?php
                $new_account = new Account();
                if (isset($_POST['email'])) {
                  $new_account->email = htmlentities($_POST['email']);
                } else {
                  $new_account->email = '';
                }
                if (isset($_POST['email']) && strcmp(validate_email($_POST['email']), 'success') != 0) {
                ?>
                  <p class="text-dark m-0 ps-2"><?= validate_email($_POST['email']) ?></p>
                <?php
                } else if (($new_account->is_email_exist() && $_POST['email']) && $success !== 'success') {
                ?>
                  <p class="text-dark m-0 ps-2">Email you've entered already exist.</p>
                <?php
                } else if (isset($_POST['email']) && !validate_wmsu_email($_POST['email'])) {
                ?>
                  <p class="text-dark m-0 ps-2">You must use wmsu email.</p>
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
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
if (isset($_POST['signup']) && $success == 'success') {
?>
  <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">Account is successfully created!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row d-flex">
            <div class="col-12 text-center">
              <a href="./login.php" class="text-decoration-none text-dark">
                <p class="m-0 text-primary fw-bold">Login to verify your account.</p>
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