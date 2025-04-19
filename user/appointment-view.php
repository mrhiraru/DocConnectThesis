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

function calculateAge($birthdate)
{
    if (empty($birthdate)) return 'N/A';

    $birthDate = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birthDate);
    return $age->y;
}
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
                                    <p class="m-0 p-0 fs-6 text-secondary">Doctor Name: <span class="text-dark"><?= $record['doctor_name'] ?></span></p>
                                    <p class="m-0 p-0 fs-6 text-secondary">Status: <span class="text-dark"><?= $record['appointment_status'] ?></span></p>
                                    <p class="m-0 p-0 fs-6 text-secondary">Purpose: <span class="text-dark"><?= $record['purpose'] ?></span></p>
                                    <p class="m-0 p-0 fs-6 text-secondary mb-3">Reason: <span class="text-dark"><?= $record['reason'] ?></span></p>
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

                                <?php
                                if ($record['appointment_status'] == "Completed") {
                                ?>
                                    <div class="col-12 mb-3">
                                        <div class="row col-12 mb-3 border-bottom">
                                            <p class="m-0 p-0 fs-5 mb-2 text-center">Clinical History</p>
                                            <div class="col-6 mb-2">
                                                <label for="name" class="form-label mb-1">Patient Name:</label>
                                                <input id="name" class="form-control bg-light" value="<?= $_SESSION['fullname'] ?>" readonly>
                                            </div>
                                            <div class="col-2 mb-2">
                                                <label for="birthdate" class="form-label mb-1">Age:</label>
                                                <input id="birthdate" class="form-control bg-light" value="<?= calculateAge($_SESSION['birthdate']) ?>" readonly>
                                            </div>
                                            <div class="col-2 mb-2">
                                                <label for="gender" class="form-label mb-1">Sex:</label>
                                                <input id="gender" class="form-control bg-light" value="<?= $_SESSION['gender'] ?>" readonly>
                                            </div>
                                            <div class="col-2 mb-2">
                                                <label for="civil_status" class="form-label mb-1">Civil Status:</label>
                                                <input id="civil_status" class="form-control bg-light" value="" readonly>
                                            </div>
                                            <div class="col-4 mb-2">
                                                <label for="address" class="form-label mb-1">Residence:</label>
                                                <input id="address" class="form-control bg-light" value="<?= $_SESSION['address'] ?>" readonly>
                                            </div>
                                            <div class="col-4 mb-2">
                                                <label for="religion" class="form-label mb-1">Religion:</label>
                                                <input id="religion" class="form-control bg-light" value="" readonly>
                                            </div>
                                            <div class="col-4 mb-2">
                                                <label for="date_time" class="form-label mb-1">Date & Time of Consultation:</label>
                                                <input id="date_time" class="form-control bg-light" value="<?= date("l, M d, Y", strtotime($record['appointment_date'])) . " " . date("g:i A", strtotime($record['appointment_time'])) ?>" readonly>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="informant" class="form-label mb-1">Informant:</label>
                                                <textarea id="informant" rows="1" cols="50" class="form-control bg-light" readonly><?= $_SESSION['fullname'] ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="complaint" class="form-label mb-1">Chief Complaint:</label>
                                                <textarea id="complaint" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['complaint'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="his_illness" class="form-label mb-1">History of Present Illness:</label>
                                                <textarea id="his_illness" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['his_illness'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="medcon" class="form-label mb-1">Past Medical or Surgical History:</label>
                                                <textarea id="medcon" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['medcon_history'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="ob_his" class="form-label mb-1">Obstetric History:</label>
                                                <textarea id="ob_his" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['ob_his'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="fam_his" class="form-label mb-1">Family History:</label>
                                                <textarea id="fam_his" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['fam_his'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="soc_his" class="form-label mb-1">Social History:</label>
                                                <textarea id="soc_his" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['soc_his'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="rev_sys" class="form-label mb-1">Review of System:</label>
                                                <textarea id="rev_sys" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['rev_sys'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="medication" class="form-label mb-1">Maintenance Medication:</label>
                                                <textarea id="medication" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['medication'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="allergy" class="form-label mb-1">Allergies & Medical Intolerance/s:</label>
                                                <textarea id="allergy" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['allergy'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <label for="immu" class="form-label mb-1">Immunization & Preventive Care Services:</label>
                                                <textarea id="immu" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['immu'] ?? '') ?></textarea>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="assessment" class="form-label">Consultation Assessment:</label>
                                                <textarea id="assessment" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['assessment'] ?? '') ?></textarea>
                                            </div>

                                            <div class="col-12 mb-2">
                                                <label for="diagnosis" class="form-label mb-1">Diagnosis:</label>
                                                <textarea id="diagnosis-data" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['diagnosis'] ?? 'No diagnosis recorded') ?></textarea>
                                            </div>

                                            <?php if (!empty($record['plan'])) : ?>
                                                <div class="col-12 mb-2">
                                                    <label for="plan" class="form-label mb-1">Treatment Plan:</label>
                                                    <textarea id="plan" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['plan'] ?? '') ?></textarea>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($record['prescription'])) : ?>
                                                <div class="col-12 mb-2">
                                                    <label for="prescription" class="form-label mb-1">Prescription:</label>
                                                    <textarea id="prescription" rows="2" cols="50" class="form-control bg-light" readonly><?= htmlspecialchars($record['prescription'] ?? '') ?></textarea>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="col-12 d-flex justify-content-center">
                                    <?php
                                    if ($record['appointment_status'] == "Completed") {
                                    ?>
                                        <button class="btn btn-danger text-white mb-3 me-2" id="download_clinical_history">
                                            <i class='bx bx-file align-middle fs-5'></i>
                                            Download Clinical History
                                        </button>
                                        <button class="btn btn-danger text-white mb-3 me-2" id="download_consultation_report">
                                            <i class='bx bx-file align-middle fs-5'></i>
                                            Download Consultation Report
                                        </button>
                                        <?php
                                        if (isset($record['prescription'])) {
                                        ?>
                                            <button class="btn btn-danger text-white mb-3" id="download_prescription">
                                                <i class='bx bx-file align-middle fs-5'></i>
                                                Download Prescription
                                            </button>
                                        <?php
                                        }
                                        ?>
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
    include_once('../tools/pdfmaker_user.php');
    ?>
    <script>
        function join_meeting(url) {
            window.open(url, '_blank', 'width=800,height=600,top=100,left=100,toolbar=no,menubar=no,scrollbars=yes,resizable=yes');
        }
    </script>
</body>

</html>