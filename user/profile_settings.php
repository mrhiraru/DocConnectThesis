<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
  header('location: ../index.php');
  exit();
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
          $setting = 'active';
          $aSetting = 'page';
          $cSetting = 'text-dark';

          include 'profile_nav.php';
          ?>

          <div class="card bg-body-tertiary mb-4">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <i class='bx bx-user text-primary display-6 me-2'></i>
                <h4 class="mb-0">Account</h4>
              </div>
              <hr class="mt-2 mb-3" style="height: 2.5px;">
              
                <div class="row">
                  <form action="" method="post" class="col-md-4" enctype="multipart/form-data">
                    <!-- Image Upload Section -->
                    <div class="d-flex flex-column align-items-center mx-4 mb-4">
                      <!-- Profile Picture -->
                      <div class="campus-pic align-items-end">
                        <label class="label brand-border-color d-flex flex-column" for="file" style="border-width: 4px !important; border-radius: 5px !important;">
                          <span>Change Image</span>
                        </label>

                        <!-- Image Preview -->
                        <img id="output" class="rounded-2"
                          src="<?php if (isset($_SESSION['account_image'])) {
                                  echo "../assets/images/" . $_SESSION['account_image'];
                                } else {
                                  echo "../assets/images/default_profile.png";
                                } ?>"
                          alt="User Avatar" style="max-width: 150px; max-height: 150px; object-fit: cover;">

                        <!-- <img id="output" class="rounded-2"
                          src="<?php echo isset($_SESSION['account_image'])
                                  ? "../assets/images/" . $_SESSION['account_image']
                                  : "../assets/images/default_profile.png"; ?>"
                          alt="User Avatar" style="max-width: 150px; max-height: 150px; object-fit: cover;"> -->

                        <!-- Image Upload Input -->
                        <input id="file" type="file" name="account_image" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                      </div>

                      <!-- Upload Button -->
                      <button class="btn btn-primary text-light" id="uploadProfileImage" type="submit" name="save_image">Upload Image</button>
                    </div>
                  </form>

                  <form action="" method="" class="col-md-8">
                    <!-- ---NAME--- -->
                    <div class="row mb-3">
                      <div class="col-12 mb-3 mb-md-0">
                        <label for="firstName" class="form-label text-black-50">First Name</label>
                        <input type="text" class="form-control bg-light border border-dark" id="firstName" name="first_name" value="<?= isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "" ?>" required>
                        <?php
                        if (isset($_POST['firstname']) && !validate_field($_POST['firstname'])) {
                        ?>
                          <p class="text-dark m-0 ps-2">First name is required.</p>
                        <?php
                        }
                        ?>
                      </div>
                      <div class="col-12 mb-3 mb-md-0">
                        <label for="middleName" class="form-label text-black-50">Middle Name</label>
                        <input type="text" class="form-control bg-light border border-dark" id="middleName" name="middle_name" value="<?= isset($_SESSION['middlename']) ? $_SESSION['middlename'] : "" ?>">
                      </div>
                      <div class="col-12">
                        <label for="lastName" class="form-label text-black-50">Last Name</label>
                        <input type="text" class="form-control bg-light border border-dark" id="lastName" name="last_name" value="<?= isset($_SESSION['lastname']) ? $_SESSION['lastname'] : "" ?>" required>
                        <?php
                        if (isset($_POST['lastname']) && !validate_field($_POST['lastname'])) {
                        ?>
                          <p class="text-dark m-0 ps-2">Last name is required.</p>
                        <?php
                        }
                        ?>
                      </div>
                    </div>
                  </form>
                </div>

                <!-- ---2nd ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="campus" class="form-label text-black-50">Campus</label>
                    <!-- WALA PA TO SA DATABASE -->
                    <select class="form-select bg-light border border-dark" id="campus" name="campus" required>
                      <option value="chooseCampus" <?= ($_SESSION['campus'] ?? "chooseCampus") == "chooseCampus" ? 'selected' : '' ?>>Choose Campus</option>
                      <option value="wmsuMainCampus" <?= (isset($_SESSION['campus']) && $_SESSION['campus'] == "wmsuMainCampus") ? 'selected' : '' ?>>WMSU main campus</option>
                      <option value="testCampus" <?= (isset($_SESSION['campus']) && $_SESSION['campus'] == "testCampus") ? 'selected' : '' ?>>test campus</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="schoolID" class="form-label text-black-50">School ID</label>
                    <input type="text" class="form-control bg-light border border-dark" id="schoolID" name="school_id" required>
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="gender" class="form-label text-black-50">Gender</label>
                    <select class="form-select bg-light border border-dark" id="gender" name="gender" required>
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
                  </div>
                </div>

                <!-- ---3rd ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-7 mb-3 mb-md-0">
                    <label for="email" class="form-label text-black-50">Email</label>
                    <input type="email" class="form-control bg-light border border-dark" id="email" name="email" placeholder="example@wmsu.edu.ph" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : "" ?>" required readonly>
                  </div>
                  <div class="col-md-5 mb-3 mb-md-0">
                    <label for="phoneNo" class="form-label text-black-50">Phone No.</label>
                    <input type="text" class="form-control bg-light border border-dark" id="phoneNo" name="Phone_No" value="<?= isset($_SESSION['contact']) ? $_SESSION['contact'] : "" ?>" pattern="\+63 \d{3} \d{3} \d{4}" required />
                    <?php
                    if (isset($_POST['contact']) && !validate_field($_POST['contact'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">Phone number is required</p>
                    <?php
                    }
                    ?>
                  </div>
                </div>

                <!-- ---4th ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="birthdate" class="form-label text-black-50">Birthdate</label>
                    <input type="date" class="form-control bg-light border border-dark" id="birthdate" name="birthdate" value="<?= $birthdate ?>" required>
                    <?php
                    if (isset($_POST['birthdate']) && !validate_field($_POST['birthdate'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">Birth date is required</p>
                    <?php
                    }
                    ?>
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="height" class="form-label text-black-50">Height <span class="text-small">(cm)</span></label>
                    <div class="input-group">
                      <input type="number" class="form-control bg-light border border-dark" id="height" name="height" placeholder="Enter height" required />
                      <span class="input-group-text bg-light border border-dark">cm</span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="weight" class="form-label text-black-50">Weight <span class="text-small">(kg)</span></label>
                    <div class="input-group">
                      <input type="number" class="form-control bg-light border border-dark" id="weight" name="weight" placeholder="Enter weight" required />
                      <span class="input-group-text bg-light border border-dark">kg</span>
                    </div>
                  </div>
                </div>
                <!-- ---4th ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-12 mb-3 mb-md-0">
                    <label for="address" class="form-label text-black-50">Address</label>
                    <input type="text" class="form-control bg-light border border-dark" id="address" name="address" value="<?= isset($_SESSION['address']) ? $_SESSION['address'] : "" ?>">
                    <?php
                    if (isset($_POST['address']) && !validate_field($_POST['address'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">Address is required</p>
                    <?php
                    }
                    ?>
                  </div>
                </div>

                <div class="text-end">
                  <input type="submit" class="btn btn-primary text-light" name="save" value="Save Changes">
                </div>
              </form>
            </div>
          </div>

          <div class="card bg-body-tertiary mb-4">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <i class='bx bxs-key text-primary display-6 me-2'></i>
                <h4 class="mb-0">Parent / Guardian</h4>
              </div>
              <hr class="my-2" style="height: 2.5px;">
              <form action="#.php" method="post">
                <div class="col-md-12">
                  <!-- ---NAME--- -->
                  <div class="row mb-3">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                      <label for="firstName" class="form-label text-black-50">First Name</label>
                      <input type="text" class="form-control bg-light border border-dark" id="firstName" name="first_name" value="<?= isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "" ?>" required>
                    </div>
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                      <label for="middleName" class="form-label text-black-50">Middle Name</label>
                      <input type="text" class="form-control bg-light border border-dark" id="middleName" name="middle_name" value="<?= isset($_SESSION['middlename']) ? $_SESSION['middlename'] : "" ?>">
                    </div>
                    <div class="col-12 col-md-4">
                      <label for="lastName" class="form-label text-black-50">Last Name</label>
                      <input type="text" class="form-control bg-light border border-dark" id="lastName" name="last_name" value="<?= isset($_SESSION['lastname']) ? $_SESSION['lastname'] : "" ?>" required>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-7 mb-3 mb-md-0">
                      <label for="email" class="form-label text-black-50">Email</label>
                      <input type="email" class="form-control bg-light border border-dark" id="email" name="email" placeholder="example@wmsu.edu.ph" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : "" ?>" required readonly>
                    </div>
                    <div class="col-md-5 mb-3 mb-md-0">
                      <label for="phoneNo" class="form-label text-black-50">Phone No.</label>
                      <input type="text" class="form-control bg-light border border-dark" id="phoneNo" name="Phone_No" value="<?= isset($_SESSION['contact']) ? $_SESSION['contact'] : "" ?>" pattern="\+63 \d{3} \d{3} \d{4}" required />
                      <?php
                      if (isset($_POST['contact']) && !validate_field($_POST['contact'])) {
                      ?>
                        <p class="text-dark m-0 ps-2">Phone number is required</p>
                      <?php
                      }
                      ?>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <input type="submit" class="btn btn-primary text-light" name="save" value="Save Changes">
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="../js/user/profile_settings.js"></script>
  <script src="../js/imageChange.js"></script>

  <?php
  require_once('../includes/footer.php');
  ?>

</body>

</html>