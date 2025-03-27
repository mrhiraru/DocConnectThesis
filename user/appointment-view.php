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

$appointment_class = new Appointment();
$record = $appointment_class->get_appointment_details_user($_GET['appointment_id']);

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
                    $appointment = 'active';
                    $aAppointment = 'page';
                    $cAppointment = 'text-dark';

                    include 'profile_nav.php';
                    ?>

                    <div class="card bg-body-tertiary mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-2 text-green">Appointment View</h5>
                            <hr>
                            <div class="p-0 m-0 row">
                                <div class="col-12 mb-3 border-bottom">
                                    <p class="m-0 p-0 fs-5 text-dark fw-semibold text-wrap">
                                        <?= date("l, M d, Y", strtotime($record['appointment_date'])) . " " . date("g:i A", strtotime($record['appointment_time'])) ?>
                                    </p>
                                    <p class="m-0 p-0 fs-6 text-secondary mb-3">Status: <span class="text-dark"><?= $record['appointment_status'] ?></span></p>
                                    <div class="col-12 d-flex justify-content-center">
                                        <?php
                                        if ($record['appointment_status'] == "Ongoing") {
                                        ?>
                                            <button class="btn btn-success text-white mb-3 me-2" onclick="join_meeting('<?= $record['appointment_link'] ?>'); return false;">
                                                <i class='bx bx-video me-2 align-middle fs-5'></i>
                                                Join Meeting
                                            </button>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row col-12 mb-3 border-bottom">
                                    <p class="m-0 p-0 fs-6 text-secondary mb-2">Patient Information</p>
                                    <div class="col-12 mb-2">
                                        <label for="name" class="form-label mb-1">Patient Name:</label>
                                        <input id="name" class="form-control bg-light" value="<?= $record['patient_name'] ?>" readonly>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="birthdate" class="form-label mb-1">Birthdate:</label>
                                        <input id="birthdate" class="form-control bg-light" value="<?= date('F d, Y', strtotime($record['birthdate'])) ?>" readonly>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="gender" class="form-label mb-1">Gender:</label>
                                        <input id="gender" class="form-control bg-light" value="<?= $record['gender'] ?>" readonly>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="email" class="form-label mb-1">Email:</label>
                                        <input id="email" class="form-control bg-light" value="<?= $record['email'] ?>" readonly>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="contact" class="form-label mb-1">Contact:</label>
                                        <input id="contact" class="form-control bg-light" value="<?= $record['contact'] ?>" readonly>
                                    </div>
                                    <p class="m-0 p-0 fs-6 text-secondary mb-2">Subjective Information</p>
                                    <div class="col-12 mb-2">
                                        <label for="purpose" class="form-label mb-1">Purpose:</label>
                                        <textarea id="purpose" rows="2" cols="50" class="form-control bg-light" readonly><?= $record['purpose'] ?></textarea>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="reason" class="form-label mb-1">Reason:</label>
                                        <textarea id="reason" rows="2" cols="50" class="form-control bg-light" readonly><?= $record['reason'] ?></textarea>
                                    </div>

                                    <?php
                                    if ($record['appointment_status'] == "Completed") {
                                    ?>
                                        <div class="col-12 mb-3">
                                            <form action="" class="row" id="resultForm">
                                                <div class="col-12 mb-2">
                                                    <label for="complaint" class="form-label mb-1">Chief Complaint:</label>
                                                    <textarea id="complaint" name="complaint" rows="2" cols="50" class="form-control bg-light" required readonly><?= $record['complaint'] ?></textarea>
                                                </div>
                                                <?php
                                                if (isset($record['medcon_history'])) {
                                                ?>
                                                    <div class="col-12 mb-2">
                                                        <label for="medcon" class="form-label mb-1">Medical History:</label>
                                                        <textarea id="medcon" name="medcon" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $record['medcon_history'] ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                if (isset($record['allergy'])) {
                                                ?>
                                                    <div class="col-12 mb-2">
                                                        <label for="allergy" class="form-label mb-1">Allergies:</label>
                                                        <textarea id="allergy" name="allergy" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $record['allergy'] ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                if (isset($record['medication'])) {
                                                ?>
                                                    <div class="col-12 mb-2">
                                                        <label for="medication" class="form-label mb-1">Medication:</label>
                                                        <textarea id="medication" name="medication" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $record['medication'] ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <p class="m-0 p-0 fs-6 text-secondary mb-2">Objective Information</p>
                                                <div class="col-12 mb-2">
                                                    <label for="observation" class="form-label mb-1">Doctor's Observation:</label>
                                                    <textarea id="observation" name="observation" rows="2" cols="50" class="form-control bg-light" required readonly><?= $record['observation'] ?></textarea>
                                                </div>
                                                <p class="m-0 p-0 fs-6 text-secondary mb-2">Assessment and Plan</p>
                                                <?php
                                                if (isset($record['diagnosis'])) {
                                                ?>
                                                    <div class="col-12 mb-2">
                                                        <label for="diagnosis_text" class="form-label mb-1">Medical Condition:</label>
                                                        <textarea id="diagnosis_text" name="diagnosis_text" rows="2" cols="50" class="form-control bg-light" required readonly><?= $record['diagnosis'] ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <div class="col-12 mb-3">
                                                    <label for="assessment" class="form-label">Consultation Assessment:</label>
                                                    <textarea id="assessment" name="assessment" rows="2" cols="50" class="form-control bg-light" required readonly><?= $record['assessment'] ?></textarea>
                                                </div>
                                                <?php
                                                if (isset($record['plan'])) {
                                                ?>
                                                    <div class="col-12 mb-2">
                                                        <label for="plan" class="form-label mb-1">Treatment Plan and Recommendation:</label>
                                                        <textarea id="plan" name="plan" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $record['plan'] ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                if (isset($record['prescription'])) {
                                                ?>
                                                    <div class="col-12 mb-2">
                                                        <label for="prescription" class="form-label mb-1">Prescription:</label>
                                                        <textarea id="prescription" name="prescription" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required readonly><?= $record['prescription'] ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                if (isset($record['comment'])) {
                                                ?>
                                                    <div class="col-12">
                                                        <label for="comment" class="form-label">Additional Comment:</label>
                                                        <textarea id="comment" name="comment" rows="2" cols="50" class="form-control bg-light" readonly><?= $record['comment'] ?></textarea>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </form>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="col-12 d-flex justify-content-center">
                                        <?php
                                        if ($record['appointment_status'] == "Completed") {
                                        ?>
                                            <button class="btn btn-danger text-white mb-3" id="download_pdf">
                                                <i class='bx bx-file align-middle fs-5'></i>
                                                Download Result
                                            </button>
                                        <?php
                                        }
                                        ?>
                                    </div>
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
    <?php
    include_once('../tools/pdfmaker.php');
    ?>
    <script>
        function join_meeting(url) {
            window.open(url, '_blank', 'width=800,height=600,top=100,left=100,toolbar=no,menubar=no,scrollbars=yes,resizable=yes');
        }
    </script>
</body>

</html>