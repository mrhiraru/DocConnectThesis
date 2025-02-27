<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
    header('location: ../index.php');
    exit();
}

require_once('../classes/medical_history.class.php');
require_once('../tools/functions.php');

if (isset($_POST['save'])) {
    $medhis = new MedHis();

    $medhis->his_condition = htmlentities($_POST['condition']);
    $medhis->diagnosis_date = htmlentities($_POST['year']);

    if (validate_field($medhis->his_condition) && validate_field($medhis->diagnosis_date)) {
        if ($medhis->add_medhis()) {
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
                                                <label for="firstNaconditionme" class="form-label text-black-50">Medical Condition</label>
                                                <input type="text" class="form-control bg-light border border-dark" id="condition" name="condition" value="<?= isset($_POST['condition']) ? $_POST['condition'] : '' ?>" required>
                                                <?php
                                                if (isset($_POST['condition']) && !validate_field($_POST['condition'])) {
                                                ?>
                                                    <p class="text-dark m-0 ps-2">Medical condition is required.</p>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="col-12">
                                                <label for="year" class="form-label text-black-50">Year Diagnosed</label>
                                                <input type="number" class="form-control bg-light border border-dark" id="year" name="year" min="1900" max="2100" value="<?= isset($_POST['year']) ? $_POST['year'] : '' ?>" required>
                                                <?php
                                                if (isset($_POST['year']) && !validate_field($_POST['year'])) {
                                                ?>
                                                    <p class="text-dark m-0 ps-2">Year diagnosed is required.</p>
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
                        <h5 class="modal-title" id="myModalLabel">Medical Condition is successfully added!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row d-flex">
                            <div class="col-12 text-center">
                                <a href="./add_medhis.php" class="text-decoration-none text-dark">
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