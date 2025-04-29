<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
}

require_once('../tools/functions.php');
require_once('../classes/appointment.class.php');
require_once('../classes/referral.class.php');
require_once('../classes/doctor.class.php');

$appointment_class = new Appointment();
$refer = new Refer();

if (isset($_POST['refer'])) {

    $refer->appointment_id = htmlentities($_GET['appointment_id']);
    $refer->reason = ucfirst(strtolower(htmlentities($_POST['reason'])));
    if (isset($_POST['doctor_id'])) {
        $refer->doctor_id = htmlentities($_POST['doctor_id']);
    } else {
        $refer->doctor_id = '';
    }
    $refer->status = 'Pending';

    if (
        validate_field($refer->appointment_id) &&
        validate_field($refer->reason) &&
        validate_field($refer->doctor_id)
    ) {
        if ($refer->new_referral()) {
            $success = 'success';
            header('location: ./appointment-view.php?account_id=' . $_GET['account_id'] . '&appointment_id=' . $_GET['appointment_id']);
        } else {
            echo 'An error occured while adding in the database.';
        }
    } else {
        $success = 'failed';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'New Appointment';
$appointment = 'active';
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
            <main class="col-md-9 ms-sm-auto col-lg-10 bg-light">
                <div class="card flex-fill my-4">
                    <div class="card-body">
                        <h2>New Appointment</h2>
                        <form id="appointmentForm" action="" method="post" class="container-fluid row g-2 p-3 d-flex justify-content-center">
                            <section id="appointment" class="col-12 col-md-8 padding-medium">
                                <input type="hidden" name="doctor_id">
                                <input type="hidden" name="purpose">
                                <input type="hidden" name="reason">
                                <input type="hidden" name="patient_id">
                                <div class="container mt-4">
                                    <div class="row g-3">
                                        <!-- Date Picker -->
                                        <div class="col-12 col-lg-6" id="date_picker_cont">
                                            <label for="appointment_date" class="form-label text-secondary fw-semibold">Select Date</label>
                                            <div class="p-2 border rounded bg-light shadow-sm">
                                                <input type="text" id="appointment_date" name="appointment_date" class="form-control border-0 text-center fs-6 mb-3 border border-dark" placeholder="SELECT DATE" required readonly>
                                            </div>
                                            <?php
                                            if (isset($_POST['appointment_date']) && !validate_field($_POST['appointment_date'])) {
                                            ?>
                                                <p class="text-danger small mt-1">Please select date of appointment.</p>
                                            <?php
                                            }
                                            ?>
                                        </div>

                                        <!-- Time Picker -->
                                        <div class="col-12 col-lg-6" id="time_picker_cont">
                                            <label for="appointment_time" class="form-label text-secondary fw-semibold">Select Time</label>
                                            <div class="p-2 pb-3 border rounded bg-light shadow-sm">
                                                <input type="text" id="appointment_time" name="appointment_time" class="form-control border-0 text-center fs-6 mb-3 border border-dark" placeholder="SELECT TIME" required readonly>
                                                <div class="row row-cols-2 g-3 p-3" id="available_time">
                                                </div>
                                            </div>

                                            <?php
                                            if (isset($_POST['appointment_time']) && !validate_field($_POST['appointment_time'])) {
                                            ?>
                                                <p class="text-danger small mt-1">Please select time of appointment.</p>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100 d-flex justify-content-end mt-3">
                                    <button id="request" name="request" type="submit" class="col-12 col-md-6 col-lg-4 btn btn-primary text-light mt-2" disabled>Request Appointment</button>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>

</html>