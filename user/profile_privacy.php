<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
}

$birthdate = isset($_SESSION['birthdate']) ? date('Y-m-d', strtotime($_SESSION['birthdate'])) : "";

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$account_class = new Account();
if (isset($_POST['saveAccount'])) {
  $account_class->account_id = $_SESSION['account_id'];

  $account_class->firstname = $_POST['first_name'] ?? '';
  $account_class->middlename = $_POST['middle_name'] ?? '';
  $account_class->lastname = $_POST['last_name'] ?? '';
  $account_class->gender = $_POST['gender'] ?? '';
  $account_class->email = $_POST['email'] ?? '';
  $account_class->contact = $_POST['Phone_No'] ?? '';
  $account_class->birthdate = $_POST['birthdate'] ?? '';
  $account_class->address = $_POST['address'] ?? '';

  if (
    validate_field($account_class->firstname) &&
    validate_field($account_class->middlename) &&
    validate_field($account_class->lastname) &&
    validate_field($account_class->gender) &&
    validate_field($account_class->email) &&
    validate_field($account_class->contact) &&
    validate_field($account_class->birthdate) &&
    validate_field($account_class->address)
  ) {
    if ($account_class->update_user_info()) {
      $success = 'success';

      $_SESSION['firstname'] = $account_class->firstname;
      $_SESSION['middlename'] = $account_class->middlename;
      $_SESSION['lastname'] = $account_class->lastname;
      $_SESSION['gender'] = $account_class->gender;
      $_SESSION['email'] = $account_class->email;
      $_SESSION['address'] = $account_class->address;
      $_SESSION['birthdate'] = $account_class->birthdate;
      $_SESSION['contact'] = $account_class->contact;
    } else {
      echo 'An error occured while adding in the database.';
    }
  } else {
    $success = 'failed';
  }
}

if (isset($_POST['save_image'])) {

  $account_class->account_id = $_SESSION['account_id'];

  $uploaddir = '../assets/images/';
  $uploadname = $_FILES[htmlentities('account_image')]['name'];
  $uploadext = explode('.', $uploadname);
  $uploadnewext = strtolower(end($uploadext));
  $allowed = array('jpg', 'jpeg', 'png');

  if (in_array($uploadnewext, $allowed)) {

    $uploadenewname = uniqid('', true) . "." . $uploadnewext;
    $uploadfile = $uploaddir . $uploadenewname;

    if (move_uploaded_file($_FILES[htmlentities('account_image')]['tmp_name'], $uploadfile)) {
      $account_class->account_image = $uploadenewname;

      if ($account_class->save_image()) {
        $_SESSION['account_image'] = $account_class->account_image;
        $success = 'success';
      } else {
        echo 'An error occured while adding in the database.';
      }
    } else {
      $success = 'failed';
    }
  } else {
    $success = 'failed';
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | Profile';
include '../includes/head.php';
?>

<body>
  <?php
  require_once('../includes/header.php');
  ?>

  <section id="profile" class="page-container">
    <div class="container py-5">

      <div class="row">
        <?php include 'profile_left.php'; ?>

        <div class="col-lg-9">
          <?php
          $privacy = 'active';
          $aPrivacy = 'page';
          $cPrivacy = 'text-dark';

          include 'profile_nav.php';
          ?>

          <div class="card bg-body-tertiary mb-4">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <i class='bx bxs-key text-primary display-6 me-2'></i>
                <h4 class="mb-0">Password</h4>
              </div>
              <hr class="my-2" style="height: 2.5px;">
              <form action="#.php" method="post">
                <div class="row mb-3">
                  <div class="col-md-8 mb-3 mb-md-0">
                    <label for="oldPassword" class="form-label text-black-50">Old Password</label>
                    <input type="password" class="form-control bg-light border border-dark" id="oldPassword" name="old_password" required>
                  </div>
                  <div class="col-md-8 mb-3 mb-md-0">
                    <label for="newPassword" class="form-label text-black-50">New Password</label>
                    <input type="password" class="form-control bg-light border border-dark" id="newPassword" name="new_password">
                  </div>
                  <div class="col-md-8">
                    <label for="confirmNewPassword" class="form-label text-black-50">Confirm New Password</label>
                    <input type="password" class="form-control bg-light border border-dark" id="confirmNewPassword" name="confirm_new_password" required>
                  </div>
                </div>

                <div class="form-check mb-3">
                  <input type="checkbox" class="form-check-input" id="togglePassword">
                  <label for="togglePassword" class="form-check-label" id="togglePasswordLabel">Show Password</label>
                </div>

                <div class="text-end">
                  <input type="submit" id="saveAccount" class="btn btn-primary text-light" name="saveAccount" value="Save Changes">
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="../js/user/profile_privacy.js"></script>

  <?php
  require_once('../includes/footer.php');
  ?>

</body>

</html>