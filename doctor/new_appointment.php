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
require_once('../classes/message.class.php');

$appointment_class = new Appointment();
$refer = new Refer();
$appointment_record = $appointment_class->get_appointment_details($_GET['appointment_id']);

$appointment_class = new Appointment();
if (isset($_POST['request'])) {
    $appointment_class->patient_id = htmlentities($_POST['patient_id']);
    $appointment_class->doctor_id = htmlentities($_POST['doctor_id']);
    $appointment_class->appointment_date = htmlentities($_POST['appointment_date']);
    $appointment_class->appointment_time = htmlentities($_POST['appointment_time']);
    $appointment_class->estimated_end = date('H:i', strtotime('+59 minutes', strtotime($appointment_class->appointment_time)));
    if (isset($_POST['purpose'])) {
        $appointment_class->purpose = htmlentities($_POST['purpose']);
    } else {
        $appointment_class->purpose = '';
    }
    $appointment_class->reason = htmlentities($_POST['reason']);
    $appointment_class->appointment_status = "Incoming";

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
            $message->message = 'Referral Accepted: You have incoming appointment on ' . $date_time . '.';
            $message->message_type = 'System';

            if (
                validate_field($message->message) &&
                validate_field($message->sender_id) &&
                validate_field($message->receiver_id)
            ) {
                if ($message->send_message()  && $refer->update_status($_GET['referral_id'], 'Accepted')) {
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
                                <input type="hidden" name="doctor_id" value="<?= $_SESSION['doctor_id'] ?>">
                                <input type="hidden" name="purpose" value="<?= $appointment_record['purpose'] ?>">
                                <input type="hidden" name="reason" value="<?= $appointment_record['reason'] ?>">
                                <input type="hidden" name="patient_id" value="<?= $appointment_record['patient_id'] ?>">
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
                                    <button id="request" name="request" type="submit" class="col-12 col-md-6 col-lg-4 btn btn-primary text-light mt-2">Request Appointment</button>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php
    $appArray = $appointment_class->get_full_dates($_SESSION['doctor_id'], $_SESSION['start_wt'], $_SESSION['end_wt']);

    $fullDates = array_map(function ($date) {
        return date('Y-m-d', strtotime($date['appointment_date']));
    }, $appArray);

    $formattedDates = implode(', ', $fullDates);
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var startDay = '<?= $_SESSION["start_day"] ?>';
            var endDay = '<?= $_SESSION["end_day"] ?>';
            var startTime = '<?= $_SESSION["start_wt"] ?>';
            var endTime = subtractOneHour('<?= $_SESSION["end_wt"] ?>');
            var rawendTime = '<?= $_SESSION["end_wt"] ?>';
            var request_btn = document.getElementById("request");
            var full_dates = '<?= $formattedDates ?>';
            var doctor_id = '<?= $_SESSION['doctor_id'] ?>';

            reinitializeFlatpickr();

            var doctorSelect = document.getElementById("doctor_id");



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
                    }
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
                    endTime: end,
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
</body>

</html>