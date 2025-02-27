<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
    header('location: ../index.php');
    exit();
}

require_once('../classes/allergy.class.php');
require_once('../tools/functions.php');

if (isset($_POST['save'])) {
    $immu = new Immunization();

    $immu->immunization_name = htmlentities($_POST['immunization_name']);
    $immu->patient_id = htmlentities($_SESSION['patient_id']);

    if (validate_field($immu->immunization_name)) {
        if ($immu->add_immu()) {
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
                                <h5 class="mb-0">Add allergyical History</h5>
                            </div>
                            <hr class="mt-2 mb-3" style="height: 2.5px;">
                            <form id="profileForm" action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12">
                                        <!-- ---NAME--- -->
                                        <div class="row mb-3">
                                            <div class="col-12 mb-3 mb-md-0">
                                                <label for="immunization_name" class="form-label text-black-50">Immunization Name</label>
                                                <input type="text" class="form-control bg-light border border-dark" id="immunization_name" name="immunization_name" value="<?= isset($_POST['immunization_name']) ? $_POST['immunization_name'] : '' ?>" required>
                                                <?php
                                                if (isset($_POST['immunization_name']) && !validate_field($_POST['immunization_name'])) {
                                                ?>
                                                    <p class="text-dark m-0 ps-2">Immunization name is required.</p>
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
                        <h5 class="modal-title" id="myModalLabel">Immunization is successfully added!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row d-flex">
                            <div class="col-12 text-center">
                                <a href="./add_immunization.php" class="text-decoration-none text-dark">
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