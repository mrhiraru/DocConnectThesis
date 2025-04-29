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
                            <section id="appointment" class="col-12 col-md-8 page-container padding-medium">
                                <div class="col-md-12 d-flex justify-content-between align-items-center">
                                    <a href="./profile_appointment" class="btn btn-outline-secondary hover-light d-fex align-items-center mb-3">
                                        <i class='bx bx-chevron-left'></i>
                                        Back
                                    </a>
                                </div>
                                <div id="" class="border border-dark-subtle shadow-sm rounded-2 p-2 p-md-3 m-0 mb-4 mb-md-0">
                                    <div class="row d-flex justify-content-between align-items-center">
                                        <div class="col-6 text-start">
                                            <p class="form-label text-black-50 fw-bold fs-5 m-0"> Appointment Form</p>
                                        </div>
                                        <!-- <div class="col-6 text-end">
                        <button type="button" class="btn btn-sm btn-primary text-light" data-bs-toggle="modal" data-bs-target="#patientDetails">
                            View Patient Details
                        </button>
                    </div> -->
                                    </div>

                                    <hr>
                                    <div class="col-12">
                                        <div class="d-flex flex-row flex-wrap justify-content-start mb-3">
                                            <label for="doctor_id" class="form-label text-black-50">Select Doctor</label>
                                            <select name="doctor_id" id="doctor_id" class="col-12">
                                                <?php
                                                include_once('../handlers/appointment.get_doctors.php');
                                                ?>
                                            </select>
                                        </div>
                                        <div class="row align-items-center border p-3 mx-2 rounded bg-light" id="doctor_info">
                                            <p class='text-center text-muted m-0 p-0'>No doctor selected.</p>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="mb-3">
                                        <label for="purpose" class="form-label text-black-50">Purpose of Appointment</label>
                                        <select id="purpose" name="purpose" class="form-select bg-light border border-outline-dark text-secondary">
                                            <option value=""></option>
                                            <option value="Check-up" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Check-up") ? 'selected' : '' ?>>Check-up</option>
                                            <option value="Follow-up Check-up" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Follow-up Check-up") ? 'selected' : '' ?>>Follow-up Check-up</option>
                                            <option value="Medical Advice" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Medical Advice") ? 'selected' : '' ?>>Medical Advice</option>
                                            <option value="Mental Health Consultation" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Mental Health Consultation") ? 'selected' : '' ?>>Mental Health Consultation</option>
                                            <option value="Dietary and Nutrition Advice" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Dietary and Nutrition Advice") ? 'selected' : '' ?>>Dietary and Nutrition Advice</option>
                                        </select>
                                        <?php
                                        if (isset($_POST['purpose']) && !validate_field($_POST['purpose'])) {
                                        ?>
                                            <p class="text-danger small mt-1">Select a purpose for the appointment.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="reason" class="form-label text-black-50">Reason</label>
                                        <textarea class="form-control bg-light fs-6 mb-3 border  border-outline-dark text-secondary" rows="3" id="reason" name="reason" placeholder="Include your reason for appointment."><?= (isset($_POST['reason'])) ? $_POST['reason'] : '' ?></textarea>
                                        <?php
                                        if (isset($_POST['reason']) && !validate_field($_POST['reason'])) {
                                        ?>
                                            <p class="text-danger small mt-1">Reason for appointment is required.</p>
                                        <?php
                                        }
                                        ?>
                                    </div>

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

                                    <hr class="my-2">
                                    <div class="w-100 d-flex justify-content-end">
                                        <button id="request" name="request" type="submit" class="col-12 col-md-6 col-lg-4 btn btn-primary text-light mt-2" disabled>Request Appointment</button>
                                    </div>
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