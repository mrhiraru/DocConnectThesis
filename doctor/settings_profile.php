<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
  header('location: ./index.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$account = new Account();
if (isset($_POST['save'])) {
  $account->firstname = htmlentities($_POST['firstname']);
  $account->middlename = htmlentities($_POST['middlename']);
  $account->lastname = htmlentities($_POST['lastname']);
  $account->gender = htmlentities($_POST['gender']);
  $account->birthdate = htmlentities($_POST['birthdate']);
  $account->specialty = htmlentities($_POST['specialty']);
  $account->contact = htmlentities($_POST['contact']);
  $account->email = htmlentities($_POST['email']);
  $account->address = htmlentities($_POST['address']);
  $account->bio = htmlentities($_POST['bio']);
  $account->account_id = $_SESSION['account_id'];

  if (validate_field($account->firstname &&
    $account->lastname &&
    $account->gender  &&
    $account->birthdate  &&
    $account->specialty  &&
    $account->contact  &&
    $account->address  &&
    $account->email  &&
    $account->bio)) {
    if ($account->update_doctor_info()) {
      $success = 'success';

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
      $_SESSION['contact'] = $account->contact;
      $_SESSION['specialty'] = $account->specialty;
      $_SESSION['bio'] = $account->bio;
    } else {
      echo 'An error occured while adding in the database.';
    }
  } else {
    $success = 'failed';
  }
}
if (isset($_POST['save_image'])) {

  $account->account_id = $_SESSION['account_id'];

  $uploaddir = '../assets/images/';
  $uploadname = $_FILES[htmlentities('account_image')]['name'];
  $uploadext = explode('.', $uploadname);
  $uploadnewext = strtolower(end($uploadext));
  $allowed = array('jpg', 'jpeg', 'png');

  if (in_array($uploadnewext, $allowed)) {

    $uploadenewname = uniqid('', true) . "." . $uploadnewext;
    $uploadfile = $uploaddir . $uploadenewname;

    if (move_uploaded_file($_FILES[htmlentities('account_image')]['tmp_name'], $uploadfile)) {
      $account->account_image = $uploadenewname;

      if ($account->save_image()) {
        $_SESSION['account_image'] = $account->account_image;
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
$title = 'Settings | Profile';
$setting = 'active';
include '../includes/head.php';
?>

<body>
  <?php
  require_once('../includes/header-doctor.php');
  ?>

  <div class="container-fluid">
    <div class="row">
      <?php
      require_once('../includes/sidepanel-doctor.php');
      ?>


      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Account Settings</h1>
        </div>

        <?php
        require_once('../includes/doctorSetting_Nav.php')
        ?>

        <div class="card bg-body-tertiary mb-4">
          <div class="card-body">

            <form method="post" action="" enctype="multipart/form-data">
              <div class="d-flex flex-column flex-lg-row align-items-center mx-4 mb-4">
                <!-- Profile Picture -->
                <div class="campus-pic align-items-end">
                  <label class="label brand-border-color d-flex flex-column" for="file" style="border-width: 4px !important;">
                    <i class="bx bxs-camera-plus text-light p-2 bg-primary"></i>
                    <span>Change Image</span>
                  </label>

                  <img src="<?php if (isset($_SESSION['account_image'])) {
                              echo "../assets/images/" . $_SESSION['account_image'];
                            } else {
                              echo "../assets/images/defualt_profile.png";
                            } ?>" id="output" class="rounded-circle" alt="User Avatar">

                  <input id="file" type="file" name="account_image" accept=".jpg, .jpeg, .png" required onchange="previewImage(event)">
                </div>
                <!-- <button class="btn btn-primary btn-md d-block mx-2 text-light" id="upload_profile" type="button">Upload New</button> -->
                <input type="submit" class="btn btn-primary text-light ms-0 mx-lg-3" name="save_image" value="Save Image">
              </div>
            </form>

            <form id="profileForm" method="post" action="">
              <!-- Personal Information -->
              <div class="row row-cols-1 row-cols-md-3">
                <div class="col mb-3">
                  <label for="firstName" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="firstName" placeholder="First name" name="firstname" value="<?= (isset($_POST['firstname'])) ? $_POST['firstname'] : $_SESSION['firstname'] ?>">
                  <?php
                  if (isset($_POST['firstname']) && !validate_field($_POST['firstname'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">First name is required.</p>
                  <?php
                  }
                  ?>
                </div>
                <div class="col mb-3">
                  <label for="middleName" class="form-label">Middle Name</label>
                  <input type="text" class="form-control" id="middleName" placeholder="Middle name" name="middlename" value="<?= (isset($_POST['middlename'])) ? $_POST['middlename'] : $_SESSION['middlename'] ?>">
                </div>
                <div class="col mb-3">
                  <label for="lastName" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="lastName" placeholder="Last name" name="lastname" value="<?= (isset($_POST['lastname'])) ? $_POST['lastname'] : $_SESSION['lastname'] ?>">
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
                <div class="col-12 col-md-6 mb-3">
                  <label for="gender" class="form-label">Gender</label>
                  <select id="gender" class="form-select" name="gender" required>
                    <option value="Male" <?php if ((isset($_POST['gender']) && $_POST['gender'] == "Male")) {
                                            echo 'selected';
                                          } else if ($_SESSION['gender'] == "Male") {
                                            echo "selected";
                                          } ?>>Male</option>
                    <option value="Female" <?php if ((isset($_POST['gender']) && $_POST['gender'] == "Female")) {
                                              echo 'selected';
                                            } else if ($_SESSION['gender'] == "Female") {
                                              echo "selected";
                                            } ?>>Female</option>
                    <option value="Other" <?php if ((isset($_POST['gender']) && $_POST['gender'] == "Other")) {
                                            echo 'selected';
                                          } else if ($_SESSION['gender'] == "Other") {
                                            echo "selected";
                                          } ?>>Other</option>
                  </select>
                  <?php
                  if (isset($_POST['gender']) && !validate_field($_POST['gender'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">No gender selected</p>
                  <?php
                  }
                  ?>
                </div>
                <div class="col-12 col-md-6 mb-3">
                  <label for="birthday" class="form-label">Birthdate</label>
                  <input type="date" class="form-control" id="birthday" required name="birthdate" value="<?= (isset($_POST['birthdate'])) ? $_POST['birthdate'] : date('Y-m-d', strtotime($_SESSION['birthdate'])) ?>">
                  <?php
                  if (isset($_POST['birthdate']) && !validate_field($_POST['birthdate'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">Birth date is required</p>
                  <?php
                  }
                  ?>
                </div>
              </div>

              <div class="row row-cols-1 row-cols-md-2">
                <!-- DROPDOWN WITH SESRCH -->
                <div class="col-12 col-md-6 mb-3">
                  <div class="col mb-3">
                    <label for="specialty" class="form-label">Medical Specialty</label>
                    <input type="text" class="form-control" id="specialty" name="specialty" placeholder="Medical Specialty" value="<?= (isset($_POST['specialty'])) ? $_POST['specialty'] : $_SESSION['specialty'] ?> ">
                  </div>
                  <?php
                  if (isset($_POST['specialty']) && !validate_field($_POST['specialty'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">Specialty is required</p>
                  <?php
                  }
                  ?>
                </div>
                <!-- DAPAT TEXT INPUT WITH SUGGEWSTION -->
                <div class="col-12 col-md-6 mb-3">
                  <label for="contact" class="form-label">Contact</label>
                  <input type="text" class="form-control" id="contact" name="contact" inputmode="numeric" title="Format: 09XX XXX XXXX" placeholder="Contact No." maxlength="13" pattern="09\d{2} \d{3} \d{4}" oninput="formatPhoneNumber(this)" required value="<?= (isset($_POST['contact'])) ? $_POST['contact'] : $_SESSION['contact'] ?> ">
                  <?php
                  if (isset($_POST['contact']) && !validate_field($_POST['contact'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">Contact is required</p>
                  <?php
                  }
                  ?>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" readonly class="form-control" id="email" placeholder="example@wmsu.edu.ph" name="email" value="<?= (isset($_POST['email'])) ? $_POST['email'] : $_SESSION['email'] ?> ">
                  <?php
                  if (isset($_POST['email']) && !validate_field($_POST['email'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">Email is required</p>
                  <?php
                  }
                  ?>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="address" class="form-label">Address</label>
                  <input type="address" class="form-control" id="address" placeholder="Address" name="address" value="<?= (isset($_POST['address'])) ? $_POST['address'] : $_SESSION['address'] ?> ">
                  <?php
                  if (isset($_POST['address']) && !validate_field($_POST['address'])) {
                  ?>
                    <p class="text-dark m-0 ps-2">Address is required</p>
                  <?php
                  }
                  ?>
                </div>
              </div>
              <!-- Gender -->
              <!-- <div class="row mb-3">
                <label class="form-label">Gender</label>
                <div class="col">
                  <div class="form-check form-check-inline border border-2 border-dark rounded-2 px-4 ps-5 py-3">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male">
                    <label class="form-check-label" for="male">Male</label>
                  </div>
                  <div class="form-check form-check-inline border border-2 border-dark rounded-2 px-4 ps-5 py-3">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male">
                    <label class="form-check-label" for="male">Male</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                    <label class="form-check-label" for="female">Female</label>
                  </div>
                </div>
              </div> -->

              <!-- Tax Information -->
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea class="form-control" id="bio" rows="3" maxlength="255" name="bio"><?= (isset($_POST['bio'])) ? $_POST['bio'] : $_SESSION['bio'] ?></textarea>
                    <?php
                    if (isset($_POST['bio']) && !validate_field($_POST['bio'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">Bio is required</p>
                    <?php
                    }
                    ?>
                  </div>
                </div>
              </div>

              <!-- Address
              <div class="mb-3">
                <label for="address" class="form-label">Residential Address</label>
                <input type="text" class="form-control" id="address" placeholder="123 street, city">
              </div> -->

              <!-- Save Button -->
              <input type="submit" class="btn btn-primary text-light" name="save" value="Save Changes">
            </form>

            <form method="post" action="" enctype="multipart/form-data">
              <div class="d-flex flex-column flex-lg-row align-items-center mx-4 mb-4">
                <!-- Profile Picture -->
                <div class="campus-pic align-items-end">
                  <label class="label brand-border-color d-flex flex-column" for="file" style="border-width: 4px !important;">
                    <i class="bx bxs-camera-plus text-light p-2 bg-primary"></i>
                    <span>Change E-signature</span>
                  </label>

                  <img src="<?php if (isset($_SESSION['e_signature'])) {
                              echo "../assets/images/" . $_SESSION['e_signature'];
                            } else {
                              echo "../assets/images/defualt_profile.png";
                            } ?>" id="outputesign" class="rounded-circle" alt="User Avatar">

                  <input id="file" type="file" name="e_signature" accept=".jpg, .jpeg, .png" required onchange="previewImageesign(event)">
                </div>
                <!-- <button class="btn btn-primary btn-md d-block mx-2 text-light" id="upload_profile" type="button">Upload New</button> -->
                <input type="submit" class="btn btn-primary text-light ms-0 mx-lg-3" name="save_e_signature" value="Save E-Signature">
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
  <?php
  if (isset($_POST['save']) && $success == 'success') {
  ?>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Doctor account is successfully updated!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row d-flex">
              <div class="col-12 text-center">
                <a href="./settings_profile.php" class="text-decoration-none text-dark">
                  <p class="m-0 text-primary fw-bold">Click to Continue.</p>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  } else if (isset($_POST['save_image']) && $success == 'success') {
  ?>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Account image is succesfully updated!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row d-flex">
              <div class="col-12 text-center">
                <a href="./settings_profile.php" class="text-decoration-none text-dark">
                  <p class="m-0 text-primary fw-bold">Click to Continue.</p>
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
  <!-- <script src="../js/doctor/settings_profile.js"></script> -->
  <script src="../js/main.js"></script>
  <script src="../js/imageChange.js"></script>
  <script src="../js/eSign.js"></script>
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