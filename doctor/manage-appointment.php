<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ./index.php');
}

require_once('../tools/functions.php');
require_once('../classes/appointment.class.php');
require_once('../classes/account.class.php');

$appointment_class = new Appointment();
$record = $appointment_class->get_appointment_details($_GET['appointment_id']);

?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Appointment';
$appointment = 'active';
include '../includes/head.php';

?>

<body onload="get_date_schedule(<?= $_SESSION['doctor_id'] ?>, <?= $record['appointment_id'] ?>)">
    <?php
    require_once('../includes/header-doctor.php');
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php
            require_once('../includes/sidepanel-doctor.php');
            ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 bg-light d-flex justify-content-center">
                <div class="card my-4 col-12 col-md-10 col-lg-8">
                    <div class="card-body">
                        <h4>
                            Manage Appointment
                        </h4>
                        <form id="appointmentForm" method="post" action="">
                            <hr class="my-3 opacity-25">
                            <div class="mb-3 row">
                                <div class="col-12">
                                    <div class="row align-items-center border p-3 mx-2 rounded bg-light">
                                        <div class="d-flex justify-content-center col-12 col-md-3 mb-3 mb-md-0">
                                            <img id="account_image" src="<?php if (isset($record['account_image'])) {
                                                                                echo "../assets/images/" . $record['account_image'];
                                                                            } else {
                                                                                echo "../assets/images/default_profile.png";
                                                                            } ?>" alt="User Profile" width="125" height="125" class="rounded-circle border border-2 shadow-sm">
                                        </div>
                                        <div class="col-12 col-md-7">
                                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Name: <span class="text-black" id="patient_name"><?= $record['patient_name'] ?></span> </p>
                                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Age: <span class="text-black" id="age"><?= get_age($record['birthdate']) ?> </span> </p>
                                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Gender: <span class="text-black" id="gender"><?= $record['gender'] ?></span> </p>
                                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Birthdate: <span class="text-black" id="birthdate"><?= date('F j, Y', strtotime($record['birthdate'])) ?></span> </p>
                                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Email: <span class="text-black" id="email"><?= $record['email'] ?></span> </p>
                                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Contact: <span class="text-black" id="contact"><?= $record['contact'] ?></span> </p>
                                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Address: <span class="text-black" id="address"><?= $record['address'] ?></span> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3 opacity-25">
                            <div class="mb-3">
                                <label for="reason" class="form-label text-black-50">Reason for appointment?</label>
                                <textarea id="reason" name="reason" class="form-control bg-light border border-dark" rows="3" placeholder="Describe the reason for your appointment (e.g., symptoms, check-up, follow-up)" required readonly><?= $record['reason'] ?></textarea>
                                <?php
                                if (isset($_POST['reason']) && !validate_field($_POST['reason'])) {
                                ?>
                                    <p class="text-dark m-0 ps-2">Enter reason for appointment.</p>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <p class="">
                                    Appointment Status: <span class="<?php if ($record['appointment_status'] == 'Pending') {
                                                                            echo 'text-danger';
                                                                        } else if ($record['appointment_status'] == 'Incoming') {
                                                                            echo 'text-success';
                                                                        } else if ($record['appointment_status'] == 'Cancelled') {
                                                                            echo 'text-warning';
                                                                        } ?>"><?= $record['appointment_status'] ?></span>
                                </p>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="appointment_date" class="form-label text-black-50">Select Date</label>
                                    <input type="date" id="appointment_date" name="appointment_date" data-startday="<?= $_SESSION['start_day'] ?>" data-endday="<?= $_SESSION['end_day'] ?>" min="<?php echo date('Y-m-d'); ?>" value="<?= date('Y-m-d', strtotime($record['appointment_date'])) ?>" onchange="get_date_schedule(<?= $_SESSION['doctor_id'] ?>, <?= $record['appointment_id'] ?>)" class="form-control fs-6 px-2 py-1 bg-light border border-dark" required>
                                    <?php
                                    if (isset($_POST['appointment_date']) && !validate_field($_POST['appointment_date'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Select appointment date.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="appointment_time" class="form-label text-black-50">Select Time</label>
                                    <input type="time" id="appointment_time" name="appointment_time" step="1800" min="<?= $_SESSION['start_wt'] ?>" max="<?= $_SESSION['end_wt'] ?>" value="<?= $record['appointment_time'] ?>" onchange="get_date_schedule(<?= $_SESSION['doctor_id'] ?>, <?= $record['appointment_id'] ?>)" class="form-control fs-6 px-2 py-1 bg-light border border-dark" required>
                                    <?php
                                    if (isset($_POST['appointment_time']) && !validate_field($_POST['appointment_time'])) {
                                    ?>
                                        <p class="text-dark m-0 ps-2">Select appointment time.</p>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php //Schedule Conflict Checker
                            //$date_schedule = $appointment_class->get_appointment_schedules($_SESSION['doctor_id'], $record['appointment_time']);
                            //$conflict = $appointment_class->check_availability($_SESSION['doctor_id'], $record['appointment_date'], $record['appointment_time'], $record['appointment_id']);
                            ?>
                            <div id="schedules" class="table-responsive">



                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input border-danger" type="checkbox" name="authenticate" id="authenticate" required>
                                <label class="form-check-label text-danger text-decoration-line" for="authenticate">
                                    Click to Authenticate Google Account.
                                </label>
                            </div>
                            <hr class="my-3 opacity-25">
                            <div class="m-0 p-0 text-end">
                                <?php
                                if ($record['appointment_status'] == 'Pending') {
                                ?>
                                    <button type="button" class="btn btn-danger text-light" data-bs-toggle="modal" data-bs-target="#declineModal">Decline</button>
                                    <button type="submit" class="btn btn-success text-light" id="confirm" name="confirm">Confirm</button>
                                <?php
                                } else if ($record['appointment_status'] == 'Incoming') {
                                ?>
                                    <button type="submit" class="btn btn-danger text-light" id="cancel" name="cancel">Cancel</button>
                                    <button type="submit" class="btn btn-success text-light" id="reschedule" name="reschedule">Reschedule</button>
                                <?php
                                } else if ($record['appointment_status'] == 'Ongoing') {
                                ?>
                                    <button type="submit" class="btn btn-danger text-light" id="cancel" name="cancel">Cancel</button>
                                    <button type="submit" class="btn btn-success text-light" id="reschedule" name="reschedule">Reschedule</button>
                                <?php
                                } else if ($record['appointment_status'] == 'Completed') {
                                ?>
                                    <button type="submit" class="btn btn-success text-light" id="reschedule" name="reschedule">New Appointment</button>
                                <?php
                                } else if ($record['appointment_status'] == 'Cancelled') {
                                ?>
                                    <button type="submit" class="btn btn-success text-light" id="confirm" name="confirm">Reschedule</button>
                                <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Modal for Adding Events -->
    <div class="modal fade" id="confirmationFailed" tabindex="-1" aria-labelledby="confirmationFailedLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myModalLabel">Authentication Failed: Use your registered email.</h6>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-12 text-center">
                            <a href="./manage-appointment.php?appointment_id=<?= $_GET['appointment_id'] ?>" class="text-decoration-none text-dark">
                                <p class="m-0 text-primary fw-bold">Reauthenticate</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updatedModal" tabindex="-1" aria-labelledby="updatedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatedModalLabel">The appointment has been successfully confirmed.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-12 text-center">
                            <a href="./appointment" class="text-decoration-none text-dark">
                                <p class="m-0 text-primary fw-bold">Click to Continue.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rescheduleModalLabel">The appointment has been successfully rescheduled.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-12 text-center">
                            <a href="./appointment" class="text-decoration-none text-dark">
                                <p class="m-0 text-primary fw-bold">Click to Continue.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="declinedModal" tabindex="-1" aria-labelledby="declinedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="declinedModalLabel">The appointment has been declined.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-12 text-center">
                            <a href="./appointment" class="text-decoration-none text-dark">
                                <p class="m-0 text-primary fw-bold">Click to Continue.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="declineModalLabel">Are you sure you want to decline the appointment?</h5>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-secondary text-light" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="button" class="btn btn-primary text-light" data-bs-dismiss="modal" aria-label="Close" onclick="decline_appointment()">Decline</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Are you sure you want to cancel the appointment?</h5>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-secondary text-light" data-bs-dismiss="modal" id="cancel-no" aria-label="Close">No</button>
                            <button type="button" class="btn btn-primary text-light" data-bs-dismiss="modal" id="cancel-yes" aria-label="Close">Yes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelledModal" tabindex="-1" aria-labelledby="cancelledModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelledModalLabel">The appointment has been cancelled.</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-12 text-center">
                            <a href="./appointment" class="text-decoration-none text-dark">
                                <p class="m-0 text-primary fw-bold">Click to Continue.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const appointment_date = document.getElementById("appointment_date");
        var startDay = appointment_date.dataset.startday;
        var endDay = appointment_date.dataset.endday;

        function validate_date() {
            const minDate = new Date();
            const maxDate = new Date(minDate);
            maxDate.setMonth(maxDate.getMonth() + 1);

            appointment_date.min = formatDate(minDate);
            appointment_date.max = formatDate(maxDate);

            // Helper function to format date as YYYY-MM-DD
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Helper function to get all allowed days in a weekly cycle
            function getAllowedDaysRange(startDay, endDay) {
                const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                const startIdx = daysOfWeek.indexOf(startDay);
                const endIdx = daysOfWeek.indexOf(endDay);

                // Create allowed days array that cycles through the week
                const allowedDays = [];
                for (let i = startIdx; i !== endIdx + 1; i = (i + 1) % 7) {
                    allowedDays.push(daysOfWeek[i]);
                }

                return allowedDays;
            }

            // Get the allowed days for the specified range
            const allowedDays = getAllowedDaysRange(startDay, endDay);

            // Validate the selected date

            const selectedDate = new Date(appointment_date.value);
            const dayName = selectedDate.toLocaleDateString("en-US", {
                weekday: 'long'
            });


            if (!allowedDays.includes(dayName)) {
                // Set a custom validity message
                appointment_date.setCustomValidity("Please select a valid day from " + startDay + " to " + endDay + ".");
            } else {
                // Clear any previous custom validity message
                appointment_date.setCustomValidity("");
            }
        }

        appointment_date.addEventListener("change", function(event) {
            validate_date();
        });

        const form = document.getElementById('appointmentForm');
        form.addEventListener("submit", function(event) {
            if (!appointment_date.checkValidity()) {
                event.preventDefault(); // Prevent submission if the input is invalid
                appointment_date.reportValidity(); // Show tooltip if invalid
            }
        });

    });

    // Round the time to the nearest half-hour
    function roundTimeToNearestHalfHour(time) {
        let [hours, minutes] = time.split(":");
        minutes = parseInt(minutes);

        if (minutes < 15) {
            minutes = "00";
        } else if (minutes < 45) {
            minutes = "30";
        } else {
            minutes = "00";
            hours = (parseInt(hours) + 1).toString().padStart(2, '0');
        }

        return `${hours.padStart(2, '0')}:${minutes}`;
    }

    document.getElementById("appointment_time").addEventListener("input", function() {
        let inputTime = this.value;
        let roundedTime = roundTimeToNearestHalfHour(inputTime);
        this.value = roundedTime;
    });

    function get_date_schedule(doctor_id, appointment_id) {
        $.ajax({
            url: '../handlers/appointment.get_date_schedule.php',
            type: 'GET',
            data: {
                doctor_id: doctor_id,
                appointment_date: $('#appointment_date').val(),
                appointment_time: $('#appointment_time').val(),
                appointment_id: appointment_id,
                start_day: $('#appointment_date').data('startday'),
                end_day: $('#appointment_date').data('endday'),
                start_wt: $('#appointment_time').attr('min'),
                end_wt: $('#appointment_time').attr('max'),

            },
            success: function(response) {
                $('#schedules').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
            }
        });
    }
</script>

<?php
require_once('../tools/calendar.php');
?>