<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
    header('location: ../index.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/appointment.class.php');
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
                    $files = 'active';
                    $aFiles = 'page';
                    $cFiles = 'text-dark';

                    include 'profile_nav.php';
                    ?>

                    <div class="card bg-body-tertiary mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-2 text-green">My Doctors</h5>
                            <hr>
                            <div class="p-0 m-0 row">
                                <p class="m-0 p-0 fs-5 text-dark fw-semibold mb-1">Doctor Uploads <button class="fs-6 float-end btn btn-primary text-white">Add File</button></p>
                                <a href="" class="m-0 p-0 fs-6 text-dark mb-3">- Test_Result_April_2024</a>
                                <p class="m-0 p-0 fs-5 text-dark fw-semibold mb-1">Patient Uploads</p>
                                <a href="" class="m-0 p-0 fs-6 text-dark">- Patient_Record_Jane_Smith_April_2024</a>
                                <a href="" class="m-0 p-0 fs-6 text-dark mb-3">- Jane_Smith_XRay_Chest_January_2024</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    require_once('../includes/footer.php');
    ?>
</body>

</html>