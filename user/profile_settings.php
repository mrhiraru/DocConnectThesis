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
require_once('../classes/campus.class.php');
require_once('../classes/patient.class.php');

$account_class = new Account(); 
if (isset($_POST['save'])) {
  $account_class->account_id = $_SESSION['account_id'];

  $account_class->firstname = ucfirst(strtolower(htmlentities($_POST['firstname'])));
  if (isset($_POST['middlename'])) {
    $account_class->middlename = ucfirst(strtolower(htmlentities($_POST['middlename'])));
  } else {
    $account_class->middlename = '';
  }
  $account_class->lastname = ucfirst(strtolower(htmlentities($_POST['lastname'])));
  $account_class->contact = htmlentities($_POST['contact']);
  $account_class->address = htmlentities($_POST['address']);
  $account_class->birthdate = htmlentities($_POST['birthdate']);
  $account_class->height = htmlentities($_POST['height']);
  $account_class->weight = htmlentities($_POST['weight']);
  $account_class->email = htmlentities($_POST['email']);
  $account_class->suffix = isset($_POST['suffix']) ? htmlentities($_POST['suffix']) : '';
  $account_class->religion = isset($_POST['religion']) ? htmlentities($_POST['religion']) : '';
  $account_class->civil_status = isset($_POST['civil_status']) ? htmlentities($_POST['civil_status']) : '';

  if (isset($_POST['gender'])) {
    $account_class->gender = htmlentities($_POST['gender']);
  } else {
    $account_class->gender = '';
  }
  if (isset($_POST['campus_id'])) {
    $account_class->campus_id = htmlentities($_POST['campus_id']);
  } else {
    $account_class->campus_id = '';
  }
  if (isset($_POST['role'])) {
    $account_class->role = htmlentities($_POST['role']);
  } else {
    $account_class->role = '';
  }

  if (
    validate_field($account_class->firstname) &&
    validate_field($account_class->lastname) &&
    validate_field($account_class->gender) &&
    validate_field($account_class->email) &&
    validate_field($account_class->contact) &&
    validate_field($account_class->birthdate) &&
    validate_field($account_class->address) &&
    validate_field($account_class->role) &&
    validate_field($account_class->height) &&
    validate_field($account_class->weight)
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
      $_SESSION['role'] = $account_class->role;
      $_SESSION['campus_id'] = $account_class->campus_id;
      $_SESSION['suffix'] = $account_class->suffix;
      $_SESSION['religion'] = $account_class->religion;
      $_SESSION['civil_status'] = $account_class->civil_status;

      $_SESSION['height'] = $account_class->height;
      $_SESSION['weight'] = $account_class->weight;
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

if (isset($_POST['save_parent'])) {
  $parent = new Patient();

  $parent->parent_name = ucwords(strtolower(htmlentities($_POST['parent_name'])));
  $parent->parent_email = htmlentities($_POST['parent_email']);
  $parent->parent_contact = htmlentities($_POST['parent_contact']);
  $parent->account_id = $_SESSION['account_id'];

  if (
    validate_field($parent->parent_name) && validate_field($parent->parent_email) && validate_field($parent->parent_contact)
  ) {
    if ($parent->update_parent_guardian()) {
      $success = 'success';
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
              <form action="" method="post" enctype="multipart/form-data">
                <div class="col-md-12">
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
                </div>
              </form>
              <form id="profileForm" action="" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-12">
                    <!-- ---NAME--- -->
                    <div class="row mb-3">
                      <div class="col-12 col-md-4">
                        <label for="lastName" class="form-label text-black-50">Last Name</label>
                        <input type="text" class="form-control bg-light border border-dark" id="lastName" name="lastname" value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : $_SESSION['lastname'] ?>" required>
                        <?php
                        if (isset($_POST['lastname']) && !validate_field($_POST['lastname'])) {
                        ?>
                          <p class="text-dark m-0 ps-2">Last name is required.</p>
                        <?php
                        }
                        ?>
                      </div>
                      <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <label for="firstName" class="form-label text-black-50">First Name</label>
                        <input type="text" class="form-control bg-light border border-dark" id="firstName" name="firstname" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : $_SESSION['firstname'] ?>" required>
                        <?php
                        if (isset($_POST['firstname']) && !validate_field($_POST['firstname'])) {
                        ?>
                          <p class="text-dark m-0 ps-2">First name is required.</p>
                        <?php
                        }
                        ?>
                      </div>
                      <div class="col-12 col-md-3 mb-3 mb-md-0">
                        <label for="middleName" class="form-label text-black-50">Middle Name</label>
                        <input type="text" class="form-control bg-light border border-dark" id="middleName" name="middlename" value="<?= isset($_POST['middlename']) ? $_POST['middlename'] : $_SESSION['middlename'] ?>">
                      </div>
                      <div class="col-12 col-md-1 mb-3 mb-md-0">
                        <label for="middleName" class="form-label text-black-50">Sfx</label>
                        <input type="text" class="form-control bg-light border border-dark" id="middleName" name="middlename" value="<?= isset($_POST['middlename']) ? $_POST['middlename'] : $_SESSION['middlename'] ?>">
                      </div>
                    </div>
                  </div>
                </div>

                <!-- ---2nd ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="campus" class="form-label text-black-50">Campus</label>
                    <!-- WALA PA TO SA DATABASE -->
                    <select class="form-select bg-light border border-dark" id="campus_id" name="campus_id" required>
                      <?php
                      $campus = new Campus();
                      $campusArray = $campus->show_campus();
                      foreach ($campusArray as $item) {
                      ?>
                        <option value="<?= $item['campus_id'] ?>"
                          <?= (isset($_POST['campus_id']) && $_POST['campus_id'] == $item['campus_id'])
                            || (isset($_SESSION['campus_id']) && $_SESSION['campus_id'] == $item['campus_id'])
                            ? 'selected' : '' ?>>
                          <?= $item['campus_name'] ?>
                        </option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="role" class="form-label text-black-50">Role</label>
                    <select class="form-select bg-light border border-dark" name="role" id="role">
                      <option value="Student" <?php if ((isset($_POST['role']) && $_POST['role'] == "Student") || (isset($_SESSION['role']) && $_SESSION['role'] == "Student")) {
                                                echo 'selected';
                                              } ?>>Student</option>
                      <option value="Employee" <?php if ((isset($_POST['role']) && $_POST['role'] == "Employee") || (isset($_SESSION['role']) && $_SESSION['role'] == "Employee")) {
                                                  echo 'selected';
                                                } ?>>Employee</option>
                      <option value="Faculty" <?php if ((isset($_POST['role']) && $_POST['role'] == "Faculty") || (isset($_SESSION['role']) && $_SESSION['role'] == "Faculty")) {
                                                echo 'selected';
                                              } ?>>Faculty</option>
                      <option value="Alumni" <?php if ((isset($_POST['role']) && $_POST['role'] == "Alumni") || (isset($_SESSION['role']) && $_SESSION['role'] == "Alumni")) {
                                                echo 'selected';
                                              } ?>>Alumni</option>
                    </select>
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
                <div class="row row-cols-1 row-cols-md-2 mb-3">
                  <div class="col mb-3 mb-md-0">
                    <label for="religion" class="form-label text-black-50">Religion</label>
                    <input type="religion" class="form-control bg-light border border-dark" id="religion" name="religion" value="<?= isset($_POST['religion']) ? $_POST['religion'] : $_SESSION['religion'] ?>" required>
                  </div>
                  <div class="col mb-3 mb-md-0">
                    <label for="civil_status" class="form-label text-black-50">Civil Status</label>
                    <select class="form-select bg-light border border-dark" id="civil_status" name="civil_status" required>
                      <option value="Single" <?php if ((isset($_POST['civil_status']) && $_POST['civil_status'] == "Single")) {
                                                echo 'selected';
                                              } else if ($_SESSION['civil_status'] == "Single") {
                                                echo "selected";
                                              } ?>>Single</option>
                      <option value="Married" <?php if ((isset($_POST['civil_status']) && $_POST['civil_status'] == "Married")) {
                                                echo 'selected';
                                              } else if ($_SESSION['civil_status'] == "Married") {
                                                echo "selected";
                                              } ?>>Married</option>
                      <option value="Divorce" <?php if ((isset($_POST['civil_status']) && $_POST['civil_status'] == "Divorce")) {
                                                echo 'selected';
                                              } else if ($_SESSION['civil_status'] == "Divorce") {
                                                echo "selected";
                                              } ?>>Divorced</option>
                      <option value="Widowed" <?php if ((isset($_POST['civil_status']) && $_POST['civil_status'] == "Widowed")) {
                                                echo 'selected';
                                              } else if ($_SESSION['civil_status'] == "Widowed") {
                                                echo "selected";
                                              } ?>>Widowed</option>
                    </select>
                  </div>
                </div>

                <!-- ---3rd ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-7 mb-3 mb-md-0">
                    <label for="email" class="form-label text-black-50">Email</label>
                    <input type="email" class="form-control bg-light border border-dark" id="email" name="email" placeholder="example@wmsu.edu.ph" value="<?= isset($_POST['email']) ? $_POST['email'] : $_SESSION['email'] ?>" required readonly>
                  </div>
                  <div class="col-md-5 mb-3 mb-md-0">
                    <label for="phoneNo" class="form-label text-black-50">Contact No.</label>
                    <input type="text" class="form-control bg-light border border-dark" id="contact" name="contact" inputmode="numeric" title="Format: 09XX XXX XXXX" maxlength="13" pattern="09\d{2} \d{3} \d{4}" value="<?= isset($_POST['contact']) ? $_POST['contact'] : $_SESSION['contact'] ?>" oninput="formatPhoneNumber(this)" required />
                    <?php
                    if (isset($_POST['contact']) && !validate_field($_POST['contact'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">Contact number is required</p>
                    <?php
                    }
                    ?>
                  </div>
                </div>

                <!-- ---4th ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="birthdate" class="form-label text-black-50">Birthdate</label>
                    <input type="date" class="form-control bg-light border border-dark" id="birthdate" name="birthdate" value="<?= isset($_POST['birthdate']) ? $_POST['birthdate'] : $birthdate ?>" required>
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
                      <input type="number" class="form-control bg-light border border-dark" id="height" name="height" value="<?= isset($_POST['height']) ? $_POST['height'] : $_SESSION['height'] ?>" placeholder="Enter height" required />
                      <span class="input-group-text bg-light border border-dark">cm</span>
                    </div>
                    <?php
                    if (isset($_POST['height']) && !validate_field($_POST['height'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">Height is required.</p>
                    <?php
                    }
                    ?>
                  </div>
                  <div class="col-md-4">
                    <label for="weight" class="form-label text-black-50">Weight <span class="text-small">(kg)</span></label>
                    <div class="input-group">
                      <input type="number" class="form-control bg-light border border-dark" id="weight" name="weight" value="<?= isset($_POST['weight']) ? $_POST['weight'] : $_SESSION['weight'] ?>" placeholder="Enter weight" required />
                      <span class="input-group-text bg-light border border-dark">kg</span>
                    </div>
                    <?php
                    if (isset($_POST['weight']) && !validate_field($_POST['weight'])) {
                    ?>
                      <p class="text-dark m-0 ps-2">Weight is required.</p>
                    <?php
                    }
                    ?>
                  </div>
                </div>
                <!-- ---4th ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-12 mb-3 mb-md-0">
                    <label for="address" class="form-label text-black-50">Address</label>
                    <input type="text" class="form-control bg-light border border-dark" id="address" name="address" value="<?= isset($_POST['address']) ? $_POST['address'] : $_SESSION['address'] ?>">
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
              <form action="" method="post">
                <?php
                $parent = new Patient();
                $parent_record = $parent->fetch_parent_guardian($_SESSION['account_id']);
                ?>
                <div class="col-md-12">
                  <!-- ---NAME--- -->
                  <div class="row mb-3">
                    <div class="col-12">
                      <label for="firstName" class="form-label text-black-50">Full Name</label>
                      <input type="text" class="form-control bg-light border border-dark" id="parent_name" name="parent_name" placeholder="Last, First, Middle" value="<?= isset($_POST['parent_name']) ? $_POST['parent_name'] : (isset($parent_record['parent_name']) ? $parent_record['parent_name'] : '') ?>" required>
                      <?php
                      if (isset($_POST['parent_name']) && !validate_field($_POST['parent_name'])) {
                      ?>
                        <p class="text-dark m-0 ps-2">Parent/Guardian name is required</p>
                      <?php
                      }
                      ?>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-7 mb-3 mb-md-0">
                      <label for="email" class="form-label text-black-50">Email</label>
                      <input type="email" class="form-control bg-light border border-dark" id="parent_email" name="parent_email" placeholder="example@wmsu.edu.ph" value="<?= isset($_POST['parent_email']) ? $_POST['parent_email'] : (isset($parent_record['parent_email']) ? $parent_record['parent_email'] : '') ?>" required>
                      <?php
                      if (isset($_POST['parent_email']) && !validate_field($_POST['parent_email'])) {
                      ?>
                        <p class="text-dark m-0 ps-2">Email is required</p>
                      <?php
                      }
                      ?>
                    </div>
                    <div class="col-md-5 mb-3 mb-md-0">
                      <label for="phoneNo" class="form-label text-black-50">Contact No.</label>
                      <input type="text" class="form-control bg-light border border-dark" id="parent_contact" name="parent_contact" inputmode="numeric" title="Format: 09XX XXX XXXX" maxlength="13" pattern="09\d{2} \d{3} \d{4}" value="<?= isset($_POST['parent_contact']) ? $_POST['parent_contact'] : (isset($parent_record['parent_contact']) ? $parent_record['parent_contact'] : '') ?>" oninput="formatPhoneNumber(this)" required />
                      <?php
                      if (isset($_POST['parent_contact']) && !validate_field($_POST['parent_contact'])) {
                      ?>
                        <p class="text-dark m-0 ps-2">Contact number is required</p>
                      <?php
                      }
                      ?>
                    </div>
                  </div>
                </div>
                <div class="text-end">
                  <input type="submit" class="btn btn-primary text-light" name="save_parent" value="Save Changes">
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php
  if ((isset($_POST['save']) || isset($_POST['save_image'])) && $success == 'success') {
  ?>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Account is successfully updated!</h5>
          </div>
          <div class="modal-body">
            <div class="row d-flex">
              <div class="col-12 text-center">
                <a href="./profile_general" class="text-decoration-none text-dark">
                  <p class="m-0 text-primary fw-bold">Click to Continue.</p>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  } else if (isset($_POST['save_parent']) && $success == 'success') {
  ?>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Parent/Guardian Information is successfully updated!</h5>
          </div>
          <div class="modal-body">
            <div class="row d-flex">
              <div class="col-12 text-center">
                <a href="./profile_general" class="text-decoration-none text-dark">
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

  <script src="../js/user/profile_settings.js"></script>
  <script src="../js/imageChange.js"></script>
  <script src="../js/main.js"></script>
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
  <?php
  require_once('../includes/footer.php');
  ?>

</body>

</html>