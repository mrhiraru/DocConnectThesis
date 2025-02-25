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
                            <div class="col-12 pt-2">
                                <p class="m-0 p-0 fs-5 text-secondary mb-2">Date: <span class="text-dark"><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></span></p>
                                <p class="m-0 p-0 fs-6 text-secondary">Patient: <span class="text-dark"><?= $item['patient_name'] ?></span></p>
                                <p class="m-0 p-0 fs-6 text-secondary mb-3">Reason: <span class="text-dark"><?= $item['reason'] ?></span></p>
                                <div class="col-12 mb-3">
                                    <div class="col-12 mb-3">
                                        <label for="result" class="form-label">Result:</label>
                                        <textarea id="result" name="result" rows="2" cols="50" class="form-control bg-light" required disabled><?= $item['result'] ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="comment" class="form-label">Note:</label>
                                        <textarea id="comment" name="comment" rows="7" cols="50" class="form-control bg-light" disabled><?= $item['comment'] ?></textarea>
                                    </div>
                                </div>
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