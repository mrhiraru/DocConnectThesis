<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
    exit();
}
require_once('../classes/appointment.class.php');

$appointment_class = new Appointment();
$recordArray = $appointment_class->get_completed_appointment($_SESSION['doctor_id'], $_GET['account_id']);
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Patient Results';
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

                if (!empty($recordArray)) {
                    foreach ($recordArray as $item) {
                ?>
                        <div class="p-0 m-0 row border-bottom border-top">
                            <div class="col-12 mb-3 border-bottom">
                                <p class="m-0 p-0 fs-5 text-dark fw-semibold text-wrap">
                                    <?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?>
                                </p>
                                <p class="m-0 p-0 fs-6 text-secondary mb-3">Status: <span class="text-dark"><?= $item['appointment_status'] ?></span></p>
                            </div>
                        </div>
                        <div class="row col-12 mb-3 border-bottom">
                            <p class="m-0 p-0 fs-6 text-secondary mb-2">Patient Information</p>
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label mb-1">Patient Name:</label>
                                <input id="name" class="form-control bg-light" value="<?= $item['patient_name'] ?>" readonly>
                            </div>
                            <div class="col-6 mb-2">
                                <label for="birthdate" class="form-label mb-1">Birthdate:</label>
                                <input id="birthdate" class="form-control bg-light" value="<?= date('F d, Y', strtotime($item['birthdate'])) ?>" readonly>
                            </div>
                            <div class="col-6 mb-2">
                                <label for="gender" class="form-label mb-1">Gender:</label>
                                <input id="gender" class="form-control bg-light" value="<?= $item['gender'] ?>" readonly>
                            </div>
                            <div class="col-6 mb-2">
                                <label for="email" class="form-label mb-1">Email:</label>
                                <input id="email" class="form-control bg-light" value="<?= $item['email'] ?>" readonly>
                            </div>
                            <div class="col-6 mb-2">
                                <label for="contact" class="form-label mb-1">Contact:</label>
                                <input id="contact" class="form-control bg-light" value="<?= $item['contact'] ?>" readonly>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="p-0 m-0 row pt-2 border-bottom border-top">
                        <div class="col-12 pt-2">
                            <p class="m-0 p-0 fs-5 text-secondary mb-2">No result found for this patient.</p>

                        </div>
                    </div>
                <?php
                }
                ?>
            </main>
        </div>
    </div>
</body>

</html>