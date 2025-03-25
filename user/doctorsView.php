<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$doctor_id = isset($_GET['id']) ? intval($_GET['id']) : '';

$doctor = new Account();
$doctorDetails = $doctor->get_doctor_info_2($doctor_id);

// if (!$doctorDetails) {
//   header('location: doctors.php');
//   exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Doctor Profile';
include '../includes/head.php';
?>

<style>
    .profile-card {
        height: 100%;
    }

    @media (max-width:450px) {
        .profile-card {
            height: 300px !important;
        }
    }
</style>

<body>
    <?php require_once('../includes/header.php'); ?>

    <!-- Doctor Profile Section -->
    <section class="page-container padding-medium pt-3 p-3">
        <div class="border-primary border-bottom text-center mx-4 mb-3">
            <h1 class="text-green">Doctor Profile</h1>
            <p class="fs-5 fw-light">
                Detailed information about our healthcare professional
            </p>
        </div>
    </section>

    <!-- Doctor Details -->
    <section class="padding-medium py-4">
        <div class="container">
            <div class="row mx-3 mx-lg-5 mb-4 align-items-stretch">
                <div class="col-12 col-lg-5 mb-3 mb-lg-0">
                    <div class="profile-card h-100 me-4">
                        <div class="profile-image">
                            <img src="<?php if (isset($item['account_image'])) {
                                            echo "../assets/images/" . $item['account_image'];
                                        } else {
                                            echo "../assets/images/default_profile.png";
                                        } ?>" alt="Doctor Profile Image" class="img-fluid rounded shadow">
                        </div>

                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="details h-100">
                        <h2 class="text-green mb-3 fs-2">
                            DOCTOR NAME
                        </h2>

                        <div class="d-flex flex-column">
                            <div class="row mb-3 align-items-stretch">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <div class="card px-4 py-2 bg-light shadow-lg h-100">
                                        <h6 class="text-primary">Medical Specialty:</h6>
                                        <p class="fw-light"><?= !empty($doctorDetails['specialty']) ? htmlspecialchars($doctorDetails['specialty']) : 'Not specified' ?></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card px-4 py-2 bg-light shadow-lg h-100">
                                        <h6 class="text-primary">Working Schedule:</h6>
                                        <p class="fw-light">
                                            <?php
                                            if (
                                                !empty($doctorDetails['start_day']) && !empty($doctorDetails['end_day']) &&
                                                !empty($doctorDetails['start_wt']) && !empty($doctorDetails['end_wt'])
                                            ) {
                                                echo htmlspecialchars($doctorDetails['start_day']) . ' to ' .
                                                    htmlspecialchars($doctorDetails['end_day']) . ", " .
                                                    date('h:i A', strtotime($doctorDetails['start_wt'])) . ' - ' .
                                                    date('h:i A', strtotime($doctorDetails['end_wt']));
                                            } else {
                                                echo 'Not specified';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3 bg-light shadow-lg p-3">
                                <h4 class="text-primary mb-3">Contact Information</h4>
                                <div class="mb-2">
                                    <i class="fas fa-envelope me-2 text-green"></i>
                                    <span><?= !empty($doctorDetails['email']) ? htmlspecialchars($doctorDetails['email']) : 'Not provided' ?></span>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-phone me-2 text-green"></i>
                                    <span><?= !empty($doctorDetails['contact']) ? htmlspecialchars($doctorDetails['contact']) : 'Not provided' ?></span>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="card px-4 py-2 bg-light shadow-lg my-3">
                    <h6 class="text-primary">Professional Biography:</h6>
                    <p class="fw-light fs-6">
                        <?= !empty($doctorDetails['bio']) ? nl2br(htmlspecialchars($doctorDetails['bio'])) : 'No biography available' ?>
                    </p>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="./appointment.php?doctor_id=<?= $doctor_id ?>" class="btn btn-primary text-light me-2">
                        <i class="fas fa-calendar-check me-2"></i>Book Appointment
                    </a>
                    <a href="./chat_user?account_id=<?= $doctor_id ?>" class="btn btn-success text-light">
                        <i class="fas fa-comments me-2"></i>Start Chat
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php require_once('../includes/footer.php'); ?>
</body>

</html>