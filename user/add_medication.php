<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
    header('location: ../index.php');
    exit();
}

require_once('../classes/medication.class.php');

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
                            <form id="profileForm" action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-4">
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
                                    <div class="col-md-8">
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
                                    </div>
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
                                <h4 class="mb-0">Other info</h4>
                            </div>
                            <hr class="my-2" style="height: 2.5px;">
                            <form action="#.php" method="post">

                                <!-- Medical History -->
                                <h5 class="text-primary">Medical History</h5>
                                <div class="mb-3">
                                    <label class="form-label">Do you have any pre-existing conditions?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="conditions[]" value="Diabetes">
                                        <label class="form-check-label">Diabetes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="conditions[]" value="Hypertension">
                                        <label class="form-check-label">Hypertension</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="conditions[]" value="Asthma">
                                        <label class="form-check-label">Asthma</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="conditions[]" value="Heart Disease">
                                        <label class="form-check-label">Heart Disease</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="otherCondition">
                                        <label class="form-check-label">Other</label>
                                        <input type="text" class="form-control mt-2 d-none" id="otherConditionText" name="conditions[]">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Have you undergone any major surgeries?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="surgery" value="Yes" id="surgeryYes">
                                        <label class="form-check-label" for="surgeryYes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="surgery" value="No" id="surgeryNo">
                                        <label class="form-check-label" for="surgeryNo">No</label>
                                    </div>
                                </div>

                                <div class="mb-3 d-none" id="surgeryDetails">
                                    <label class="form-label">If yes, specify:</label>
                                    <input type="text" class="form-control" name="surgery_details">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Do you have any known allergies?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="allergies" value="Yes" id="allergyYes">
                                        <label class="form-check-label" for="allergyYes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="allergies" value="No" id="allergyNo">
                                        <label class="form-check-label" for="allergyNo">No</label>
                                    </div>
                                </div>

                                <div class="mb-3 d-none" id="allergyDetails">
                                    <label class="form-label">If yes, specify:</label>
                                    <input type="text" class="form-control" name="allergy_details">
                                </div>

                                <hr>

                                <!-- Medication -->
                                <h5 class="text-primary">Medication</h5>
                                <div class="mb-3">
                                    <label class="form-label">Are you currently taking any medications?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="medications" value="Yes" id="medicationsYes">
                                        <label class="form-check-label" for="medicationsYes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="medications" value="No" id="medicationsNo">
                                        <label class="form-check-label" for="medicationsNo">No</label>
                                    </div>
                                </div>

                                <div id="medicationList" class="d-none">
                                    <label class="form-label">If yes, list them:</label>
                                    <div class="medication-entry mb-2">
                                        <input type="text" class="form-control mb-2" name="medication_name[]" placeholder="Medication Name">
                                        <input type="text" class="form-control mb-2" name="medication_dosage[]" placeholder="Dosage">
                                        <input type="text" class="form-control mb-2" name="medication_frequency[]" placeholder="Frequency">
                                        <input type="text" class="form-control mb-2" name="medication_purpose[]" placeholder="Purpose">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary text-light" id="addMedication">Add More</button>
                                </div>

                                <hr>

                                <!-- Immunization -->
                                <h5 class="text-primary">Immunization</h5>
                                <div class="mb-3">
                                    <label class="form-label">Have you received any vaccinations?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="vaccinations" value="Yes" id="vaccinationsYes">
                                        <label class="form-check-label" for="vaccinationsYes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="vaccinations" value="No" id="vaccinationsNo">
                                        <label class="form-check-label" for="vaccinationsNo">No</label>
                                    </div>
                                </div>

                                <div id="vaccinationList" class="d-none">
                                    <label class="form-label">If yes, list them:</label>
                                    <div class="vaccination-entry mb-2">
                                        <input type="text" class="form-control mb-2" name="vaccine_name[]" placeholder="Vaccine Name">
                                        <input type="date" class="form-control mb-2" name="vaccine_date[]">
                                        <select class="form-control mb-2" name="booster_required[]">
                                            <option value="Yes">Booster Required</option>
                                            <option value="No">No Booster Required</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary text-light" id="addVaccination">Add More</button>
                                </div>

                                <div class="text-end mt-3">
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

    <script src="../js/imageChange.js"></script>

    <?php
    require_once('../includes/footer.php');
    ?>

</body>

</html>