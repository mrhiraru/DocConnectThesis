<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 2) {
    header('location: ./index.php');
    exit();
}

require_once '../classes/doctor.class.php';

$doctor = new Doctor();
$doctorArray = $doctor->get_doctors();
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Doctor Profile';
include '../includes/head.php';
function getCurrentPage()
{
    return basename($_SERVER['PHP_SELF']);
}
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
    <?php
    require_once('./includes/admin_header.php');
    require_once('./includes/admin_sidepanel.php');
    ?>
    <section class="page-container padding-medium py-4">
        <div class="col-md-6 text-md-start">
            <button onclick="history.back()" class="btn btn-outline-secondary d-flex align-items-center mb-3 ms-3">
                <i class='bx bx-chevron-left'></i> Back
            </button>
        </div>
        <main class="mx-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        <img src="<?= isset($doctorDetails['account_image']) ? "../assets/images/" . $doctorDetails['account_image'] : "../assets/images/default_profile.png" ?>"
                             alt="Doctor Profile Image" class="img-fluid rounded shadow mb-3 me-md-3" height="150" width="150">
                        <div class="flex-grow-1">
                            <h5 class="card-title">Dr. <?= htmlspecialchars($doctorDetails['doctor_name']) ?></h5>
                            <p class="text-muted"><?= !empty($doctorDetails['specialty']) ? htmlspecialchars($doctorDetails['specialty']) : 'Not specified' ?></p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="./file_list.php?account_id=<?= $doctor_id ?>" class="btn btn-outline-primary hover-light">Send Files</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-3 mb-4">
                <h5 class="text-primary">Overview</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="bg-light p-3 rounded shadow-sm">
                            <strong>Specialty:</strong>
                            <p><?= !empty($doctorDetails['specialty']) ? htmlspecialchars($doctorDetails['specialty']) : 'Not specified' ?></p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="bg-light p-3 rounded shadow-sm">
                            <strong>Working Schedule:</strong>
                            <p>
                                <?php
                                if (!empty($doctorDetails['start_day']) && !empty($doctorDetails['end_day']) && !empty($doctorDetails['start_wt']) && !empty($doctorDetails['end_wt'])) {
                                    echo htmlspecialchars($doctorDetails['start_day']) . ' to ' . htmlspecialchars($doctorDetails['end_day']) . ", " .
                                         date('h:i A', strtotime($doctorDetails['start_wt'])) . ' - ' . date('h:i A', strtotime($doctorDetails['end_wt']));
                                } else {
                                    echo 'Not specified';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-light p-3 rounded shadow-sm mb-3">
                    <h6>Contact Info</h6>
                    <p><i class='bx bxs-envelope text-green me-2'></i><?= $doctorDetails['email'] ?? 'Not provided' ?></p>
                    <p><i class='bx bxs-phone text-green me-2'></i><?= $doctorDetails['contact'] ?? 'Not provided' ?></p>
                </div>

                <div class="bg-light p-3 rounded shadow-sm">
                    <h6>Biography</h6>
                    <p><?= !empty($doctorDetails['bio']) ? nl2br(htmlspecialchars($doctorDetails['bio'])) : 'No biography available' ?></p>
                </div>
            </div>
        </main>
    </section>

    <?php require_once('../includes/footer.php'); ?>
</body>
</html>
