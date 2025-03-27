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

    <!-- Doctor Details -->
    <section class="page-container padding-medium py-4">
        <div class="col-md-6 ms-4 text-md-start">
            <button onclick="history.back()" class="bg-none d-flex align-items-center">

                <p class="btn btn-outline-secondary hover-light d-fex align-items-center">
                    <i class='bx bx-chevron-left'></i>
                    Back
                </p>
            </button>
        </div>
        <main class="mx-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="<?php if (isset($doctorDetails['account_image'])) {
                                        echo "../assets/images/" . $doctorDetails['account_image'];
                                    } else {
                                        echo "../assets/images/default_profile.png";
                                    } ?>" alt="Doctor Profile Image" class="img-fluid rounded me-3 shadow" height="150" width="150">
                        <div>
                            <h5 class="card-title">Dr. <?= $doctorDetails['doctor_name'] ?></h5>
                            <p class="text-muted mb-0"><?= !empty($doctorDetails['specialty']) ? htmlspecialchars($doctorDetails['specialty']) : 'Not specified' ?></p>
                            <div class="d-flex align-items-center">
                                <!-- <span class="text-primary me-2">★★★★★</span>
                                    <a href="#" class="text-decoration-none">More</a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 border-2 p-3">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-overview-tab" data-bs-toggle="tab" data-bs-target="#nav-overview" type="button" role="tab" aria-controls="nav-overview" aria-selected="true">Overview</button>
                        <!-- <button class="nav-link" id="nav-opinions-tab" data-bs-toggle="tab" data-bs-target="#nav-opinions" type="button" role="tab" aria-controls="nav-opinions" aria-selected="false">Opinions</button> -->
                        <!-- <button class="nav-link" id="nav-experience-tab" data-bs-toggle="tab" data-bs-target="#nav-experience" type="button" role="tab" aria-controls="nav-Experience" aria-selected="false">Experience</button> -->
                    </div>
                </nav>
                <div class="card-body px-0 px-lg-3">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
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
                                    <div class="mb-2 fw-light">
                                        <i class='bx bxs-envelope me-2 text-green'></i>
                                        <span><?= !empty($doctorDetails['email']) ? htmlspecialchars($doctorDetails['email']) : 'Not provided' ?></span>
                                    </div>
                                    <div class="mb-2 fw-light">
                                        <i class='bx bxs-phone me-2 text-green'></i>
                                        <span><?= !empty($doctorDetails['contact']) ? htmlspecialchars($doctorDetails['contact']) : 'Not provided' ?></span>
                                    </div>
                                </div>

                                <div class="card px-4 py-2 bg-light shadow-lg mb-3">
                                    <h6 class="text-primary">Professional Biography:</h6>
                                    <p class="fw-light fs-6">
                                        <?= !empty($doctorDetails['bio']) ? nl2br(htmlspecialchars($doctorDetails['bio'])) : 'No biography available' ?>
                                    </p>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="./file_upload" class="btn btn-outline-primary hover-light me-2">
                                        Send File
                                    </a>
                                    <a href="./appointment.php?doctor_id=<?= $doctor_id ?>" class="btn btn-primary text-light me-2">
                                        Book Appointment
                                    </a>
                                    <a href="./chat_user?account_id=<?= $doctor_id ?>" class="btn btn-success text-light">
                                        Chat
                                    </a>
                                </div>

                                <!-- <div class="tab-pane fade" id="nav-opinions" role="tabpanel" aria-labelledby="nav-opinions-tab">
                                <h6>Patient Reviews</h6>
                                <div class="review mb-3">
                                    <div class="d-flex align-items-center">
                                        <h6 class="me-2">John Doe</h6>
                                        <span class="badge bg-success">5.0 ★</span>
                                    </div>
                                    <p>"Dr. Smith is excellent! She takes the time to explain everything and ensures I understand my treatment plan. I feel well cared for."</p>
                                </div>
                                <div class="review mb-3">
                                    <div class="d-flex align-items-center">
                                        <h6 class="me-2">Jane Roe</h6>
                                        <span class="badge bg-warning">4.5 ★</span>
                                    </div>
                                    <p>"Great experience! The doctor was very professional and addressed all my concerns. Highly recommend."</p>
                                </div>
                                <a href="#" class="text-decoration-none">See all reviews</a>
                            </div>

                                <!-- <div class="tab-pane fade" id="nav-experience" role="tabpanel" aria-labelledby="nav-experience-tab">
                                <h6>Professional Experience</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Senior Consultant, Orthopedics</strong> - ABC Hospital (2015 - Present)</li>
                                    <li><strong>Resident Doctor</strong> - XYZ Medical Center (2010 - 2015)</li>
                                </ul>

                                <h6>Education</h6>
                                <ul class="list-unstyled">
                                    <li><strong>MD, Orthopedics</strong> - Harvard Medical School</li>
                                    <li><strong>BS, Biology</strong> - University of California</li>
                                </ul>

                                <h6>Certifications</h6>
                                <ul class="list-unstyled">
                                    <li>Board Certified Orthopedic Surgeon</li>
                                    <li>Advanced Trauma Life Support (ATLS) Certified</li>
                                </ul>

                                <a href="#" class="text-decoration-none">Read more about professional background</a>
                            </div> -->
                            </div>
                        </div>
                    </div>

                    <!-- <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-2">Availability</h6>
                            <a href="./dashboard#calendar" class="mb-2">All terms</a>
                        </div>
                        <div class="d-flex flex-wrap">
                            <div class="card me-2 mb-2">
                                <div class="card-body p-2">
                                    <small>Sep 19 | Thursday</small>
                                    <div class="d-flex flex-column align-items-center">
                                        <p class="mb-0">8:30 AM</p>
                                        to
                                        <p class="mb-0">4:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card me-2 mb-2">
                                <div class="card-body p-2">
                                    <small>Sep 20 | Friday</small>
                                    <div class="d-flex flex-column align-items-center">
                                        <p class="mb-0">8:30 AM</p>
                                        to
                                        <p class="mb-0">4:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card me-2 mb-2">
                                <div class="card-body p-2">
                                    <small>Sep 21 | Saturday</small>
                                    <div class="d-flex flex-column align-items-center">
                                        <p class="mb-0">8:30 AM</p>
                                        to
                                        <p class="mb-0">4:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card me-2 mb-2">
                                <div class="card-body p-2">
                                    <small>Sep 22 | <span class="text-primary">Sunday</span></small>
                                    <div class="d-flex flex-column align-items-center">
                                        <p class="mb-0">--:-- AM</p>
                                        to
                                        <p class="mb-0">--:-- PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card me-2 mb-2">
                                <div class="card-body p-2">
                                    <small>Sep 23| Monday</small>
                                    <div class="d-flex flex-column align-items-center">
                                        <p class="mb-0">8:30 AM</p>
                                        to
                                        <p class="mb-0">4:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card me-2 mb-2">
                                <div class="card-body p-2">
                                    <small>Sep 25 | Tuesday</small>
                                    <div class="d-flex flex-column align-items-center">
                                        <p class="mb-0">8:30 AM</p>
                                        to
                                        <p class="mb-0">4:00 PM</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card me-2 mb-2">
                                <div class="card-body p-2">
                                    <small>Sep 26 | Wednesday</small>
                                    <div class="d-flex flex-column align-items-center">
                                        <p class="mb-0">8:30 AM</p>
                                        to
                                        <p class="mb-0">4:00 PM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
        </main>
    </section>

    <?php require_once('../includes/footer.php'); ?>
</body>

</html>