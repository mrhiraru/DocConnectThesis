<?php
session_start();

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 1) {
  header('location: ../logout.php?from=1');
} else if (isset($_SESSION['user_role'])) {
  header('location: ./index.php');
}

require_once '../tools/functions.php';
require_once '../classes/account.class.php';

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
  $account->birthdate = htmlentities($_POST['birthdate']);
  $account->gender = htmlentities($_POST['gender']);
  $account->contact = htmlentities($_POST['contact']);
  $account->lastname = ucfirst(strtolower(htmlentities($_POST['lastname'])));
  $account->user_role = 1; // user_role (0 = admin, 1 = doc, 2 = mod, 3 = user)

  if (
    validate_field($account->email) &&
    validate_field($account->password) &&
    validate_field($account->firstname) &&
    validate_field($account->lastname) &&
    validate_field($account->gender) &&
    validate_field($account->birthdate) &&
    validate_field($account->contact) &&
    validate_password($account->password) &&
    validate_cpw($account->password, $_POST['confirm-password']) &&
    validate_email($account->email) == 'success' && !$account->is_email_exist()
  ) {
    if ($account->add_doc()) {
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
$title = 'Signup';
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
    background-color: rgba(0, 128, 0, 0.4);
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
      <div class="col-12 col-md-8">
        <div class="card shadow-lg">
          <div class="card-header text-center">
            <h4>Doctors Signup</h4>
          </div>

          <div class="card-body">
            <form method="post" action="">
              <div class="row">
                <div class="col-12 col-md-4 mb-3">
                  <label for="fname">First Name</label>
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
                  <label for="mname">Middle Name</label>
                  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="middle name" value="<?= isset($_POST['middlename']) ? $_POST['middlename'] : '' ?>">

                </div>
                <div class="col-12 col-md-4 mb-3">
                  <label for="lname">Last Name</label>
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

              <div class="row">
                <div class="col-12 col-md-4 mb-3">
                  <label for="gender">Gender</label>
                  <select class="form-select" aria-label="gender" name="gender">
                    <option selected>Gender</option>
                    <option value="Male" <?= (isset($_POST['gender']) && $_POST['gender'] == "Male") ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= (isset($_POST['gender']) && $_POST['gender'] == "Female") ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= (isset($_POST['gender']) && $_POST['gender'] == "Other") ? 'selected' : '' ?>>Other</option>
                  </select>
                  <?php
                  if (isset($_POST['gender']) && !validate_field($_POST['gender'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">No gender selected.</p>
                  <?php
                  }
                  ?>
                </div>
                <div class="col-12 col-md-4 mb-3">
                  <label for="DoB">Date of Birth</label>
                  <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="MM/DD/YYYY" value="<?= isset($_POST['birthdate']) ? $_POST['birthdate'] : '' ?>">
                  <?php
                  if (isset($_POST['birthdate']) && !validate_field($_POST['birthdate'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">Birthdate is required.</p>
                  <?php
                  }
                  ?>
                </div>
                <div class="col-12 col-md-4 mb-3">
                  <label for="contact">Contact</label>
                  <input type="text" class="form-control" id="contact" name="contact" inputmode="numeric" title="Format: 09XX XXX XXXX" maxlength="13" pattern="09\d{2} \d{3} \d{4}" oninput="formatPhoneNumber(this)" required value="+63 <?= isset($_POST['contact']) ? $_POST['contact'] : '' ?>">
                  <?php
                  if (isset($_POST['contact']) && !validate_field($_POST['contact'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">Contact is required.</p>
                  <?php
                  }
                  ?>
                </div>
              </div>


              <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
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
                } else if (isset($_POST['email'])) {
                ?>
                  <p class="text-dark m-0 ps-2">You must use wmsu email.</p>
                <?php
                }
                ?>
              </div>
              <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
                <i class='bx bx-show text-dark position-absolute toggle-password' data-target="password" style="transform: translateY(25%);"></i>
                <?php
                if (isset($_POST['password']) && validate_password($_POST['password']) !== "success") {
                ?>
                  <p class="text-dark m-0 ps-2"><?= validate_password($_POST['password']) ?></p>
                <?php
                }
                ?>
              </div>
              <div class="mb-3 position-relative">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password" required placeholder="Confirm your password" value="<?= isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '' ?>">
                <i class='bx bx-show text-dark position-absolute toggle-password' data-target="confirm-password" style="transform: translateY(25%);"></i>
                <?php
                if (isset($_POST['password']) && isset($_POST['confirm-password']) && !validate_cpw($_POST['password'], $_POST['confirm-password'])) {
                ?>
                  <p class="text-dark m-0 ps-2">Password did not match.</p>
                <?php
                }
                ?>
              </div>
              <button type="submit" class="btn btn-primary text-light w-100" name="signup">Sign Up</button>
            </form>
          </div>

          <div class="card-footer text-center">
            <small class="text-muted">Already have an account? <a href="./login">Login here</a></small>
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
            <h5 class="modal-title" id="myModalLabel">Doctor account is successfully created!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row d-flex">
              <div class="col-12 text-center">
                <a href="./login.php" class="text-decoration-none text-dark">
                  <p class="m-0 text-primary fw-bold">Login to verify the doctor account.</p>
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
  <script src="../js/viewHideTogglePassword.js"></script>
  <script>
    function formatPhoneNumber(input) {
      let value = input.value.replace(/\D/g, ""); // Remove non-numeric characters
      if (value.startsWith("09")) {
        if (value.length > 4) {
          value = value.slice(0, 4) + " " + value.slice(4);
        }
        if (value.length > 8) {
          value = value.slice(0, 8) + " " + value.slice(8);
        }
      } else {
        value = "09"; // Force it to start with 09
      }
      input.value = value.slice(0, 13); // Limit to 11 characters
    }
  </script>
</body>

</html>