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
                        <p class="m-0 p-0 fs-6 text-secondary">Patient: <span class="text-dark"><?= $record['patient_name'] ?></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary">Reason: <span class="text-dark"><?= $record['reason'] ?></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary mb">Status: <span class="text-dark"><?= $record['appointment_status'] ?></span></p>
                        <p class="m-0 p-0 fs-6 text-secondary mb-3">Link: <a href="<?= $record['appointment_link'] ?>" class="text-primary"><?= $record['appointment_link'] ?></a></p>
                    </div>
                    <?php
                    if ($record['appointment_status'] == "Ongoing") {
                    ?>
                        <div class="col-12 mb-3">
                            <form action="" class="row" id="resultForm">
                                <div class="col-12 mb-3">
                                    <label for="result" class="form-label">Consultation Result:</label>
                                    <textarea id="result" name="result" rows="2" cols="50" class="form-control bg-light" required></textarea>
                                    <?php
                                    if (isset($_POST['result']) && !validate_field($_POST['result'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Consultation result is required.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="col-12">
                                        Does the patient have a medical condition?
                                        <div class="form-check form-check-inline ms-3">
                                            <input class="form-check-input" type="radio" name="medcon_check" id="Yes" value="Yes" <?= (isset($_POST['medcon_check']) && $_POST['medcon_check'] == "Yes") ? "checked" : "" ?>>
                                            <label class="form-check-label" for="Yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="medcon_check" id="No" value="No" <?= (isset($_POST['medcon_check']) && $_POST['medcon_check'] == "No") ? "checked" : "" ?>>
                                            <label class="form-check-label" for="No">No</label>
                                        </div>
                                    </div>
                                    <div class="col-12" id="diagnosis-container">
                                        <label for="diagnosis" class="form-label">What medical condition does the patient have?</label>
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
                                <div class="col-12">
                                    <label for="comment" class="form-label">Note:</label>
                                    <textarea id="comment" name="comment" rows="7" cols="50" class="form-control bg-light" placeholder="Write your notes here (e.g., patient instructions, prescriptions, recommendations, and etc)."></textarea>
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

                            $appointment_datetime = $record['appointment_date'] . ' ' . $record['appointment_time'];
                            $current_datetime = date('Y-m-d H:i:s'); // Get current date and time

                            $disable_button = ($appointment_datetime != $current_datetime) ? 'disabled' : '';
                        ?>

                            <button class="btn btn-success text-white mb-3" id="start" onclick="start_meeting()" <?= $disable_button ?>>
                                <i class='bx bx-video me-2 align-middle fs-5'></i>
                                Start Appointment
                            </button>
                        <?php
                        } else if ($record['appointment_status'] == "Ongoing") {
                        ?>
                            <button class="btn btn-success text-white mb-3 me-2" onclick="join_meeting('<?= $record['appointment_link'] ?>'); return false;">
                                <i class='bx bx-video me-2 align-middle fs-5'></i>
                                Join Meeting
                            </button>
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
        var resultInput = $('#result');

        if (!resultInput.val().trim()) {
            resultInput.reportValidity(); // Show validation popup
            return; // Stop execution
        }

        var medconCheck = $('#medcon_check').get

        $.ajax({
            url: '../handlers/doctor.update_appointment.php',
            type: 'POST',
            data: {
                end: true,
                result: resultInput.val().trim(),
                comment: $('#comment').val(),
                appointment_id: '<?= $_GET['appointment_id'] ?>',
            },
            success: function(response) {
                if (response.trim() === 'success') { // Trim to avoid whitespace issues
                    message_notifcation('end');
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
    });
</script>