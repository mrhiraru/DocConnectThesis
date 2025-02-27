<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
    header('location: ../index.php');
    exit();
}

require_once('../classes/medication.class.php');
require_once('../tools/functions.php');

if (isset($_POST['save'])) {
    $med = new Medication();

    $med->medication_name = htmlentities($_POST['medname']);
    $med->dosage = htmlentities($_POST['dosage']);
    $med->med_usage = htmlentities($_POST['frequency']);
    $med->patient_id = htmlentities($_SESSION['patient_id']);

    if (validate_field($med->medication_name) && validate_field($med->dosage) && $med->med_usage) {
        if ($med->add_med()) {
            $success = "success";
        } else {
            $success = "failed";
        }
    } else {
        $success = "failed";
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
                                <h5 class="mb-0">Add Medical History</h5>
                            </div>
                            <hr class="mt-2 mb-3" style="height: 2.5px;">
                            <form id="profileForm" action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12">
                                        <!-- ---NAME--- -->
                                        <div class="row mb-3">
                                            <div class="col-12 mb-3 mb-md-0">
                                                <label for="medname" class="form-label text-black-50">Medical Condition</label>
                                                <input type="text" class="form-control bg-light border border-dark" id="medname" name="medname" value="<?= isset($_POST['medname']) ? $_POST['medname'] : '' ?>" required>
                                                <?php
                                                if (isset($_POST['medname']) && !validate_field($_POST['medname'])) {
                                                ?>
                                                    <p class="text-dark m-0 ps-2">Medication name is required.</p>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="col-12">
                                                <label for="dosage" class="form-label text-black-50">Dosage</label>
                                                <input type="text" class="form-control bg-light border border-dark" id="dosage" name="dosage" value="<?= isset($_POST['dosage']) ? $_POST['dosage'] : '' ?>" required>
                                                <?php
                                                if (isset($_POST['dosage']) && !validate_field($_POST['dosage'])) {
                                                ?>
                                                    <p class="text-dark m-0 ps-2">Dosage is required.</p>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="col-12">
                                                <label for="frequency" class="form-label text-black-50">Frequency</label>
                                                <input type="text" class="form-control bg-light border border-dark" id="frequency" name="frequency"  value="<?= isset($_POST['frequency']) ? $_POST['frequency'] : '' ?>" required>
                                                <?php
                                                if (isset($_POST['frequency']) && !validate_field($_POST['frequency'])) {
                                                ?>
                                                    <p class="text-dark m-0 ps-2">Usage frequency is required.</p>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <input type="submit" class="btn btn-primary text-light" name="save" value="Save">
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php
    if (isset($_POST['save']) && $success == 'success') {
    ?>
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Medication is successfully added!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row d-flex">
                            <div class="col-12 text-center">
                                <a href="./add_medication.php" class="text-decoration-none text-dark">
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

    <?php
    require_once('../includes/footer.php');
    ?>

    <script src="../js/main.js"></script>
</body>

</html>