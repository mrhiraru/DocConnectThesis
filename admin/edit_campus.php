<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ./index.php');
}

require_once '../classes/campus.class.php';
require_once '../tools/functions.php';

$campus = new Campus();
$record = $campus->view_campus($_GET['campus_id']);

if (isset($_POST['save'])) {

  $campus->campus_id = $_GET['campus_id'];
  $campus->campus_name = htmlentities($_POST['campus_name']);
  $campus->campus_contact = htmlentities($_POST['campus_contact']);
  $campus->campus_address = htmlentities($_POST['campus_address']);
  $campus->campus_email = htmlentities($_POST['campus_email']);

  $uploaddir = '../assets/images/';
  $uploadname = $_FILES[htmlentities('campus_profile')]['name'];
  $uploadext = explode('.', $uploadname);
  $uploadnewext = strtolower(end($uploadext));
  $allowed = array('jpg', 'jpeg', 'png');

  if (in_array($uploadnewext, $allowed)) {

    $uploadenewname = uniqid('', true) . "." . $uploadnewext;
    $uploadfile = $uploaddir . $uploadenewname;

    if (move_uploaded_file($_FILES[htmlentities('campus_profile')]['tmp_name'], $uploadfile)) {
      $campus->campus_profile = $uploadenewname;
    } else {
      $campus->campus_profile = $record['campus_profile']; // Keep the existing image if upload fails
    }
  } else {
    $campus->campus_profile = $record['campus_profile']; // Keep the existing image if the file type is invalid
  }

  if (
    validate_field($campus->campus_name) &&
    validate_field($campus->campus_contact) &&
    validate_field($campus->campus_email) &&
    validate_field($campus->campus_address)
  ) {
    if ($campus->edit_campus()) {
      $success = 'success';
    } else {
      echo 'An error occurred while updating the campus.';
    }
  } else {
    $success = 'failed';
  }
}
?>

<html lang="en">
<?php
$title = 'Edit Campus | Campus A';
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

  <section id="edit_campus" class="page-container">
    <div class="row">

      <div class="col-2"></div>

      <div class="col-12 col-md-8">
        <form method="post" action="" enctype="multipart/form-data">
          <div class="border shadow p-3 mb-5 bg-body rounded">
            <h3 class="text-center">Edit Campus</h3>
            <div class="user">
              <div class="campus-pic">
                <label class="label brand-border-color d-flex flex-column" for="file" style="border-width: 4px !important;">
                  <i class="bx bxs-camera-plus"></i>
                  <span>Change Image</span>
                </label>
                <img src="<?= "../assets/images/" . $record['campus_profile'] ?>" id="output" class="img-fluid rounded-3 w-75">
                <input id="file" type="file" name="campus_profile" accept=".jpg, .jpeg, .png" onchange="validateFile(event)">
              </div>
            </div>

            <div class="form-group mb-2">
              <label for="name">Campus Name</label>
              <input type="text" class="form-control" id="name" name="campus_name" placeholder="Campus Name" value="<?= $record['campus_name'] ?>">
              <?php
              if (isset($_POST['campus_name']) && !validate_field($_POST['campus_name'])) {
              ?>
                <p class="text-dark m-0 ps-2">Campus name is required.</p>
              <?php
              }
              ?>
            </div>
            <div class="form-group mb-2">
              <label for="address">Address</label>
              <input type="text" class="form-control" id="address" name="campus_address" placeholder="Address" value="<?= $record['campus_address'] ?>">
              <?php
              if (isset($_POST['campus_address']) && !validate_field($_POST['campus_address'])) {
              ?>
                <p class="text-dark m-0 ps-2">Campus address is required.</p>
              <?php
              }
              ?>
            </div>
            <div class="form-group mb-2">
              <label for="contact">Contact</label>
              <input type="text" class="form-control" id="contact" name="campus_contact" placeholder="+63 9xx xxx xxxx" value="<?= $record['campus_contact'] ?>">
              <?php
              if (isset($_POST['campus_contact']) && !validate_field($_POST['campus_contact'])) {
              ?>
                <p class="text-dark m-0 ps-2">Campus contact is required.</p>
              <?php
              }
              ?>
            </div>
            <div class="form-group mb-2">
              <label for="email">Email Address</label>
              <input type="email" class="form-control" id="email" name="campus_email" placeholder="name@example.com" value="<?= $record['campus_email'] ?>">
              <?php
              if (isset($_POST['campus_email']) && !validate_field($_POST['campus_email'])) {
              ?>
                <p class="text-dark m-0 ps-2">Campus email is required.</p>
              <?php
              }
              ?>
            </div>

            <!-- Save and Cancel Buttons -->
            <div class="d-flex justify-content-end mt-3">
              <a href="./campus" class="btn btn-secondary me-2 link-light">Cancel</a>
              <input type="submit" class="btn btn-primary text-light w-100" name="save" value="Save">
            </div>
          </div>
        </form>

      </div>

      <div class="col-2"></div>

    </div>
  </section>

  <?php
  if (isset($_POST['save']) && $success == 'success') {
  ?>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Campus updated successfully!</h5>
          </div>
          <div class="modal-body">
            <div class="row d-flex">
              <div class="col-12 text-center">
                <a href="./campus.php" class="text-decoration-none text-dark">
                  <p class="m-0 text-primary fw-bold">Click to Continue</p>
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
  <script src="./js/imageChange.js"></script>
</body>

</html>