<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/appointment.class.php');

$appointment_class = new Appointment();
$record = $appointment_class->get_appointment_details($_GET['appointment_id']);

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
$title = 'Meeting View';
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
                    <div class="col-12 mb-3 border-bottom">
                        <p class="m-0 p-0 fs-5 text-dark fw-semibold text-wrap">
                            <?= date("l, M d, Y", strtotime($record['appointment_date'])) . " " . date("g:i A", strtotime($record['appointment_time'])) ?>
                        </p>
                        <p class="m-0 p-0 fs-6 text-secondary">Patient Name: <span class="text-dark"><?= $record['patient_name'] ?></span></p>
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
                    if ($record['appointment_status'] == "Ongoing") {
                    ?>
                        <form action="" class="row m-0 p-0" id="resultForm">
                            <div class="row col-12 mb-3 border-bottom">
                                <p class="m-0 p-0 fs-5 mb-2 text-center">Clinical History</p>
                                <div class="col-6 mb-2">
                                    <label for="name" class="form-label mb-1">Patient Name:</label>
                                    <input id="name" class="form-control bg-light" value="<?= $record['patient_name'] ?>" readonly>
                                </div>
                                <div class="col-2 mb-2">
                                    <label for="birthdate" class="form-label mb-1">Age:</label>
                                    <input id="birthdate" class="form-control bg-light" value="<<?= calculateAge($record['birthdate']) ?>" readonly>
                                </div>
                                <div class="col-2 mb-2">
                                    <label for="gender" class="form-label mb-1">Sex:</label>
                                    <input id="gender" class="form-control bg-light" value="<?= $record['gender'] ?>" readonly>
                                </div>
                                <div class="col-2 mb-2">
                                    <label for="civil_status" class="form-label mb-1">Civil Status:</label>
                                    <input id="civil_status" class="form-control bg-light" value="" readonly>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="address" class="form-label mb-1">Residence:</label>
                                    <input id="address" class="form-control bg-light" value="<?= $record['address'] ?>" readonly>
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
                                    <textarea id="informant" name="informant" rows="1" cols="50" class="form-control bg-light" readonly required><?= $_SESSION['fullname'] ?></textarea>
                                    <?php
                                    if (isset($_POST['informant']) && !validate_field($_POST['informant'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Informant is required.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="complaint" class="form-label mb-1">Cheif Complaint:</label>
                                    <textarea id="complaint" name="complaint" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['complaint']) && !validate_field($_POST['complaint'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Cheif Complaint is required.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="his_illness" class="form-label mb-1">History of Present Illness:</label>
                                    <textarea id="his_illness" name="his_illness" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['his_illness']) && !validate_field($_POST['his_illness'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">History of Present Illness is required; indicate 'N/A' if not applicable.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="medcon" class="form-label mb-1">Past Medical or Surgical History:</label>
                                    <textarea id="medcon" name="medcon" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['medcon']) && !validate_field($_POST['medcon'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Past Medical or Surgical History is required; indicate 'N/A' if not applicable.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="ob_his" class="form-label mb-1">Obstetric History:</label>
                                    <textarea id="ob_his" name="ob_his" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['ob_his']) && !validate_field($_POST['ob_his'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Obstetric History is required; indicate 'N/A' if not applicable.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="fam_his" class="form-label mb-1">Family History:</label>
                                    <textarea id="fam_his" name="fam_his" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['fam_his']) && !validate_field($_POST['fam_his'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Family History is required; indicate 'N/A' if not applicable.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="soc_his" class="form-label mb-1">Social History:</label>
                                    <textarea id="soc_his" name="soc_his" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['soc_his']) && !validate_field($_POST['soc_his'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Social History is required; indicate 'N/A' if not applicable.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="rev_sys" class="form-label mb-1">Review of System:</label>
                                    <textarea id="rev_sys" name="rev_sys" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['rev_sys']) && !validate_field($_POST['rev_sys'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Review of System is required; indicate 'N/A' if not applicable.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="medication" class="form-label mb-1">Maintenance Medication:</label>
                                    <textarea id="medication" name="medication" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['medication']) && !validate_field($_POST['medication'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2"> Maintenance medication is required; indicate 'N/A' if not applicable.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="allergy" class="form-label mb-1">Allergies & Medical Intolerance/s:</label>
                                    <textarea id="allergy" name="allergy" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['allergy']) && !validate_field($_POST['allergy'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Allergy & Medical Intolerance is required; indicate 'N/A' if not applicable.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="immu" class="form-label mb-1">Immunization & Preventive Care Services:</label>
                                    <textarea id="immu" name="immu" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['immu']) && !validate_field($_POST['immu'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Immunization & Preventive Care Services is required; indicate 'N/A' if not applicable.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="assessment" class="form-label">Consultation Assessment:</label>
                                    <textarea id="assessment" name="assessment" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['assessment']) && !validate_field($_POST['assessment'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Assessment is required.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="col-12">
                                        Did you identify any medical conditions during the consultation?
                                        <div class="form-check form-check-inline ms-3">
                                            <input class="form-check-input" type="radio" name="medcon_check" id="Yes" value="Yes" <?= (isset($_POST['medcon_check']) && $_POST['medcon_check'] == "Yes") ? "checked" : "" ?> required>
                                            <label class="form-check-label" for="Yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="medcon_check" id="No" value="No" <?= (isset($_POST['medcon_check']) && $_POST['medcon_check'] == "No") ? "checked" : "" ?>>
                                            <label class="form-check-label" for="No">No</label>
                                        </div>
                                    </div>
                                    <div class="col-12" id="diagnosis-container">
                                        <label for="diagnosis" class="form-label mb-1">What medical condition does the patient have?</label>
                                        <select class="" name="diagnosis[]" id="diagnosis" multiple required>
                                            <?php
                                            include_once('../handlers/appointment-view.fetch_conditions.php');
                                            ?>
                                        </select>
                                        <?php
                                        if (isset($_POST['diagnosis']) && !validate_field($_POST['diagnosis'])) {
                                        ?>
                                            <p class="text-dark m-0 ps-2">Select diagnosis is required.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="col-12">
                                        <label for="plan" class="form-label mb-1">Would you like to provide a treatment plan and recommendations?</label>
                                        <div class="form-check form-check-inline ms-3">
                                            <input class="form-check-input" type="radio" name="plan_check" id="Yes_plan" value="Yes" <?= (isset($_POST['plan_check']) && $_POST['plan_check'] == "Yes") ? "checked" : "" ?> required>
                                            <label class="form-check-label" for="Yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="plan_check" id="No_plan" value="No" <?= (isset($_POST['plan_check']) && $_POST['plan_check'] == "No") ? "checked" : "" ?>>
                                            <label class="form-check-label" for="No">No</label>
                                        </div>
                                    </div>
                                    <div class="col-12" id="plan-container">
                                        <textarea id="plan" name="plan" rows="2" cols="50" class="form-control bg-light" placeholder="Include treatment plan and recommendation here" required></textarea>
                                        <?php
                                        if (isset($_POST['plan']) && !validate_field($_POST['plan'])) {
                                        ?>
                                            <p class="text-dark m-0 ps-2">Treatment Plan or Recommendation is required.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="col-12">
                                        <label for="prescription" class="form-label mb-1">Would you like to provide a prescription?</label>
                                        <div class="form-check form-check-inline ms-3">
                                            <input class="form-check-input" type="radio" name="prescription_check" id="Yes_prescription" value="Yes" <?= (isset($_POST['prescription_check']) && $_POST['prescription_check'] == "Yes") ? "checked" : "" ?> required>
                                            <label class="form-check-label" for="Yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="prescription_check" id="No_prescription" value="No" <?= (isset($_POST['prescription_check']) && $_POST['prescription_check'] == "No") ? "checked" : "" ?>>
                                            <label class="form-check-label" for="No">No</label>
                                        </div>
                                    </div>
                                    <div class="col-12 input-group" id="prescription-container">

                                        <div class="input-group">
                                            <textarea class="form-control bg-light" id="prescription" name="prescription" placeholder="Prescribe medicine here" required></textarea>
                                            <textarea class="form-control bg-light" id="dosage" name="dosage" placeholder="Dosage" required></textarea>
                                            <textarea class="form-control bg-light" id="frequency" name="frequency" placeholder="Usage Frequency" required></textarea>
                                        </div>
                                        
                                        <?php
                                        if (isset($_POST['prescription']) && !validate_field($_POST['prescription'])) {
                                        ?>
                                            <p class="text-dark m-0 ps-2">Prescription is required.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </form>

                    <?php
                    } else if ($record['appointment_status'] == "Completed") {
                    ?>
                        <div class="col-12 mb-3">
                            <div class="row col-12 mb-3 border-bottom">
                                <p class="m-0 p-0 fs-5 mb-2 text-center">Clinical History</p>
                                <div class="col-6 mb-2">
                                    <label for="name" class="form-label mb-1">Patient Name:</label>
                                    <input id="name" class="form-control bg-light" value="<?= $record['patient_name'] ?>" readonly>
                                </div>
                                <div class="col-2 mb-2">
                                    <label for="birthdate" class="form-label mb-1">Age:</label>
                                    <input id="birthdate" class="form-control bg-light" value="<?= calculateAge($record['birthdate']) ?>" readonly>
                                </div>
                                <div class="col-2 mb-2">
                                    <label for="gender" class="form-label mb-1">Sex:</label>
                                    <input id="gender" class="form-control bg-light" value="<?= $record['gender'] ?>" readonly>
                                </div>
                                <div class="col-2 mb-2">
                                    <label for="civil_status" class="form-label mb-1">Civil Status:</label>
                                    <input id="civil_status" class="form-control bg-light" value="<?= $record['civil_status'] ?>" readonly>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="address" class="form-label mb-1">Residence:</label>
                                    <input id="address" class="form-control bg-light" value="<?= $record['address'] ?>" readonly>
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="religion" class="form-label mb-1">Religion:</label>
                                    <input id="religion" class="form-control bg-light" value="<?= $record['religion'] ?>" readonly>
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
                    <div class="col-12 d-flex justify-content-center mb-3 ">
                        <?php
                        if ($record['appointment_status'] == "Incoming") {

                            $appointment_datetime = date('Y-m-d', strtotime($record['appointment_date']));
                            date_default_timezone_set('Asia/Manila');
                            $current_datetime = date('Y-m-d');

                            $disable_button = ($appointment_datetime != $current_datetime) ? 'disabled' : '';
                        ?>

                            <button class="btn btn-success text-white mb-3 me-2" id="start" onclick="start_meeting()" <?= $disable_button ?>>
                                <i class='bx bx-video me-2 align-middle fs-5'></i>
                                Start Appointment
                            </button>
                            <div class="dropdown">
                                <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" onclick="force_start()">Force Start</a></li>
                                </ul>
                            </div>
                        <?php
                        } else if ($record['appointment_status'] == "Ongoing") {
                        ?>
                            <button class="btn btn-danger text-white mb-3" onclick="end_meeting()">
                                <i class='bx bx-check-square align-middle fs-5'></i>
                                Complete Appointment
                            </button>
                        <?php
                        } else if ($record['appointment_status'] == "Completed") {
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
            </main>
        </div>
    </div>

    <div class="modal fade" id="forcestartModal" tabindex="-1" aria-labelledby="forcestartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forcestartModalLabel">Are you sure you want to start the appointment right now?</h5>

                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-12 text-center">
                            <h6 class="modal-title col-12 my-2 text-primary">Please inform your patient before starting.</h6>
                            <button type="button" class="btn btn-secondary text-light" data-bs-dismiss="modal" id="no" aria-label="Close">Cancel</button>
                            <button type="button" class="btn btn-primary text-light" data-bs-dismiss="modal" id="yes" aria-label="Close" onclick="start_meeting()">I have already informed my patient, Start now.</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
include_once('../tools/pdfmaker.php');
?>
<script>
    function start_meeting() {
        $.ajax({
            url: '../handlers/doctor.update_appointment.php',
            type: 'POST',
            data: {
                start: true,
                appointment_id: '<?= $_GET['appointment_id'] ?>',
            },
            success: function(response) {
                if (response.trim() === 'success') {
                    message_notifcation('start');
                    location.reload();
                } else {
                    console.error('Error:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error starting meeting:', error);
            }
        });
    }

    function force_start() {
        var modalElement = document.getElementById("forcestartModal");
        if (modalElement) {
            var myModal = new bootstrap.Modal(modalElement, {});
            myModal.show();
        }
    }

    function join_meeting(url) {
        window.open(url, '_blank', 'width=800,height=600,top=100,left=100,toolbar=no,menubar=no,scrollbars=yes,resizable=yes');
    }

    function end_meeting() {
        // Get all required fields
        const requiredFields = [{
                id: 'complaint',
                message: "Chief complaint is required. Please enter the patient's main health concern."
            },
            {
                id: 'his_illness',
                message: "History of Present Illness is required; indicate 'N/A' if not applicable."
            },
            {
                id: 'medcon',
                message: "Past Medical or Surgical History is required; indicate 'N/A' if not applicable."
            },
            {
                id: 'ob_his',
                message: "Obstetric History is required; indicate 'N/A' if not applicable."
            },
            {
                id: 'fam_his',
                message: "Family History is required; indicate 'N/A' if not applicable."
            },
            {
                id: 'soc_his',
                message: "Social History is required; indicate 'N/A' if not applicable."
            },
            {
                id: 'rev_sys',
                message: "Review of System is required; indicate 'N/A' if not applicable."
            },
            {
                id: 'medication',
                message: "Maintenance Medication is required; indicate 'N/A' if not applicable."
            },
            {
                id: 'allergy',
                message: "Allergy & Medical Intolerance is required; indicate 'N/A' if not applicable."
            },
            {
                id: 'immu',
                message: "Immunization & Preventive Care Services is required; indicate 'N/A' if not applicable."
            },
            {
                id: 'assessment',
                message: "Consultation Assessment is required. Please provide your assessment."
            }
        ];

        // Validate all required fields
        for (const field of requiredFields) {
            const element = document.getElementById(field.id);
            if (!element.value.trim()) {
                element.setCustomValidity(field.message);
                element.reportValidity();
                // Focus on the first invalid field
                element.focus();
                return false;
            }
            // Reset validity if field is valid
            element.setCustomValidity("");
        }

        // Validate radio button groups
        // Validate radio button groups
        const radioGroups = [{
                name: 'medcon_check',
                message: 'Please indicate if you identified any medical conditions',
                yesId: 'Yes',
                conditionalField: 'diagnosis',
                conditionalMessage: "Please enter at least one medical condition."
            },
            {
                name: 'plan_check',
                message: 'Please indicate if you want to provide a treatment plan',
                yesId: 'Yes_plan',
                conditionalField: 'plan',
                conditionalMessage: "Please provide your plan and recommendation."
            },
            {
                name: 'prescription_check',
                message: 'Please indicate if you want to provide a prescription',
                yesId: 'Yes_prescription',
                conditionalField: 'prescription',
                conditionalMessage: "Please provide your prescription."
            }
        ];

        for (const group of radioGroups) {
            const checked = document.querySelector(`input[name="${group.name}"]:checked`);
            if (!checked) {
                // Show validation on the first radio button of the group
                const firstRadio = document.querySelector(`input[name="${group.name}"]`);
                firstRadio.setCustomValidity(group.message);
                firstRadio.reportValidity();
                firstRadio.focus();
                return false;
            }

            // Reset validity if selection is made
            document.querySelectorAll(`input[name="${group.name}"]`).forEach(radio => {
                radio.setCustomValidity("");
            });

            // If "Yes" is checked, validate the conditional field immediately
            if (document.getElementById(group.yesId).checked) {
                const conditionalElement = document.getElementById(group.conditionalField);
                if (!conditionalElement.value || (group.conditionalField === 'diagnosis' && conditionalElement.value.length === 0)) {
                    conditionalElement.setCustomValidity(group.conditionalMessage);
                    conditionalElement.reportValidity();
                    conditionalElement.focus();
                    return false;
                }
                conditionalElement.setCustomValidity("");
            }
        }

        // If all validations pass, proceed with AJAX call

        const formData = {
            end: true,
            complaint: $('#complaint').val().trim(),
            his_illness: $('#his_illness').val().trim(),
            medcon: $('#medcon').val().trim(),
            ob_his: $('#ob_his').val().trim(),
            fam_his: $('#fam_his').val().trim(),
            soc_his: $('#soc_his').val().trim(),
            rev_sys: $('#rev_sys').val().trim(),
            medication: $('#medication').val().trim(),
            allergy: $('#allergy').val().trim(),
            immu: $('#immu').val().trim(),
            medcon_check: $('input[name="medcon_check"]:checked').val(),
            diagnosis: $('#diagnosis').val(),
            assessment: $('#assessment').val().trim(),
            plan_check: $('input[name="plan_check"]:checked').val(),
            plan: $('#plan').val().trim(),
            prescription_check: $('input[name="prescription_check"]:checked').val(),
            prescription: $('#prescription').val().trim(),
            appointment_id: '<?= $_GET['appointment_id'] ?>',
            appointment_status: 'Completed'
        };

        // Add conditional fields based on radio button selections
        if (formData.medcon_check === 'No') {
            formData.diagnosis = null;
        }
        if (formData.plan_check === 'No') {
            formData.plan = null;
        }
        if (formData.prescription_check === 'No') {
            formData.prescription = null;
        }

        $.ajax({
            url: '../handlers/doctor.update_appointment.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.trim() === 'success') {
                    message_notifcation('end');
                    add_new_medcon($('#diagnosis').val());
                    location.reload();
                } else {
                    console.error('Error:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error completing meeting:', error);
            }
        });
    }

    function message_notifcation(action) {
        $.ajax({
            url: '../handlers/chat.send_message.php',
            type: 'POST',
            data: {
                appointment_id: '<?= $record["appointment_id"] ?>',
                notif: 'true',
                action: action
            },
            success: function(response) {
                console.log('Message notifcation sent.');
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
            }
        })
    }

    function add_new_medcon(medcon) {
        $.ajax({
            url: '../handlers/medcon.add_new_medcon.php',
            type: 'POST',
            data: {
                add: 'true',
                medcon: medcon
            },
            success: function(response) {
                console.log('New medical condition added.');
            },
            error: function(xhr, status, error) {
                console.error('Error adding medical condition:', error);
            }
        })
    }

    function show_medical_conditions() {
        $.ajax({
            url: '../handlers/appointment-view.fetch_conditions.php',
            success: function(response) {
                $("#medcon").html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error request:', error);
            }
        })
    }

    document.addEventListener("DOMContentLoaded", function() {

        var diagnosis_input = document.getElementById("diagnosis");
        if (diagnosis_input) {
            new TomSelect("#diagnosis", {
                maxItems: null, // Allows unlimited selections, set a number if you want to limit it
                persist: false,
                create: true, // Set to true if you want to allow custom inputs
                plugins: ['remove_button'] // Adds a remove button for each selected item
            });

            const medconCheck = document.getElementsByName("medcon_check");
            const diagnosisContainer = document.getElementById("diagnosis-container");

            diagnosisContainer.style.display = "none";

            // Function to toggle visibility
            function toggleDiagnosisContainer() {
                if (document.getElementById("Yes").checked) {
                    diagnosisContainer.style.display = "block"; // Show if Yes is checked
                } else {
                    diagnosisContainer.style.display = "none"; // Hide if No is checked
                }
            }

            // Add event listeners to both radio buttons
            medconCheck.forEach(radio => {
                radio.addEventListener("change", toggleDiagnosisContainer);
            });
        }

        var plan_input = document.getElementById("plan");
        if (plan_input) {

            const planCheck = document.getElementsByName("plan_check");
            const planContainer = document.getElementById("plan-container");

            planContainer.style.display = "none";

            // Function to toggle visibility
            function togglePlanContainer() {
                if (document.getElementById("Yes_plan").checked) {
                    planContainer.style.display = "block"; // Show if Yes is checked
                } else {
                    planContainer.style.display = "none"; // Hide if No is checked
                }
            }

            // Add event listeners to both radio buttons
            planCheck.forEach(radio => {
                radio.addEventListener("change", togglePlanContainer);
            });
        }

        var prescription_input = document.getElementById("prescription");
        if (prescription_input) {
            const prescriptionCheck = document.getElementsByName("prescription_check");
            const prescriptionContainer = document.getElementById("prescription-container");

            prescriptionContainer.style.display = "none";

            // Function to toggle visibility
            function togglePrescriptionContainer() {
                if (document.getElementById("Yes_prescription").checked) {
                    prescriptionContainer.style.display = "block"; // Show if Yes is checked
                } else {
                    prescriptionContainer.style.display = "none"; // Hide if No is checked
                }
            }

            // Add event listeners to both radio buttons
            prescriptionCheck.forEach(radio => {
                radio.addEventListener("change", togglePrescriptionContainer);
            });
        }

    });
</script>