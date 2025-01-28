<?php
session_start();

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 3) {
  header('location: ../logout.php?from=3');
} else if (isset($_SESSION['user_role'])) {
  header('location: ./index.php');
}

require_once("../classes/account.class.php");
require_once("../classes/campus.class.php");
require_once("../tools/functions.php");

$account = new Account();

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
  $account->contact = htmlentities($_POST['contact']);
  $account->birthdate = htmlentities($_POST['birthdate']);
  if (isset($_POST['gender'])) {
    $account->gender = htmlentities($_POST['gender']);
  } else {
    $account->gender = '';
  }
  if (isset($_POST['campus_id'])) {
    $account->campus_id = htmlentities($_POST['campus_id']);
  } else {
    $account->campus_id = '';
  }
  $account->user_role = 3; // user_role (0 = admin, 1 = doc, 2 = mod, 3 = user)

  if (
    validate_field($account->email) &&
    validate_field($account->password) &&
    validate_field($account->firstname) &&
    validate_field($account->lastname) &&
    validate_field($account->gender) &&
    validate_field($account->campus_id) &&
    validate_field($account->contact) &&
    validate_password($account->password) &&
    validate_cpw($account->password, $_POST['confirm-password']) &&
    validate_email($account->email) == 'success' && !$account->is_email_exist() &&
    validate_wmsu_email($account->email) &&
    isset($_POST['terms'])
  ) {
    if ($account->add_user()) {
      $success = 'success';
    } else {
      echo 'An error occured while adding in the database.';
    }
  } else {
    $success = 'failed';
  }
} else if (isset($_POST['login'])) {
  $account = new Account();

  $account->email = htmlentities($_POST['login-email']);
  $account->password = htmlentities($_POST['login-password']);
  if ($account->sign_in_user()) {
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
    $_SESSION['birthdate'] = $account->birthdate;
    $_SESSION['campus_id'] = $account->campus_id;
    $_SESSION['contact'] = $account->contact;
    $_SESSION['patient_id'] = $account->patient_id;

    if ($_SESSION['user_role'] == 3) {
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

<head>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/paper.js/0.11.3/paper-full.min.js"></script>
  <link rel="stylesheet" href="../css/login.css">
</head>

<body>
  <div class="container" id="container">
    <div class="form-container sign-up-container">
      <form action="" method="post">
        <a href="../index.php" class="d-flex align-items-center text-dark text-decoration-none">
          <img src="../assets/images/logo.png" alt="Logo" height="35">
          <h1 class="fs-4 link-danger m-0 d-name">Doc<span class="fs-4 link-dark">Connect</span></h1>
        </a>
        <h3 class="text-start fw-bold w-100">Sign up</h3>
        <div class="row row-cols-3 w-100">
          <div class="form-input px-1">
            <input type="text" class="form-control" id="firstname" name="firstname" required placeholder="first name" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>">
            <?php
            if (isset($_POST['firstname']) && !validate_field($_POST['firstname'])) {
            ?>
              <p class="text-dark m-0 ps-2">First name is required.</p>
            <?php
            }
            ?>
          </div>
          <div class="form-input px-1">
            <input type="text" class="form-control" id="mname" name="middlename" placeholder="middle name">
          </div>
          <div class="form-input px-1">
            <input type="text" class="form-control" id="lname" name="lastname" placeholder="last name" required value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>">
            <?php
            if (isset($_POST['firstname']) && !validate_field($_POST['lastname'])) {
            ?>
              <p class="text-dark m-0 ps-2">Last name is required.</p>
            <?php
            }
            ?>
          </div>
        </div>

        <div class="form-input w-100 px-1">
          <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
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

        <div class="row row-cols-1 row-cols-md-2 w-100">
          <div class="form-input px-1">
            <input type="number" class="form-control" id="phoneNo" name="contact" placeholder="+63 9xx xxx xxxx" required value="<?= isset($_POST['contact']) ? $_POST['contact'] : '' ?>">
            <?php
            if (isset($_POST['contact']) && !validate_field($_POST['contact'])) {
            ?>
              <p class="text-dark m-0 ps-2">Contact is required.</p>
            <?php
            }
            ?>
          </div>
          <div class="form-input px-1">
            <select class="form-select my-2" aria-label="Campus" name="campus_id" required>
              <option selected>Campus</option>
              <?php
              $campus = new Campus();
              $campusArray = $campus->show_campus();
              foreach ($campusArray as $item) {
              ?>
                <option value="<?= $item['campus_id'] ?>" <?= (isset($_POST['campus_id']) && $_POST['campus_id'] == $item['campus_id']) ? 'selected' : '' ?>><?= $item['campus_name'] ?></option>
              <?php
              }
              ?>
            </select>
            <?php
            if (isset($_POST['campus_id']) && !validate_field($_POST['campus_id'])) {
            ?>
              <p class="text-dark m-0 ps-2">Campus is required.</p>
            <?php
            }
            ?>
          </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 w-100">
          <div class="form-input px-1">
            <select class="form-select my-2" aria-label="gender" name="gender" required>
              <option selected>Gender</option>
              <option value="Male" <?= (isset($_POST['gender']) && $_POST['gender'] == "Male") ? 'selected' : '' ?>>Male</option>
              <option value="Female" <?= (isset($_POST['gender']) && $_POST['gender'] == "Female") ? 'selected' : '' ?>>Female</option>
              <option value="Other" <?= (isset($_POST['gender']) && $_POST['gender'] == "Other") ? 'selected' : '' ?>>Other</option>
            </select>
            <?php
            if (isset($_POST['gender']) && !validate_field($_POST['gender'])) {
            ?>
              <p class="text-dark m-0 ps-2">Gender is required.</p>
            <?php
            }
            ?>
          </div>
          <div class="form-input px-1">
            <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="MM/DD/YYYY" required value="<?= isset($_POST['birthdate']) ? $_POST['birthdate'] : '' ?>">
            <?php
            if (isset($_POST['birthdate']) && !validate_field($_POST['birthdate'])) {
            ?>
              <p class="text-dark m-0 ps-2">Birth date is required.</p>
            <?php
            }
            ?>
          </div>
        </div>  

        <div class="row row-cols-2 w-100">
          <div class="form-input px-1">
            <input type="password" class="form-control" id="password-signup" name="password" placeholder="Password" required value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
            <?php
            if (isset($_POST['password']) && validate_password($_POST['password']) !== "success") {
            ?>
              <p class="text-dark m-0 ps-2"><?= validate_password($_POST['password']) ?></p>
            <?php
            }
            ?>
          </div>
          <div class="form-input px-1">
            <input type="password" class="form-control" id="confirmpassword-signup" name="confirm-password" placeholder="Confirm password" required value="<?= isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '' ?>">
            <?php
            if (isset($_POST['password']) && isset($_POST['confirm-password']) && !validate_cpw($_POST['password'], $_POST['confirm-password'])) {
            ?>
              <p class="text-dark m-0 ps-2">Password did not match.</p>
            <?php
            }
            ?>
          </div>
        </div>

        <div class="d-flex justify-content-between w-100">
          <div class="form-check p-0 mb-3 d-flex justify-content-center">
            <input class="form-check-input m-0 me-1" type="checkbox" value="yes" id="confirm-terms" name="terms" required <?= (isset($_POST['terms']) && $_POST['terms'] == 'Agreed') ? "checked" : "" ?>>
            <label class="form-check-label" for="confirm-terms">
              I agree to the <a href="#" class="link-danger">Terms of Service</a> and <a href="#" class="link-danger">Privacy Policy</a>
            </label>
          </div>
        </div>

        <input type="submit" class="btn btn-primary text-light w-100" name="signup" value="Sign Up">
        <p class="text-center">Already have an account? <span class="link-danger" id="signIn" style="cursor: pointer">Log In</span></p>
      </form>
    </div>

    <div class="form-container sign-in-container">
      <form action="" method="post">
        <a href="./index.php" class="d-flex align-items-center text-dark text-decoration-none">
          <img src="../assets/images/logo.png" alt="Logo" height="35">
          <h1 class="fs-4 link-danger m-0 d-name">Doc<span class="fs-4 link-dark">Connect</span></h1>
        </a>
        <h3 class="text-start fw-bold w-100">Sign in</h3>
        <div class="form-floating mb-3 w-100">
          <input type="email" class="form-control border-2" id="email-login" placeholder="Email" name="login-email" required value="<?= isset($_POST['login-email']) ? $_POST['login-email'] : '' ?>">
          <label for="email-login" class="mt-2">Email</label>
        </div>
        <div class="form-floating mb-3 w-100">
          <input type="password" class="form-control border-2" id="password-login" placeholder="Password" name="login-password" required value="<?= isset($_POST['login-password']) ? $_POST['login-password'] : '' ?>">
          <label for="password-login" class="mt-2">Password</label>
        </div>
        <div class="d-flex justify-content-end w-100">
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
        <input type="submit" class="btn btn-primary text-light w-100" name="login" value="Log in">
        <p class="text-center">New Here? <span class="link-danger" id="signUp" style="cursor: pointer">Sign Up!</span></p>
      </form>
    </div>

    <div class="overlay-container">
      <canvas id="canvas" class="canvas-back" style="z-index: 10;"></canvas>
      <div class="overlay" style="z-index: 2;">
        <div class="overlay-panel overlay-left">
          <h1>Welcome Back!</h1>
          <p>To keep connected with us please login with your personal info</p>
          <button class="d-none ghost" id="signIn-overlay">Sign In</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>Hello, Friend!</h1>
          <p>Enter your personal details and start journey with us</p>
          <button class="d-none ghost" id="signUp-overlay">Sign Up</button>
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
                <a href="./login" class="text-decoration-none text-dark">
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
  <script src="../js/login.js"></script>
  <script src="../js/main.js"></script>
</body>

</html>