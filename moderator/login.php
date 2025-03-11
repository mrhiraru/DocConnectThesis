<?php
session_start();

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 2) {
  header('location: ../logout.php?from=2');
} else if (isset($_SESSION['user_role'])) {
  header('location: ./index.php');
}

require_once("../classes/account.class.php");
require_once("../tools/functions.php");

if (isset($_POST['login'])) {
  $account = new Account();

  $account->email = htmlentities($_POST['username']); // Change from email to username
  $account->password = htmlentities($_POST['password']);
  if ($account->sign_in_mod()) {
    $_SESSION['user_role'] = $account->user_role;
    $_SESSION['account_id'] = $account->account_id;
    $_SESSION['verification_status'] = $account->verification_status;
    $_SESSION['email'] = $account->email;
    $_SESSION['campus_id'] = $account->campus_id;
    $_SESSION['campus_name'] = $account->campus_name;

    if ($_SESSION['user_role'] == 2) {
      header('location: ./index.php');
    }
  } else {
    $error = 'Login failed: Invalid username or password.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Moderator | Login';
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
            <h4>Moderator Login</h4>
          </div>

          <div class="card-body">
            <form action="" method="post">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label> <!-- Changed from email to username -->
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>"> <!-- Changed from email to username -->
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
        </div>
      </div>
    </div>
  </div>
</body>

</html>