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
<style>
    .datepicker-container {
        display: flex;
        justify-content: center;
        /* Horizontally center */
        align-items: center;
        /* Vertically center (if needed) */
        width: 100%;
        /* Ensure it takes full width */
    }

    .flatpickr-calendar {
        margin: auto;
        /* Center the calendar */
    }

    .flatpickr-time .flatpickr-hour,
    .flatpickr-time .flatpickr-minute {
        pointer-events: none;
        /* Prevent users from manually changing the hour */
    }

    /* Hide all arrows by default */
    .flatpickr-time .numInputWrapper span.arrowUp,
    .flatpickr-time .numInputWrapper span.arrowDown {
        display: none;
    }

    /* Show only the arrows for the minutes input */
    .flatpickr-time .numInputWrapper .flatpickr-minute~span.arrowUp,
    .flatpickr-time .numInputWrapper .flatpickr-minute~span.arrowDown {
        display: inline-block !important;
        /* Make minute arrows visible */
    }
</style>

<body>
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
                                <label for="purpose" class="form-label text-black-50">Purpose of Appointment</label>
                                <textarea id="purpose" name="purpose" class="form-control bg-light border border-dark" rows="" placeholder="Describe the reason for your appointment (e.g., symptoms, check-up, follow-up)" required readonly><?= $record['purpose'] ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="reason" class="form-label text-black-50">Reason</label>
                                <textarea id="reason" name="reason" class="form-control bg-light border border-dark" rows="" placeholder="Describe the reason for your appointment (e.g., symptoms, check-up, follow-up)" required readonly><?= $record['reason'] ?></textarea>
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
                            <div class="container mt-4">
                                <div class="row g-3">
                                    <!-- Date Picker -->
                                    <div class="col-lg-6" id="date_picker_cont">
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
                                    <div class="col-lg-6" id="time_picker_cont">
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
<?php
$appArray = $appointment_class->get_full_dates($_SESSION['doctor_id'], $_SESSION['start_wt'], $_SESSION['end_wt']);

$fullDates = array_map(function ($date) {
    return date('Y-m-d', strtotime($date['appointment_date']));
}, $appArray);

if (in_array(date('Y-m-d', strtotime($record['appointment_date'])), $fullDates)) {
    $key = array_search(date('Y-m-d', strtotime($record['appointment_date'])), $fullDates);
    unset($fullDates[$key]);
    $fullDates = array_values($fullDates);
}

$formattedDates = implode(', ', $fullDates);
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var startDay = "<?= $_SESSION['start_day'] ?>";
        var endDay = "<?= $_SESSION['end_day'] ?>";
        var startTime = "<?= $_SESSION['start_wt'] ?>";
        var endTime = subtractOneHour("<?= $_SESSION['end_wt'] ?>");
        var rawendTime = "<?= $_SESSION['end_wt'] ?>";
        //var request_btn = document.getElementById('request');
        var full_dates = "<?= $formattedDates ?>".split(', ');
        console.log(full_dates);
        var doctor_id = "<?= $_SESSION['doctor_id'] ?>";


        let defaultAppointmentDate = "<?= $record['appointment_date'] ?>";
        let defaultAppointmentTime = "<?= $record['appointment_time'] ?>";

        let datecontainer = document.getElementById('date_picker_cont');
        let appointmentdate = datecontainer.querySelector('.form-control.input');

        let timecontainer = document.getElementById('time_picker_cont');
        let appointmenttime = timecontainer.querySelector('.form-control.input');

        reinitializeFlatpickr();

        function getDisabledDays(startDay, endDay) {
            const daysMap = {
                "Sunday": 0,
                "Monday": 1,
                "Tuesday": 2,
                "Wednesday": 3,
                "Thursday": 4,
                "Friday": 5,
                "Saturday": 6
            };

            let start = daysMap[startDay];
            let end = daysMap[endDay];

            return [
                function(date) {
                    let day = date.getDay();
                    let today = new Date();
                    let threeDaysLater = new Date();
                    threeDaysLater.setDate(today.getDate() + 3); // Disable next 3 days


                    if (date < threeDaysLater) return true; // Disable next 3 days

                    if (start <= end) {
                        return !(day >= start && day <= end);
                    } else {
                        return !(day >= start || day <= end); // Handles wrap-around (e.g., Friday to Monday)
                    }
                }
            ]; // Wrapped inside an array
        }

        function reinitializeFlatpickr() {
            flatpickr("#appointment_date", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "F j, Y",
                inline: true,
                disable: [
                    ...full_dates, // Directly disable full dates
                    ...getDisabledDays(startDay, endDay) // Function for disabling other conditions
                ],
                onChange: function(selectedDates, dateStr, instance) {
                    available_time(dateStr, doctor_id, startTime, endTime);
                    set_value(null);
                },
                defaultDate: defaultAppointmentDate,
                onReady: function(selectedDates, dateStr, instance) {
                    available_time(dateStr, doctor_id, startTime, endTime);
                }
                // ended here onready of page show the time buttons
            });

            flatpickr("#appointment_time", {
                enableTime: false,
                noCalendar: true,
                dateFormat: "H:i:s",
                altInput: true,
                altFormat: "h:i K",
                inline: true,
                minuteIncrement: 60,
                minTime: startTime,
                maxTime: endTime,
                defaultDate: defaultAppointmentTime
            });
        }
    });

    function set_value(selectedRadio) {
        if (selectedRadio === null) {

            document.getElementById('appointment_time').value = null;
            let timecontainer = document.getElementById('time_picker_cont');

            let appointmentInput = timecontainer.querySelector('.form-control.input');
            appointmentInput.value = "SELECT TIME";
        } else {
            let selectedTime = selectedRadio.value; // Get time in HH:MM:SS format
            let formattedTime = selectedRadio.nextElementSibling.textContent.split(" - ")[0]; // Extract AM/PM format

            document.getElementById('appointment_time').value = selectedTime;

            let timecontainer = document.getElementById('time_picker_cont');

            let appointmentInput = timecontainer.querySelector('.form-control.input');
            appointmentInput.value = formattedTime + " - " + addOneHour(selectedTime);
        }
    }

    function addOneHour(time) {
        let [hours, minutes] = time.split(":").map(Number); // Extract HH and MM

        hours = (hours + 1) % 24; // Add one hour and wrap around if it exceeds 23

        let period = hours >= 12 ? "PM" : "AM"; // Determine AM or PM
        let formattedHours = hours % 12 || 12; // Convert 24-hour to 12-hour format

        return `${String(formattedHours).padStart(2, "0")}:${String(minutes).padStart(2, "0")} ${period}`;
    }

    function available_time(date, doctor_id, start, end) {
        $.ajax({
            url: '../handlers/appointment.get_date_available_time.php',
            type: 'GET',
            data: {
                date,
                doctor_id,
                startTime: start,
                endTime: end
            },
            success: function(response) {
                $('#available_time').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching available time:', error);
            }
        });
    }

    function subtractOneHour(time) {
        let [hours, minutes] = time.split(":").map(Number);
        hours = (hours === 0) ? 23 : hours - 1; // Handle midnight wrap-around
        return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
    }
</script>

<?php
require_once('../tools/calendar.php');
?>