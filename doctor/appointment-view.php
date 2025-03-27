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
                        if ($record['appointment_status'] == "Ongoing") {
                        ?>
                            <form action="" class="row m-0 p-0" id="resultForm">
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
                                    <div class="col-12">
                                        <label for="medcon" class="form-label mb-1">Does the patient have past or present medical conditions?</label>
                                        <div class="form-check form-check-inline ms-3">
                                            <input class="form-check-input" type="radio" name="exmedcon_check" id="Yes_medcon" value="Yes" <?= (isset($_POST['exmedcon_check']) && $_POST['exmedcon_check'] == "Yes") ? "checked" : "" ?> required>
                                            <label class="form-check-label" for="Yes_medcon">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="exmedcon_check" id="No_medcon" value="No" <?= (isset($_POST['exmedcon_check']) && $_POST['exmedcon_check'] == "No") ? "checked" : "" ?>>
                                            <label class="form-check-label" for="No_medcon">No</label>
                                        </div>
                                    </div>
                                    <div class="col-12" id="medcon-container">
                                        <textarea id="medcon" name="medcon" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required></textarea>
                                        <?php
                                        if (isset($_POST['medcon']) && !validate_field($_POST['medcon'])) {
                                        ?>
                                            <p class="text-dark m-0 ps-2">Existing medical condition is required.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="col-12">
                                        <label for="allergy" class="form-label mb-1">Does the patient have allergies?</label>
                                        <div class="form-check form-check-inline ms-3">
                                            <input class="form-check-input" type="radio" name="allergy_check" id="Yes_allergy" value="Yes" <?= (isset($_POST['allergy_check']) && $_POST['allergy_check'] == "Yes") ? "checked" : "" ?> required>
                                            <label class="form-check-label" for="Yes_allergy">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="allergy_check" id="No_allergy" value="No" <?= (isset($_POST['allergy_check']) && $_POST['allergy_check'] == "No") ? "checked" : "" ?>>
                                            <label class="form-check-label" for="No_allergy">No</label>
                                        </div>
                                    </div>
                                    <div class="col-12" id="allergy-container">
                                        <textarea id="allergy" name="allergy" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required></textarea>
                                        <?php
                                        if (isset($_POST['allergy']) && !validate_field($_POST['allergy'])) {
                                        ?>
                                            <p class="text-dark m-0 ps-2">Allergy is required.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="col-12">
                                        <label for="medication" class="form-label mb-1">Is the patient taking any medications?</label>
                                        <div class="form-check form-check-inline ms-3">
                                            <input class="form-check-input" type="radio" name="medication_check" id="Yes_medication" value="Yes" <?= (isset($_POST['medication_check']) && $_POST['medication_check'] == "Yes") ? "checked" : "" ?> required>
                                            <label class="form-check-label" for="Yes_medication">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="medication_check" id="No_medication" value="No" <?= (isset($_POST['medication_check']) && $_POST['medication_check'] == "No") ? "checked" : "" ?>>
                                            <label class="form-check-label" for="No_medication">No</label>
                                        </div>
                                    </div>
                                    <div class="col-12" id="medication-container">
                                        <textarea id="medication" name="medication" rows="2" cols="50" class="form-control bg-light" placeholder="If yes, please specify" required></textarea>
                                        <?php
                                        if (isset($_POST['medication']) && !validate_field($_POST['medication'])) {
                                        ?>
                                            <p class="text-dark m-0 ps-2">Medication is required.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p class="m-0 p-0 fs-6 text-secondary mb-2">Objective Information</p>
                                <div class="col-12 mb-2">
                                    <label for="observation" class="form-label mb-1">Doctor's Observation:</label>
                                    <textarea id="observation" name="observation" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['observation']) && !validate_field($_POST['observation'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Observation is required.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <p class="m-0 p-0 fs-6 text-secondary mb-2">Assessment and Plan</p>
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
                                    <div class="col-12" id="prescription-container">
                                        <textarea id="prescription" name="prescription" rows="2" cols="50" class="form-control bg-light" placeholder="Include prescription here" required></textarea>
                                        <?php
                                        if (isset($_POST['prescription']) && !validate_field($_POST['prescription'])) {
                                        ?>
                                            <p class="text-dark m-0 ps-2">Prescription is required.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="comment" class="form-label">Additional Comment:</label>
                                    <textarea id="comment" name="comment" rows="2" cols="50" class="form-control bg-light" placeholder=""></textarea>
                                </div>
                            </form>
                    </div>
                <?php
                        } else if ($record['appointment_status'] == "Completed") {
                ?>
                    <div class="col-12 mb-3">
                        <form action="" class="row" id="resultForm">
                            <div class="col-12 mb-3">
                                <label for="result" class="form-label">Consultation Result:</label>
                                <textarea id="result" name="result" rows="2" cols="50" class="form-control bg-light" required readonly><?= $record['result'] ?></textarea>
                            </div>
                            <?php
                            if (isset($record['diagnosis'])) {
                            ?>
                                <div class="col-12 mb-3">
                                    <label for="result" class="form-label">Medical Condition/s:</label>
                                    <textarea id="result" name="result" rows="2" cols="50" class="form-control bg-light" required readonly><?= $record['diagnosis'] ?></textarea>
                                </div>
                            <?php
                            }
                            ?>

                            <div class="col-12">
                                <label for="comment" class="form-label">Note:</label>
                                <textarea id="comment" name="comment" rows="7" cols="50" class="form-control bg-light" readonly><?= $record['comment'] ?></textarea>
                            </div>
                        </form>
                    </div>
                <?php
                        }
                ?>
                <div class="col-12 d-flex justify-content-center mb-3 ">
                    <?php
                    if ($record['appointment_status'] == "Incoming") {

                        $appointment_datetime = date('Y-m-d', strtotime($record['appointment_date']));
                        $current_datetime = date('Y-m-d');

                        $disable_button = ($appointment_datetime != $current_datetime) ? 'disabled' : '';
                    ?>

                        <button class="btn btn-success text-white mb-3" id="start" onclick="start_meeting()" <?= $disable_button ?>>
                            <i class='bx bx-video me-2 align-middle fs-5'></i>
                            Start Appointment
                        </button>
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
                        <!-- <a href="" class="btn btn-danger text-white mb-3">
                                <i class='bx bxs-edit align-middle fs-5 me-1'></i>
                                New Appointment
                            </a> -->
                    <?php
                    }
                    ?>
                </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>

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

    function join_meeting(url) {
        window.open(url, '_blank', 'width=800,height=600,top=100,left=100,toolbar=no,menubar=no,scrollbars=yes,resizable=yes');
    }

    function end_meeting() {
        var complaintInput = $('#complaint');
        if (!complaintInput.val().trim()) {
            complaintInput[0].setCustomValidity("Chief complaint is required. Please enter the patient's main health concern."); // Set custom validation message
            complaintInput[0].reportValidity(); // Show validation popup
            return; // Stop execution
        } else {
            complaintInput[0].setCustomValidity(""); // Reset validation if valid
        }

        var exmedconCheck = $('input[name="exmedcon_check"]:checked');
        if (exmedconCheck.length === 0) { // If no option is selected
            $('input[name="exmedcon_check"]').get(0).reportValidity();
            return;
        }
        var medconInput = $('#medcon');
        if (exmedconCheck.val() === "Yes") {
            if (!medconInput.val().trim()) {
                medconInput[0].setCustomValidity("Please specify the past or existing medical condition."); // Set custom validation message
                medconInput[0].reportValidity(); // Show validation popup
                return; // Stop execution
            } else {
                medconInput[0].setCustomValidity(""); // Reset validation if valid
            }
        }

        var allergyCheck = $('input[name="allergy_check"]:checked');
        if (allergyCheck.length === 0) { // If no option is selected
            $('input[name="allergy_check"]').get(0).reportValidity();
            return;
        }
        var allergyInput = $('#allergy');
        if (allergyCheck.val() === "Yes") {
            if (!allergyInput.val().trim()) {
                allergyInput[0].setCustomValidity("Please specify the allergies.");
                allergyInput[0].reportValidity(); // Show validation popup
                return; // Stop execution
            } else {
                allergyInput[0].setCustomValidity(""); // Reset validation if valid
            }
        }

        var medicationCheck = $('input[name="medication_check"]:checked');
        if (medicationCheck.length === 0) { // If no option is selected
            $('input[name="medication_check"]').get(0).reportValidity();
            return;
        }
        var medicationInput = $('#medication');
        if (medicationCheck.val() === "Yes") {
            if (!medicationInput.val().trim()) {
                medicationInput[0].setCustomValidity("Please specify the medications.");
                medicationInput[0].reportValidity(); // Show validation popup
                return; // Stop execution
            } else {
                medicationInput[0].setCustomValidity(""); // Reset validation if valid
            }
        }

        var observationInput = $('#observation');
        if (!observationInput.val().trim()) {
            observationInput[0].setCustomValidity("Doctor's observation is required. Please provide your observation."); // Set custom validation message
            observationInput[0].reportValidity(); // Show validation popup
            return; // Stop execution
        } else {
            observationInput[0].setCustomValidity(""); // Reset validation if valid
        }

        var medconCheck = $('input[name="medcon_check"]:checked'); // Get the checked radio
        if (medconCheck.length === 0) { // If no option is selected
            $('input[name="medcon_check"]').get(0).reportValidity();
            return;
        }
        var diagnosisSelect = $('#diagnosis');
        if (medconCheck.val() === "Yes") {
            if (diagnosisSelect.val() === null || diagnosisSelect.val().length === 0) {
                diagnosisSelect.get(0).setCustomValidity("Please enter at least one medical condition."); // Set custom validation message
                diagnosisSelect.get(0).reportValidity(); // Show validation popup
                return;
            } else {
                diagnosisSelect.get(0).setCustomValidity(""); // Reset validation if valid
            }
        }

        var assessmentInput = $('#assessment');
        if (!assessmentInput.val().trim()) {
            assessmentInput[0].setCustomValidity("Consultation Assessment is required. Please provide your assessment."); // Set custom validation message
            assessmentInput[0].reportValidity(); // Show validation popup
            return; // Stop execution
        } else {
            assessmentInput[0].setCustomValidity(""); // Reset validation if valid
        }

        var planCheck = $('input[name="plan_check"]:checked');
        if (planCheck.length === 0) { // If no option is selected
            $('input[name="plan_check"]').get(0).reportValidity();
            return;
        }
        var planInput = $('#plan');
        if (planCheck.val() === "Yes") {
            if (!planInput.val().trim()) {
                planInput[0].setCustomValidity("Please provide your plan and recommendation.");
                planInput[0].reportValidity(); // Show validation popup
                return; // Stop execution
            } else {
                planInput[0].setCustomValidity(""); // Reset validation if valid
            }
        }

        var prescriptionCheck = $('input[name="prescription_check"]:checked');
        if (prescriptionCheck.length === 0) { // If no option is selected
            $('input[name="prescription_check"]').get(0).reportValidity();
            return;
        }
        var prescriptionInput = $('#prescription');
        if (prescriptionCheck.val() === "Yes") {
            if (!prescriptionInput.val().trim()) {
                prescriptionInput[0].setCustomValidity("Please provide your prescription.");
                prescriptionInput[0].reportValidity(); // Show validation popup
                return; // Stop execution
            } else {
                prescriptionInput[0].setCustomValidity(""); // Reset validation if valid
            }
        }

        $.ajax({
            url: '../handlers/doctor.update_appointment.php',
            type: 'POST',
            data: {
                end: true,
                complaint: complaintInput.val().trim(),
                exmedconCheck: exmedconCheck.val(),
                medcon: medconInput.val().trim(),
                allergyCheck: allergyCheck.val(),
                allergy: allergyInput.val().trim(),
                medicationCheck: medicationCheck.val(),
                medication: medicationInput.val().trim(),
                observation: observationInput.val().trim(),
                medcon_check: medconCheck.val(),
                diagnosis: diagnosisSelect.val(),
                assessment: assessmentInput.val().trim(),
                planCheck: planCheck.val(),
                plan: planInput.val().trim(),
                prescriptionCheck: prescriptionCheck.val(),
                prescription: prescriptionInput.val().trim(),
                comment: $('#comment').val(),
                appointment_id: '<?= $_GET['appointment_id'] ?>',
            },
            success: function(response) {
                if (response.trim() === 'success') { // Trim to avoid whitespace issues
                    message_notifcation('end');
                    add_new_medcon(diagnosisSelect.val());
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

        var exmedcon_input = document.getElementById("medcon");
        if (exmedcon_input) {

            const exmedconCheck = document.getElementsByName("exmedcon_check");
            const medconContainer = document.getElementById("medcon-container");

            medconContainer.style.display = "none";

            function toggleMedconContainer() {
                if (document.getElementById("Yes_medcon").checked) {
                    medconContainer.style.display = "block"; // Show if Yes is checked
                } else {
                    medconContainer.style.display = "none"; // Hide if No is checked
                }
            }

            // Add event listeners to both radio buttons
            exmedconCheck.forEach(radio => {
                radio.addEventListener("change", toggleMedconContainer);
            });
        }

        var allergy_input = document.getElementById("allergy");
        if (allergy_input) {

            const allergyCheck = document.getElementsByName("allergy_check");
            const allergyContainer = document.getElementById("allergy-container");

            allergyContainer.style.display = "none";

            function toggleAllergyContainer() {
                if (document.getElementById("Yes_allergy").checked) {
                    allergyContainer.style.display = "block"; // Show if Yes is checked
                } else {
                    allergyContainer.style.display = "none"; // Hide if No is checked
                }
            }

            // Add event listeners to both radio buttons
            allergyCheck.forEach(radio => {
                radio.addEventListener("change", toggleAllergyContainer);
            });
        }

        var medication_input = document.getElementById("medication");
        if (medication_input) {

            const medicationCheck = document.getElementsByName("medication_check");
            const medicationContainer = document.getElementById("medication-container");

            medicationContainer.style.display = "none";

            function toggleMedicationContainer() {
                if (document.getElementById("Yes_medication").checked) {
                    medicationContainer.style.display = "block"; // Show if Yes is checked
                } else {
                    medicationContainer.style.display = "none"; // Hide if No is checked
                }
            }

            // Add event listeners to both radio buttons
            medicationCheck.forEach(radio => {
                radio.addEventListener("change", toggleMedicationContainer);
            });
        }

        var plan_input = document.getElementById("plan");
        if (plan_input) {

            const planCheck = document.getElementsByName("plan_check");
            const planContainer = document.getElementById("plan-container");

            planContainer.style.display = "none";

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