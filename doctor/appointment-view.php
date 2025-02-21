<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ../index.php');
    exit();
}

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
                        <p class="m-0 p-0 fs-6 text-secondary mb-3">Status: <span class="text-dark"><?= $record['appointment_status'] ?></span></p>
                    </div>
                    <?php
                    if ($record['appointment_status'] == "Ongoing") {
                    ?> <div class="col-12 mb-3">
                            <form action="" class="row" id="resultForm">
                                <div class="col-12 mb-3">
                                    <label for="result" class="form-label">Consultation Result:</label>
                                    <input type="text" class="form-control" id="result" name="result" placeholder="" required>
                                </div>
                                <div class="col-12">
                                    <label for="comment" class="form-label">Comment:</label>
                                    <textarea id="comment" name="comment" rows="4" cols="50" class="form-control"></textarea>
                                </div>
                            </form>
                        </div>
                    <?php
                    } else if ($record['appointment_status'] == "Completed") {
                    ?>
                        Show result and Comment here
                    <?php
                    }
                    ?>
                    <div class="col-12 d-flex justify-content-center border-bottom mb-3 ">
                        <?php
                        if ($record['appointment_status'] == "Incoming") {
                        ?>
                            <button class="btn btn-success text-white mb-3" onclick="start_meeting('<?= $record['appointment_link'] ?>'); return false;">
                                <i class='bx bx-video me-2 align-middle fs-5'></i>
                                Start Meeting
                            </button>
                        <?php
                        } else if ($record['appointment_status'] == "Ongoing") {
                        ?>
                            <button class="btn btn-danger text-white mb-3" onclick="end_meeting()">
                                <i class='bx bx-check-square align-middle fs-5'></i>
                                Complete Meeting
                            </button>
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
    function start_meeting(url) {
        $.ajax({
            url: '../handlers/doctor.update_appointment.php',
            type: 'POST',
            data: {
                start: true,
                appointment_id: '<?= $_GET['appointment_id'] ?>',
            },
            success: function(response) {
                if (response.trim() === 'success') { // Trim to avoid whitespace issues
                    window.open(url, '_blank', 'width=800,height=600,top=100,left=100,toolbar=no,menubar=no,scrollbars=yes,resizable=yes');
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


    function end_meeting() {
        var resultInput = $('#result').get(0);

        if (!resultInput.value.trim()) {
            resultInput.reportValidity(); // Show validation popup
            return; // Stop execution
        }

        $.ajax({
            url: '../handlers/doctor.update_appointment.php',
            type: 'POST',
            data: {
                end: true,
                result: resultInput.value.trim(),
                comment: $('#comment').val(),
                appointment_id: '<?= $_GET['appointment_id'] ?>',
            },
            success: function(response) {
                if (response.trim() === 'success') { // Trim to avoid whitespace issues

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
</script>