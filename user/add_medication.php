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
                                <h5 class="mb-0">Add Medicatiom</h5>
                            </div>
                            <hr class="mt-2 mb-3" style="height: 2.5px;">
                            <form id="profileForm" action="" method="post" enctype="multipart/form-data">
                                <div class="row">
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