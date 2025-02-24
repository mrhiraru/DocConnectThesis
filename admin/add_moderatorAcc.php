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

            <div class="row row-cols-1 row-cols-md-3">
              <div class="col form-group mb-2">
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
              <div class="col form-group mb-2">
                <label for="mname">Middle Name</label>
                <input type="text" class="form-control" id="middlename" name="middlename" placeholder="middle name" value="<?= isset($_POST['middlename']) ? $_POST['middlename'] : '' ?>">
              </div>
              <div class="col form-group mb-2">
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

            <div class="row row-cols-1 row-cols-md-2">
              <div class="form-group mb-2">
                <label for="gender">Gender</label>
                <select class="form-select" aria-label="gender" name="gender">
                  <option selected>Gender</option>
                  <option value="male" <?= (isset($_POST['gender']) && $_POST['gender'] == "male") ? 'selected' : '' ?>>Male</option>
                  <option value="female" <?= (isset($_POST['gender']) && $_POST['gender'] == "female") ? 'selected' : '' ?>>Female</option>
                  <option value="other" <?= (isset($_POST['gender']) && $_POST['gender'] == "other") ? 'selected' : '' ?>>Other</option>
                </select>
                <?php
                if (isset($_POST['gender']) && !validate_field($_POST['gender'])) {
                ?>
                  <p class="text-dark m-0 ps-2">No gender selected.</p>
                <?php
                }
                ?>
              </div>

              <div class="form-group mb-2">
                <label for="position">Campus</label>
                <select class="form-select" aria-label="Default select example" name="campus">
                  <option selected>Select campus</option>
                  <?php
                  $campus = new Campus();
                  $campusArray = $campus->show_campus();
                  foreach ($campusArray as $item) {
                  ?>
                    <option value="<?= $item['campus_id'] ?>" <?= (isset($_POST['campus']) && $_POST['campus'] == $item['campus_id']) ? 'selected' : '' ?>><?= $item['campus_name'] ?></option>
                  <?php
                  }
                  ?>
                </select>
                <?php
                if (isset($_POST['campus']) && !validate_field($_POST['campus'])) {
                ?>
                  <p class="text-dark m-0 ps-2">No campus selected.</p>
                <?php
                }
                ?>
              </div>

            </div>
            <div class="form-group mb-2">
              <label for="contact">Phone No.</label>
              <input type="number" class="form-control" name="contact" id="contact" placeholder="+63 9xx xxx xxxx" value="<?= isset($_POST['contact']) ? $_POST['contact'] : '' ?>">
              <?php
              if (isset($_POST['contact']) && !validate_field($_POST['contact'])) {
              ?>
                <p class="text-dark m-0 ps-2">Contact is required.</p>
              <?php
              }
              ?>
            </div>

            <div class="form-group mb-2">
              <label for="email">Email address</label>
              <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
              <?php
              if (isset($_POST['email']) && !validate_field($_POST['email'])) {
              ?>
                <p class="text-dark m-0 ps-2">Email is required.</p>
              <?php
              }
              ?>
            </div>
            
            <div class="row row-cols-1 row-cols-md-2">
              <div class="form-group mb-2">
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
              <div class="form-group mb-2">
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
            </div>

            <!-- <div class="form-group mb-2">
              <label for="status">Status</label>
              <div class="d-flex flex-row justify-content-start">
                <div class="form-check mx-2">
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                  <label class="form-check-label" for="flexRadioDefault1">
                    Active
                  </label>
                </div>
                <div class="form-check mx-2">
                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                  <label class="form-check-label" for="flexRadioDefault2">
                    Inactive
                  </label>
                </div>
              </div>
            </div> -->

            <!-- Save and Cancel Buttons -->
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