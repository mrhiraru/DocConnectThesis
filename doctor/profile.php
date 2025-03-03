<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Profile';
$profile = 'active';
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
            <main class="col-md-9 ms-sm-auto col-lg-10 p-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="<?php if (isset($_SESSION['account_image'])) {
                                            echo "../assets/images/" . $_SESSION['account_image'];
                                        } else {
                                            echo "../assets/images/defualt_profile.png";
                                        } ?>" alt="Doctor's profile image" class="rounded-circle me-3" height="80" width="80">
                            <div>
                                <h5 class="card-title"><?= $_SESSION['fullname'] ?></h5>
                                <p class="text-muted mb-0"><?= $_SESSION['specialty'] ?></p>
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
                    <div class="card-body">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade active show" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
                                <div class="mb-3">
                                    <h6>Bio</h6>
                                    <p><?= $_SESSION['bio'] ?></p>
                                    <!-- <a href="#" class="text-decoration-none">Read more</a> -->
                                </div>
                                <!-- <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6>Diseases treated</h6>
                                        <ul class="list-unstyled">
                                            <li>Orthopedic consultation</li>
                                            <li>Delivery blocks</li>
                                            <li>Ultrasound injection</li>
                                        </ul>
                                        <a href="#" class="text-decoration-none">Read more</a>
                                    </div>
                                </div> -->
                                <div class="mb-3">
                                    <h6>Working Time</h6>
                                    <p class="mb-1">Start: <?= $_SESSION['start_day'] . ' - ' . date('h:i A', strtotime($_SESSION['start_wt'])) ?></p>
                                    <p>End: <?= $_SESSION['end_day'] . ' - ' . date('h:i A', strtotime($_SESSION['end_wt'])) ?></p>
                                    <!-- <a href="#" class="text-decoration-none">Read more</a> -->
                                </div>
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
                            </div> -->

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
        </div>
    </div>
</body>

</html>