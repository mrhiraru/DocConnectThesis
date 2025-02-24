<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Patient Files';
$patient = 'active';
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
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-4">
                <?php
                require_once('../includes/breadcrumb-patient.php');
                ?>
                <div class="p-0 m-0 row">
                    <p class="m-0 p-0 fs-5 text-dark fw-semibold mb-1">Doctor Uploads <button class="fs-6 float-end btn btn-primary text-white">Add File</button></p>
                    <a href="" class="m-0 p-0 fs-6 text-dark mb-3">- Test_Result_April_2024</a>
                    <p class="m-0 p-0 fs-5 text-dark fw-semibold mb-1">Patient Uploads</p>
                    <a href="" class="m-0 p-0 fs-6 text-dark">- Patient_Record_Jane_Smith_April_2024</a>
                    <a href="" class="m-0 p-0 fs-6 text-dark mb-3">- Jane_Smith_XRay_Chest_January_2024</a>
                </div>
            </main>
        </div>
    </div>
</body>
</html>