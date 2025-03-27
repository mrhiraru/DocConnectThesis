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
                            <div class="col-12 mt-3">
                                <p class="m-0 p-0 fs-5 text-dark fw-semibold text-wrap">
                                    <?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?>
                                </p>
                                <p class="m-0 p-0 fs-6 text-secondary mb-3">Status: <span class="text-dark"><?= $item['appointment_status'] ?></span></p>
                            </div>
                            <div class="row col-12 m-0 mb-3">
                                <p class="m-0 p-0 fs-6 text-secondary mb-2">Patient Information</p>
                                <div class="col-12 mb-2">
                                    <label for="name" class="form-label mb-1">Patient Name:</label>
                                    <input id="name" class="form-control bg-light" value="<?= $item['patient_name'] ?>" readonly>
                                </div>
                                <p class="m-0 p-0 fs-6 text-secondary mb-2">Subjective Information</p>
                                <div class="col-12 mb-2">
                                    <label for="purpose" class="form-label mb-1">Purpose:</label>
                                    <textarea id="purpose" rows="2" cols="50" class="form-control bg-light" readonly><?= $item['purpose'] ?></textarea>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="reason" class="form-label mb-1">Reason:</label>
                                    <textarea id="reason" rows="2" cols="50" class="form-control bg-light" readonly><?= $item['reason'] ?></textarea>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="complaint" class="form-label mb-1">Chief Complaint:</label>
                                    <textarea id="complaint" name="complaint" rows="2" cols="50" class="form-control bg-light" required readonly><?= $item['complaint'] ?></textarea>
                                </div>
                                <?php
                                if (isset($item['medcon_history'])) {
                                ?>
                                    <div class="col-12 mb-2">
                                        <label for="medcon" class="form-label mb-1">Medical History:</label>
                                        <textarea id="medcon" name="medcon" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $item['medcon_history'] ?></textarea>
                                    </div>
                                <?php
                                }
                                if (isset($item['allergy'])) {
                                ?>
                                    <div class="col-12 mb-2">
                                        <label for="allergy" class="form-label mb-1">Allergies:</label>
                                        <textarea id="allergy" name="allergy" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $item['allergy'] ?></textarea>
                                    </div>
                                <?php
                                }
                                if (isset($item['medication'])) {
                                ?>
                                    <div class="col-12 mb-2">
                                        <label for="medication" class="form-label mb-1">Medication:</label>
                                        <textarea id="medication" name="medication" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $item['medication'] ?></textarea>
                                    </div>
                                <?php
                                }
                                ?>
                                <p class="m-0 p-0 fs-6 text-secondary mb-2">Objective Information</p>
                                <div class="col-12 mb-2">
                                    <label for="observation" class="form-label mb-1">Doctor's Observation:</label>
                                    <textarea id="observation" name="observation" rows="2" cols="50" class="form-control bg-light" required readonly><?= $item['observation'] ?></textarea>
                                </div>
                                <p class="m-0 p-0 fs-6 text-secondary mb-2">Assessment and Plan</p>
                                <?php
                                if (isset($item['diagnosis'])) {
                                ?>
                                    <div class="col-12 mb-2">
                                        <label for="diagnosis_text" class="form-label mb-1">Medical Condition:</label>
                                        <textarea id="diagnosis_text" name="diagnosis_text" rows="2" cols="50" class="form-control bg-light" required readonly><?= $item['diagnosis'] ?></textarea>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="col-12 mb-3">
                                    <label for="assessment" class="form-label">Consultation Assessment:</label>
                                    <textarea id="assessment" name="assessment" rows="2" cols="50" class="form-control bg-light" required readonly><?= $item['assessment'] ?></textarea>
                                </div>
                                <?php
                                if (isset($item['plan'])) {
                                ?>
                                    <div class="col-12 mb-2">
                                        <label for="plan" class="form-label mb-1">Treatment Plan and Recommendation:</label>
                                        <textarea id="plan" name="plan" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $item['plan'] ?></textarea>
                                    </div>
                                <?php
                                }
                                if (isset($item['prescription'])) {
                                ?>
                                    <div class="col-12 mb-2">
                                        <label for="prescription" class="form-label mb-1">Prescription:</label>
                                        <textarea id="prescription" name="prescription" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $item['prescription'] ?></textarea>
                                    </div>
                                <?php
                                }
                                if (isset($item['comment'])) {
                                ?>
                                    <div class="col-12">
                                        <label for="comment" class="form-label">Additional Comment:</label>
                                        <textarea id="comment" name="comment" rows="2" cols="50" class="form-control bg-light" readonly><?= $item['comment'] ?></textarea>
                                    </div>
                                <?php
                                }
                                ?>
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