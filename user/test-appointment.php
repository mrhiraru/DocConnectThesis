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
                        <input type="text" id="doctorSearch" class="form-control bg-light border border-dark" placeholder="Select Doctor" aria-label="Doctor search" value="">
                        <ul id="doctorDropdown" class="docDropDown list-group position-absolute d-none w-50" style="max-height: 200px; overflow-y: auto; z-index: 100; margin-top: 2.3rem;"></ul>
                        <input type="hidden" id="doctor_id" name="doctor_id" value="">
                    </div>
                    <div class="row align-items-center border p-3 mx-2 rounded bg-light">
                        <div class="d-flex justify-content-center col-12 col-md-auto mb-3 mb-md-0">
                            <img id="account_image" src="../assets/images/default_profile.png" alt="Doctor Profile" width="125" height="125" class="rounded-circle border border-2 shadow-sm">
                        </div>
                        <div class="col-12 col-md-7">
                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Name: <span class="text-black" id="doctor_name">Not Selected</span> </p>
                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Specialty: <span class="text-black" id="specialty">N/A</span> </p>
                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Contact: <span class="text-black" id="contact">N/A</span> </p>
                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Email: <span class="text-black" id="email">N/A</span> </p>
                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Working Days: <span class="text-black" id="working_day">N/A</span> </p>
                            <p class="fs-6 fw-semibold text-dark mb-1 text-black-50">Working Time: <span class="text-black" id="working_time">N/A</span> </p>
                        </div>
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
                                <input type="text" id="appointment_date" name="appointment_date" class="form-control border-0 text-center fs-6 mb-3 border border-dark" placeholder="MONTH DD YYYY" required readonly>
                            </div>
                            <?php if (isset($_POST['appointment_date']) && !validate_field($_POST['appointment_date'])) { ?>
                                <p class="text-danger small mt-1">Select a valid appointment date.</p>
                            <?php } ?>
                        </div>

                        <!-- Time Picker -->
                        <div class="col-lg-6">
                            <label for="appointment_time" class="form-label text-secondary fw-semibold">Select Time</label>
                            <div class="p-2 pb-3 border rounded bg-light shadow-sm">
                                <input type="text" id="appointment_time" name="appointment_time" class="form-control border-0 text-center fs-6 d-none" placeholder="HH:MM AM/PM" required>
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
            const doctorSearch = document.getElementById("doctorSearch");
            const doctorDropdown = document.getElementById("doctorDropdown");
            const doctorIdInput = document.getElementById("doctor_id");
            const specialty = document.getElementById("specialty");
            const contact = document.getElementById("contact");
            const email = document.getElementById("email");
            const working_days = document.getElementById("working_day");
            const working_hours = document.getElementById("working_time");
            const account_image = document.getElementById("account_image");
            const appointment_time = document.getElementById("appointment_time");
            const appointment_date = document.getElementById("appointment_date");
            const doctor_name = document.getElementById("doctor_name");
            const request_button = document.getElementById("request");

            var startDay;
            var endDay;

            fetch('../handlers/appointment.get_doctors.php')
                .then(response => response.json())
                .then(data => {

                    doctorSearch.addEventListener("focus", function() {
                        if (doctorSearch.value === '' && data.length > 0) {
                            doctorDropdown.classList.remove('d-none');
                            populateDropdown(data);
                        }
                    });

                    doctorSearch.addEventListener("input", function() {
                        const searchTerm = doctorSearch.value.toLowerCase();
                        doctorDropdown.innerHTML = '';

                        const filteredDoctors = data.filter(doctor =>
                            doctor.doctor_name.toLowerCase().includes(searchTerm)
                        );

                        populateDropdown(filteredDoctors);
                    });

                    function populateDropdown(doctors) {
                        doctorDropdown.innerHTML = '';

                        doctors.forEach(doctor => {
                            const li = document.createElement("li");
                            li.classList.add("list-group-item", "cursor-pointer");
                            li.textContent = doctor.doctor_name;
                            li.setAttribute("data-id", doctor.account_id);

                            li.addEventListener("click", function() {
                                doctor_name.innerHTML = doctor.doctor_name;
                                doctorIdInput.value = doctor.doctor_id;
                                specialty.innerHTML = doctor.specialty;
                                contact.innerHTML = doctor.contact;
                                email.innerHTML = doctor.email;
                                working_days.innerHTML = doctor.start_day + " to " + doctor.end_day;
                                working_hours.innerHTML = formatTime(doctor.start_wt) + " to " + formatTime(doctor.end_wt);
                                account_image.src = "../assets/images/" + doctor.account_image;


                                appointment_time.min = formatMySQLTimeTo24Hour(doctor.start_wt);
                                appointment_time.max = subtractOneHour(formatMySQLTimeTo24Hour(doctor.end_wt));
                                appointment_date.dataset.startday = doctor.start_day;
                                appointment_date.dataset.endday = doctor.end_day;
                                request_button.removeAttribute("disabled");
                                doctorDropdown.classList.add('d-none');


                                startDay = appointment_date.dataset.startday;
                                endDay = appointment_date.dataset.endday;

                                validate_date();
                            });

                            doctorDropdown.appendChild(li);

                        });

                        if (doctors.length > 0) {
                            doctorDropdown.classList.remove('d-none');
                        } else {
                            doctorDropdown.classList.add('d-none');
                        }
                    }
                })
                .catch(error => console.error('Error fetching doctors:', error));


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

            appointment_date.addEventListener("input", function(event) {
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

        document.getElementById("appointment_time").addEventListener("change", function() {
            let inputTime = this.value;
            let roundedTime = roundTimeToNearestHalfHour(inputTime);
            this.value = roundedTime;
        });

        function formatTime(time) {
            let [hours, minutes] = time.split(':');
            hours = parseInt(hours);
            let suffix = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;

            return `${hours}:${minutes} ${suffix}`;
        }

        function formatMySQLTimeTo24Hour(time) {
            const [hours, minutes] = time.split(':');

            return `${hours}:${minutes}`;
        }

        function subtractOneHour(time) {
            let [hours, minutes] = time.split(':');
            hours = parseInt(hours) - 1;
            if (hours < 0) {
                hours = 23;
            }
            return `${hours.toString().padStart(2, '0')}:${minutes}`;
        }
    </script>

    <script>
        function disableDates(date) {
            const today = new Date();
            const threeDaysLater = new Date();
            threeDaysLater.setDate(today.getDate() + 3);
            return date.getDay() === 0 || date < threeDaysLater;
        }

        flatpickr("#appointment_date", {
            dateFormat: "Y-m-d",
            allowInput: true,
            inline: true,
            disable: [disableDates]
        });

        flatpickr("#appointment_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i:s",
            time_24hr: false,
            allowInput: true,
            inline: true,
            minuteIncrement: 60,
            minTime: "09:00", // Set minimum time (9:00 AM)
            maxTime: "17:00" // Set maximum time (5:00 PM)
        });
    </script>
</body>

</html>