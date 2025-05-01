<?php
session_start();

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 1) {
  header('location: ../logout.php?from=1');
} else if (isset($_SESSION['user_role'])) {
  header('location: ./index.php');
}

require_once '../tools/functions.php';
require_once '../classes/account.class.php';

if (!isset($_SESSION['user_role']) && isset($_COOKIE['remember_me'])) {
  $account = new Account();
  $account_id = $account->validateRememberMeToken($_COOKIE['remember_me']);
  if ($account_id) {
    $account->account_id = $account_id;
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
      $_SESSION['civil_status'] = $account->civil_status;
      $_SESSION['religion'] = $account->religion;
      $_SESSION['suffix'] = $account->suffix;
      $_SESSION['e_signature'] = $account->e_signature;

      if ($_SESSION['user_role'] == 1) {
        header('location: ./index.php');
        exit();
      }
    }
  } else {
    setcookie('remember_me', '', time() - 3600, "/");
  }
}

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
    $_SESSION['civil_status'] = $account->civil_status;
    $_SESSION['religion'] = $account->religion;
    $_SESSION['suffix'] = $account->suffix;
    
    $_SESSION['e_signature'] = $account->e_signature;

    if (isset($_POST['remember-me'])) {
      $token = $account->createRememberMeToken($account->account_id);
      if ($token) {
        setcookie('remember_me', $token, time() + (30 * 24 * 60 * 60), "/"); // 30 days
      }
    }

    if ($_SESSION['user_role'] == 1) {
      header('location: ./index.php');
    }
  } else {
    $error = 'Login failed: Invalid email or password.';
  }
}

if (isset($_COOKIE['remember_me'])) {
  $account = new Account();
  $account->deleteRememberMeToken($_COOKIE['remember_me']);
  setcookie('remember_me', '', time() - 3600, "/");
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Login';
include '../includes/head.php';
?>
<style>
  .bg-image {
    background: url('../assets/images/bg-4.jpg') no-repeat center center;
    background-size: cover;
    position: relative;
  }

  .bg-image::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 128, 0, 0.5);
    z-index: 0;
  }

  .container,
  .card {
    position: relative;
    z-index: 1;
  }
</style>

<body class="bg-image d-flex align-items-center min-vh-100">
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
              <div class="d-flex justify-content-between w-100 mt-3">
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember-me" <?= isset($_COOKIE['email']) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="remember-me">Remember Me</label>
                </div>
                <a href="#" class="text-end m-0 mb-3">Forgot your password?</a>
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