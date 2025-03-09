<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ./index.php');
}

require_once("../classes/account.class.php");
require_once("../classes/campus.class.php");
require_once("../tools/functions.php");
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
}
?>

<html lang="en">
<?php
$title = 'Campuses | Add User';
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
        <form>
          <div class="border shadow p-3 mb-5 bg-body rounded">
            <h3 class="text-center">Add Patient</h3>

            <hr class="my-3 mx-4">

            <div class="row row-cols-1 row-cols-md-3">
              <div class="col form-group mb-2">
                <label for="fname">First Name</label>
                <input type="text" class="form-control" id="fname" name="firstname" placeholder="first name" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>">
                <?php
                if (isset($_POST['firstname']) && !validate_field($_POST['firstname'])) {
                ?>
                  <p class="text-dark m-0 ps-2">First name is required.</p>
                <?php
                }
                ?>
              </div>
              <div class="col form-group mb-2">
                <label for="mname">Middle Name</label>
                <input type="text" class="form-control" id="mname" placeholder="middle name">
              </div>
              <div class="col form-group mb-2">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lastname" placeholder="last name" value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>">
                <?php
                if (isset($_POST['lastname']) && !validate_field($_POST['lastname'])) {
                ?>
                  <p class="text-dark m-0 ps-2">Last name is required.</p>
                <?php
                }
                ?>
              </div>
            </div>

            <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
              <?php
              if (isset($_POST['email']) && !validate_field($_POST['email'])) {
              ?>
                <p class="text-dark m-0 ps-2">Email is required.</p>
              <?php
              }
              ?>
            </div>

            <div class="row row-cols-1 row-cols-md-2">
              <div class="col form-group mb-2">
                <label for="contact">Phone No.</label>
                <input type="text" class="form-control" id="contact" placeholder="+63 9xx xxx xxxx" value="<?= isset($_POST['contact']) ? $_POST['contact'] : '' ?>">
                <?php
                if (isset($_POST['contact']) && !validate_field($_POST['contact'])) {
                ?>
                  <p class="text-dark m-0 ps-2">Contact is required.</p>
                <?php
                }
                ?>
              </div>

              <div class="form-input px-1">
                <label for="campus">Campus</label>
                <select class="col form-select" aria-label="Campus" required name="campus">
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

            <div class="row row-cols-1 row-cols-md-2">
              <div class="form-group mb-2">
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
                  <p class="text-dark m-0 ps-2">Gender is required.</p>
                <?php
                }
                ?>
              </div>

              <div class="form-group mb-2">
                <label for="DoB">Date of Birth</label>
                <input type="date" class="form-control" id="DoB" name="birthdate" placeholder="MM/DD/YYYY" value="<?= isset($_POST['birthdate']) ? $_POST['birthdate'] : '' ?>">
                <?php
                if (isset($_POST['birthdate']) && !validate_field($_POST['birthdate'])) {
                ?>
                  <p class="text-dark m-0 ps-2">Birth date is required.</p>
                <?php
                }
                ?>
              </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2">
              <div class="form-input mb-2">
                <label for="password-signup">Password</label>
                <input type="password" class="form-control" id="password-signup" placeholder="Password" required value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
                <?php
                if (isset($_POST['password']) && validate_password($_POST['password']) !== "success") {
                ?>
                  <p class="text-dark m-0 ps-2"><?= validate_password($_POST['password']) ?></p>
                <?php
                }
                ?>
              </div>
              <div class="form-input mb-2">
                <label for="confirmpassword-signup">Confirm password</label>
                <input type="password" class="form-control" id="confirmpassword-signup" placeholder="Confirm password" required value="<?= isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '' ?>">
                <?php
                if (isset($_POST['password']) && isset($_POST['confirm-password']) && !validate_cpw($_POST['password'], $_POST['confirm-password'])) {
                ?>
                  <p class="text-dark m-0 ps-2">Password did not match.</p>
                <?php
                }
                ?>
              </div>
            </div>

            <!-- Save and Cancel Buttons -->
            <div class="d-flex justify-content-end mt-3">
              <a href="./usersAcc.php" class="btn btn-secondary me-2 link-light">Cancel</a>
              <button type="submit" class="btn btn-primary link-light" name="add">Save</button>
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
            <h5 class="modal-title" id="myModalLabel">Patient account is successfully created!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row d-flex">
              <div class="col-12 text-center">
                <a href="./moderatorsAcc" class="text-decoration-none text-dark">
                  <p class="m-0 text-primary fw-bold">Login to verify the patient account.</p>
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