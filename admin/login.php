<?php
session_start();

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 0) {
  header('location: ../logout.php?from=0');
} else if (isset($_SESSION['user_role'])) {
  header('location: ./index.php');
}

require_once("../classes/account.class.php");
require_once("../tools/functions.php");

if (isset($_POST['login'])) {
  $account = new Account();

  $account->email = htmlentities($_POST['email']);
  $account->password = htmlentities($_POST['password']);
  if ($account->sign_in_account()) {
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

    if ($_SESSION['user_role'] == 0) {
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
$title = 'Admin | Login';
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
            <h4>Admin Login</h4>
          </div>

          <div class="card-body">
            <form action="" method="post">
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
              </div>
              <div class="mb-1">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
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
              <input type="submit" class="btn btn-primary text-light w-100" name="login" value="Login">
            </form>
          </div>

          <!-- <div class="card-footer text-center">
            <small class="text-muted">Don't have an account? <a href="./signup">Signup here</a></small>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</body>

</html>