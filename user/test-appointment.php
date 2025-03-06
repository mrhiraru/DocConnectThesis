<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
    header('location: ../index.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/appointment.class.php');
require_once('../classes/message.class.php');

$appointment_class = new Appointment();
if (isset($_POST['request'])) {
    $appointment_class->patient_id = $_SESSION['patient_id'];
    $appointment_class->doctor_id = htmlentities($_POST['doctor_id']);
    $appointment_class->appointment_date = htmlentities($_POST['appointment_date']);
    $appointment_class->appointment_time = htmlentities($_POST['appointment_time']);
    $appointment_class->estimated_end = date('H:i', strtotime('+59 minutes', strtotime($appointment_class->appointment_time)));
    if (isset($_POST['purpose'])) {
        $account->gender = htmlentities($_POST['purpose']);
    } else {
        $account->gender = '';
    }
    $appointment_class->reason = htmlentities($_POST['reason']);
    $appointment_class->appointment_status = "Pending";

    if (
        validate_field($appointment_class->patient_id) &&
        validate_field($appointment_class->doctor_id) &&
        validate_field($appointment_class->appointment_date) &&
        validate_field($appointment_class->appointment_time) &&
        validate_field($appointment_class->reason) &&
        validate_field($appointment_class->purpose) &&
        validate_field($appointment_class->appointment_status)
    ) {
        if ($appointment_class->add_appointment()) {
            $message = new Message();

            $date_time = new DateTime($_POST['appointment_date'] . ' ' . $_POST['appointment_time']);
            $date_time = $date_time->format('F j, Y \a\t h:i A');
            $id = $message->get_doctor_account($appointment_class->doctor_id);

            $message->sender_id = $_SESSION['account_id'];
            $message->receiver_id = $id['account_id'];
            $message->message = $_SESSION['fullname'] . ' has requested an appointment on ' . $date_time . '.';
            $message->message_type = 'System';

            if (
                validate_field($message->message) &&
                validate_field($message->sender_id) &&
                validate_field($message->receiver_id)
            ) {
                if ($message->send_message()) {
                    $success = 'success';
                } else {
                    echo 'An error occured while adding in the database.';
                }
            } else {
                $success = 'failed';
            }
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
    require_once('../includes/header.php');
    ?>

    <form id="appointmentForm" action="" method="post" class="container-fluid row g-2 p-3 d-flex justify-content-center">
        <section id="appointment" class="col-12 col-md-8 page-container padding-medium">
            <div id="" class="border border-dark-subtle shadow-sm rounded-2 p-3 m-0 mb-4 mb-md-0">
                <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-6 text-start">
                        <p class="form-label text-black-50 fw-bold fs-5 m-0"> Appointment Form</p>
                    </div>
                    <div class="col-6 text-end">
                        <button type="button" class="btn btn-sm btn-primary text-light" data-bs-toggle="modal" data-bs-target="#patientDetails">
                            View Patient Details
                        </button>
                    </div>
                </div>

                <hr>
                <div class="col-12">
                    <div class="d-flex flex-row flex-wrap justify-content-start mb-3">
                        <select name="doctor_id" id="doctor_id" class="col-12" onchange="show_doctor_info(this.value)">
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
                    <select id="purpose" name="purpose" class="form-select bg-light border border-dark" required>
                        <option value="" disabled <?= !isset($_POST['purpose']) ? 'selected' : '' ?>>Select a purpose</option>
                        <option value="Check-up" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Check-up") ? 'selected' : '' ?>>Check-up</option>
                        <option value="Follow-up" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Follow-up") ? 'selected' : '' ?>>Follow-up</option>
                        <option value="Medical Advice" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Medical Advice") ? 'selected' : '' ?>>Medical Advice</option>
                        <option value="Mental Health Consultation" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Mental Health Consultation") ? 'selected' : '' ?>>Mental Health Consultation</option>
                        <option value="Dietary and Nutrition Advice" <?= (isset($_POST['purpose']) && $_POST['purpose'] == "Dietary and Nutrition Advice") ? 'selected' : '' ?>>Dietary and Nutrition Advice</option>
                    </select>
                    <?php
                    if (isset($_POST['purpose']) && !validate_field($_POST['purpose'])) {
                    ?>
                        <p class="text-dark m-0 ps-2">Select a purpose for the appointment.</p>
                    <?php
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="reason" class="form-label text-black-50">Reason:</label>
                    <textarea class="form-control bg-light fs-6 mb-3 border border-dark" rows="3" id="reason" name="reason" placeholder="Include your reason for appointment."></textarea>
                    <?php
                    if (isset($_POST['reason']) && !validate_field($_POST['reason'])) {
                    ?>
                        <p class="text-dark m-0 ps-2">Reason for appointment is required.</p>
                    <?php
                    }
                    ?>
                </div>

                <div class="container mt-4">
                    <div class="row g-3">
                        <!-- Date Picker -->
                        <div class="col-lg-6">
                            <label for="appointment_date" class="form-label text-secondary fw-semibold">Select Date</label>
                            <div class="p-2 border rounded bg-light shadow-sm">
                                <input type="text" id="appointment_date" name="appointment_date" class="form-control border-0 text-center fs-6 mb-3 border border-dark" placeholder="SELECT DATE" required readonly>
                            </div>
                            <?php
                            if (isset($_POST['appointment_date']) && !validate_field($_POST['appointment_date'])) {
                            ?>
                                <p class="text-danger small mt-1">Select a valid appointment date.</p>
                            <?php
                            }
                            ?>
                        </div>

                        <!-- Time Picker -->
                        <div class="col-lg-6">
                            <label for="appointment_time" class="form-label text-secondary fw-semibold">Select Time</label>
                            <div class="p-2 pb-3 border rounded bg-light shadow-sm">
                                <input type="text" id="appointment_time" name="appointment_time" class="form-control border-0 text-center fs-6 mb-3 border border-dark" placeholder="SELECT TIME" required readonly>
                            </div>
                            <?php
                            if (isset($_POST['appointment_time']) && !validate_field($_POST['appointment_time'])) {
                            ?>
                                <p class="text-danger small mt-1">Select a valid appointment time.</p>
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

    <div class="modal fade" id="patientDetails" tabindex="-1" aria-labelledby="patientDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="form-label text-black-50 fw-bold fs-5 m-0">Patient Details</p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="firstname" class="form-label text-black-50">First Name</label>
                            <input type="text" class="form-control bg-light border border-dark" id="firstname" name="firstname" value="<?= isset($_SESSION['firstname']) ? $_SESSION['firstname'] : "" ?>" required readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="middlename" class="form-label text-black-50">Middle Name</label>
                            <input type="text" class="form-control bg-light border border-dark" id="middlename" name="middlename" name="firstname" value="<?= isset($_SESSION['middlename']) ? $_SESSION['middlename'] : "" ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="lastname" class="form-label text-black-50">Last Name</label>
                            <input type="text" class="form-control bg-light border border-dark" id="lastname" name="lastname" name="firstname" value="<?= isset($_SESSION['lastname']) ? $_SESSION['lastname'] : "" ?>" required readonly>
                        </div>
                    </div>

                    <!-- Birthdate, Gender -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-black-50">Date of Birth</label>
                            <input type="date" class="form-control bg-light border border-dark" id="birthdate" name="birthdate" placeholder="MM/DD/YYYY" required value="<?= isset($_SESSION['birthdate']) ? date('Y-m-d', strtotime($_SESSION['birthdate'])) : '' ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label text-black-50">Gender</label>
                            <input type="text" class="form-control bg-light border border-dark" id="gender" name="gender" value="<?= isset($_SESSION['gender']) ? $_SESSION['gender'] : "" ?>" required readonly />
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label text-black-50">Email</label>
                            <input type="email" class="form-control bg-light border border-dark" id="email" name="email" placeholder="example@example.com" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : "" ?>" required readonly>
                        </div>
                        <div class="col-md-6">

                            <label for=" phoneNo" class="form-label text-black-50">Contact No.</label>
                            <input type="text" class="form-control bg-light border border-dark" id="phoneNo" name="Phone_No" value="<?= isset($_SESSION['contact']) ? $_SESSION['contact'] : "" ?>" pattern="\+63 \d{3} \d{3} \d{4}" required readonly />
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label text-black-50">Address</label>
                        <input type="text" class="form-control bg-light border border-dark" id="address" name="address" placeholder="Street, City, State, Postal Code" value="<?= isset($_SESSION['address']) ? $_SESSION['address'] : "" ?>" required readonly>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="campus_name" class="form-label text-black-50">Campus</label>
                            <input type="text" class="form-control bg-light border border-dark" id="campus_name" name="campus_name" value="<?= isset($_SESSION['campus_name']) ? $_SESSION['campus_name'] : "" ?>" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label text-black-50">Role</label>
                            <input type="text" class="form-control bg-light border border-dark" id="role" name="role" value="<?= isset($_SESSION['role']) ? $_SESSION['role'] : "" ?>" required readonly />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once('../includes/footer.php');
    ?>

    <?php
    if (isset($_POST['request']) && $success == 'success') {
    ?>
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">You have requested an appointment successfully!</h5>
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
    <?php
    }
    ?>
    <script src="../js/main.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var startDay;
            var endDay;
            var startTime = "00:00:00";
            var endTime = "00:00:00";
            var request_btn = document.getElementById('request');
            var full_dates = [];
            var doctor_id = '<?= $_SESSION['doctor_id'] ?>';

            reinitializeFlatpickr();

            document.getElementById("doctor_id").addEventListener("change", function() {
                if (!this.value) { // Check if no doctor is selected

                    startDay = "";
                    endDay = "";
                    startTime = "00:00:00";
                    endTime = "00:00:00";
                    full_dates = [];

                    document.getElementById('appointment_time').value = '';
                    reinitializeFlatpickr();
                    request_btn.setAttribute('disabled', 'true'); // Ensure it's disabled
                } else {
                    let selectedOption = this.options[this.selectedIndex];

                    startDay = selectedOption.getAttribute("data-startday");
                    endDay = selectedOption.getAttribute("data-endday");
                    startTime = selectedOption.getAttribute("data-starttime");
                    endTime = subtractOneHour(selectedOption.getAttribute("data-endtime"));
                    full_dates = selectedOption.getAttribute("data-fulldates").split(', ');

                    reinitializeFlatpickr();
                    request_btn.removeAttribute('disabled'); // Ensure it's enabled
                }
            });

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
                        available_time(dateStr, doctor_id);
                    }
                });

                flatpickr("#appointment_time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i:s",
                    altInput: true,
                    altFormat: "h:i K",
                    inline: true,
                    minuteIncrement: 60,
                    minTime: startTime,
                    maxTime: endTime,
                });
            }

            function reinitializeFlatpickrTime(disabled_hours) {

                if (!Array.isArray(disabled_hours)) {
                    console.error("disabled_hours is not an array:", disabled_hours);
                    return;
                }
                console.error("Found:", disabled_hours);
                flatpickr("#appointment_time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i:s",
                    altInput: true,
                    altFormat: "h:i K",
                    inline: true,
                    minuteIncrement: 60,
                    minTime: startTime,
                    maxTime: endTime,
                    disable: disabled_hours.map(hour => {
                        return {
                            from: `${String(hour).padStart(2, "0")}:00:00`,
                            to: `${String(hour).padStart(2, "0")}:59:59`
                        };
                    })
                });
            }

            function available_time(date, doctor_id) {
                $.ajax({
                    url: '../handlers/appointment.get_date_available_time.php',
                    type: 'GET',
                    data: {
                        date,
                        doctor_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Raw Response:", response); // Debug JSON response

                        let disabled_hours = response;

                        reinitializeFlatpickrTime(disabled_hours);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching doctor information:', error);
                    }
                });
            }

            new TomSelect("#doctor_id", {
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });



        function show_doctor_info(account_id) {

            if (account_id) {
                $.ajax({
                    url: '../handlers/appointment.show_doctor_info.php',
                    type: 'GET',
                    data: {
                        account_id: account_id
                    },
                    success: function(response) {
                        $('#doctor_info').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching doctor information:', error);
                    }
                });
            } else {
                $('#doctor_info').html("<p class='text-center text-muted m-0 p-0'>No doctor selected.</p>");
            }
        }

        function subtractOneHour(time) {
            let [hours, minutes] = time.split(":").map(Number);
            hours = (hours === 0) ? 23 : hours - 1; // Handle midnight wrap-around
            return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
        }
    </script>
</body>

</html>