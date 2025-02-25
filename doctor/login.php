<?php
session_start();

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 1) {
  header('location: ../logout.php?from=1');
} else if (isset($_SESSION['user_role'])) {
  header('location: ./index.php');
}

require_once '../tools/functions.php';
require_once '../classes/account.class.php';

if (isset($_POST['login'])) {
  $account = new Account();

  $account->email = htmlentities($_POST['login-email']);
  $account->password = htmlentities($_POST['login-password']);
  if ($account->sign_in_doctor()) {
    $_SESSION['user_role'] = $account->user_role;
    $_SESSION['account_id'] = $account->account_id;
    $_SESSION['verification_status'] = $account->verification_status;
    $_SESSION['email'] = $account->email;
    if (isset($account->middlename)) {
      $_SESSION['fullname'] = ucwords(strtolower($account->firstname . ' ' . $account->middlename . ' ' . $account->lastname));
    } else {
      $_SESSION['fullname'] = ucwords(strtolower($account->firstname . ' ' . $account->lastname));
    }
    $_SESSION['firstname'] = $account->firstname;
    $_SESSION['middlename'] = $account->middlename;
    $_SESSION['lastname'] = $account->lastname;
    $_SESSION['gender'] = $account->gender;
    $_SESSION['address'] = $account->address;
    $_SESSION['birthdate'] = $account->birthdate;
    $_SESSION['campus_id'] = $account->campus_id;
    $_SESSION['contact'] = $account->contact;
    $_SESSION['specialty'] = $account->specialty;
    $_SESSION['start_wt'] = $account->start_wt;
    $_SESSION['end_wt'] = $account->end_wt;
    $_SESSION['start_day'] = $account->start_day;
    $_SESSION['end_day'] = $account->end_day;
    $_SESSION['bio'] = $account->bio;
    $_SESSION['account_image'] = $account->account_image;
    $_SESSION['doctor_id'] = $account->doctor_id;

    if ($_SESSION['user_role'] == 2) {
      header('location: ./index.php');
    }
  } else {
    $error = 'Login failed: Invalid email or password.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Login';
include '../includes/head.php';
?>

<body class="bg-green d-flex align-items-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-6">
        <div class="card shadow-lg">
          <div class="card-header text-center">
            <h4>Doctors Login</h4>
          </div>

          <div class="card-body">
            <form method="post" action="">
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" name="login-email" required value="<?= isset($_POST['login-email']) ? $_POST['login-email'] : '' ?>">
              </div>
              <div class="mb-1">
                <label for="password" class="form-label">Password</label>
                <div class="d-flex align-items-center border border-1 rounded-1 position-relative">
                  <input type="password" class="form-control border-0 " id="password" name="login-password" placeholder="Enter your password" required value="<?= isset($_POST['login-password']) ? $_POST['login-password'] : '' ?>">
                  <i class='bx bx-show text-dark position-absolute toggle-password' data-target="password"></i>
                </div>
              </div>
              <div class="mb-3 text-end">
                <a href="#" class="text-primary">Forgot Password?</a>
              </div>
              <?php
              if (isset($_POST['login']) && isset($error)) {
              ?>
                <div class="mb-2 col-12">
                  <p class="text-dark m-0 ps-2 text-start">
                    <?= $error ?>
                  </p>
                </div>
              <?php
              }
              ?>
              <button type="submit" class="btn btn-primary text-light w-100" name="login">Login</button>
            </form>
          </div>

          <div class="card-footer text-center">
            <small class="text-muted">Don't have an account? <a href="./signup">Signup here</a></small>
          </div>
        </div>
      </div>
    </div>
</body>

<script src="../js/viewHideTogglePassword.js"></script>

</html>