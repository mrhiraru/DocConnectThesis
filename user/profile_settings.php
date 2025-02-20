<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php 
  $title = 'DocConnect | Profile';
	include '../includes/head.php';
?>
<body>
  <?php 
    require_once ('../includes/header.php');
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
                <i class='bx bx-user text-primary display-6 me-2' ></i>
                <h4 class="mb-0">Account</h4>
              </div>
              <hr class="mt-2 mb-3" style="height: 2.5px;">
              <form action="#.php" method="post">
                <div class="row">
                  <div class="col-md-4">
                    <!-- Image Upload Section -->
                    <div class="d-flex flex-column align-items-center mx-4 mb-4">
                      <!-- Profile Picture -->
                      <div class="campus-pic align-items-end">
                        <label class="label brand-border-color d-flex flex-column" for="file" style="border-width: 4px !important; border-radius: 5px !important;">
                          <span>Change Image</span>
                        </label>

                        <img src="../assets/images/66f5b7cd6432c4.31527220.jpg" id="output" class="rounded-2" alt="User Avatar">

                        <!-- Image Upload Input -->
                        <input id="file" type="file" name="account_image" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                      </div>

                      <!-- Upload Button -->
                      <button class="btn btn-primary text-light" id="uploadProfileImage" type="button">Upload Image</button>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <!-- ---NAME--- -->
                    <div class="row mb-3">
                      <div class="col-12 mb-3 mb-md-0">
                        <label for="firstName" class="form-label text-black-50">First Name</label>
                        <input type="text" class="form-control bg-light border border-dark" id="firstName" name="first_name" required>
                      </div>
                      <div class="col-12 mb-3 mb-md-0">
                        <label for="middleName" class="form-label text-black-50">Middle Name</label>
                        <input type="text" class="form-control bg-light border border-dark" id="middleName" name="middle_name">
                      </div>
                      <div class="col-12">
                        <label for="lastName" class="form-label text-black-50">Last Name</label>
                        <input type="text" class="form-control bg-light border border-dark" id="lastName" name="last_name" required>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- ---2nd ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="occupation" class="form-label text-black-50">Occupation</label>
                    <input type="text" class="form-control bg-light border border-dark" id="occupation" name="occupation" value="Student" disabled>
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="schoolID" class="form-label text-black-50">School ID</label>
                    <input type="text" class="form-control bg-light border border-dark" id="schoolID" name="school_id" required>
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="gender" class="form-label text-black-50">Gender</label>
                    <select class="form-select bg-light border border-dark" id="gender" name="gender" required>
                      <option value="" disabled selected>Please Select</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>

                <!-- ---3rd ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-7 mb-3 mb-md-0">
                    <label for="email" class="form-label text-black-50">Email</label>
                    <input type="email" class="form-control bg-light border border-dark" id="email" name="email" placeholder="example@example.com" required>
                  </div>
                  <div class="col-md-5 mb-3 mb-md-0">
                    <label for="phoneNo" class="form-label text-black-50">Phone No.</label>
                    <input type="text" class="form-control bg-light border border-dark" id="phoneNo" name="Phone_No" value="+63 " pattern="\+63 \d{3} \d{3} \d{4}" required/>
                  </div>
                </div>
                
                <!-- ---4th ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="age" class="form-label text-black-50">Age</label>
                    <input type="text" class="form-control bg-light border border-dark" id="age" name="age" required>
                  </div>
                  <div class="col-md-4 mb-3 mb-md-0">
                    <label for="height" class="form-label text-black-50">Height <span class="text-small">(cm)</span></label>
                    <div class="input-group">
                      <input type="number" class="form-control bg-light border border-dark" id="height" name="height" placeholder="Enter height" required/>
                      <span class="input-group-text bg-light border border-dark">cm</span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="weight" class="form-label text-black-50">Weight <span class="text-small">(kg)</span></label>
                    <div class="input-group">
                      <input type="number" class="form-control bg-light border border-dark" id="weight" name="weight" placeholder="Enter weight" required/>
                      <span class="input-group-text bg-light border border-dark">kg</span>
                    </div>
                  </div>
                </div>
                <!-- ---4th ROW--- -->
                <div class="row mb-3">
                  <div class="col-md-12 mb-3 mb-md-0">
                    <label for="address" class="form-label text-black-50">Address</label>
                    <input type="text" class="form-control bg-light border border-dark" id="address" name="address">
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
                  <input type="submit" class="btn btn-primary text-light" name="save" value="Save Changes">
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="../js/user/profie_settings.js"></script>

  <?php 
    require_once ('../includes/footer.php');
  ?>

</body>
</html>