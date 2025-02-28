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
    $appointment_class->reason = htmlentities($_POST['reason']);
    $appointment_class->appointment_status = "Pending";

    if (
        validate_field($appointment_class->patient_id) &&
        validate_field($appointment_class->doctor_id) &&
        validate_field($appointment_class->appointment_date) &&
        validate_field($appointment_class->appointment_time) &&
        validate_field($appointment_class->reason) &&
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

<body>
    <?php
    require_once('../includes/header.php');
    ?>

    <div class="row">
        <section id="appointment" class="page-container padding-medium p-3">
            <div class="row mb-3">
                <div class="col-2"></div>
                <div class="col-sm-12 col-md-8">
                    <form id="appointmentForm" action="" method="post" class="border border-dark-subtle shadow-sm rounded-2 p-3 mb-4 mb-md-0">
                        <div class="row">
                            <div class="col-12">
                                <label for="doctorSearch" class="form-label text-black-50 fw-bold fs-5">Select Doctor</label>
                                <div class="d-flex flex-row flex-wrap justify-content-start mb-3">
                                    <input type="text" id="doctorSearch" class="form-control bg-light border border-dark" placeholder="Search" aria-label="Doctor search" value="">
                                    <ul id="doctorDropdown" class="docDropDown list-group position-absolute d-none w-50" style="max-height: 200px; overflow-y: auto; z-index: 100; margin-top: 2.3rem;"></ul>
                                    <input type="hidden" id="doctor_id" name="doctor_id" value="">
                                </div>
                                <div class="row align-items-center border p-3 mx-2 rounded bg-light">
                                    <div class="d-flex justify-content-center col-12 col-md-3 mb-3 mb-md-0">
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
                        </div>

                        <hr>

                        <!-- Name -->
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
                                <label for="gender" class="form-label text-black-50">Gender</label>
                                <select class="form-select bg-light border border-dark" id="gender" name="gender" required>
                                    <option value="Male" <?= (isset($_SESSION['gender']) && $_SESSION['gender'] == "Male") ? 'selected' : 'hidden' ?>>Male</option>
                                    <option value="Female" <?= (isset($_SESSION['gender']) && $_SESSION['gender'] == "Female") ? 'selected' : 'hidden' ?>>Female</option>
                                    <option value="Other" <?= (isset($_SESSION['gender']) && $_SESSION['gender'] == "Other") ? 'selected' : 'hidden' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label for="phoneNo" class="form-label text-black-50">Phone No.</label>
                            <input type="text" class="form-control bg-light border border-dark" id="phoneNo" name="Phone_No" value="<?= isset($_SESSION['contact']) ? $_SESSION['contact'] : "" ?>" pattern="\+63 \d{3} \d{3} \d{4}" required readonly />
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label text-black-50">Address</label>
                            <input type="text" class="form-control bg-light border border-dark" id="address" name="address" placeholder="Street, City, State, Postal Code" value="<?= isset($_SESSION['address']) ? $_SESSION['address'] : "" ?>" required readonly>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-black-50">Email</label>
                            <input type="email" class="form-control bg-light border border-dark" id="email" name="email" placeholder="example@example.com" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : "" ?>" required readonly>
                        </div>

                        <!-- Preferred Appointment Date -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="appointment_date" class="form-label text-black-50">Select Date</label>
                                <input type="date" id="appointment_date" name="appointment_date" data-startday="" data-endday="" min="<?php echo date('Y-m-d'); ?>" class="form-control fs-6 px-2 py-1 bg-light border border-dark" required>
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
                                <input type="time" id="appointment_time" name="appointment_time" step="1800" min="" max="" class="form-control fs-6 px-2 py-1 bg-light border border-dark" required>
                                <?php
                                if (isset($_POST['appointment_time']) && !validate_field($_POST['appointment_time'])) {
                                ?>
                                    <p class="text-dark m-0 ps-2">Select appointment time.</p>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label text-black-50">Reason for appointment?</label>
                            <textarea id="reason" name="reason" class="form-control bg-light border border-dark" rows="3" placeholder="Describe the reason for your appointment (e.g., symptoms, check-up, follow-up)" required></textarea>
                            <?php
                            if (isset($_POST['reason']) && !validate_field($_POST['reason'])) {
                            ?>
                                <p class="text-dark m-0 ps-2">Enter reason for appointment.</p>
                            <?php
                            }
                            ?>
                        </div>
                        <p class="text-black-50 m-0"><span>Note:</span> The scheduled appointment date and time may be adjusted upon the doctor's approval.</p>
                        <hr class="my-2">
                        <div class="w-100 d-flex justify-content-end">
                            <button id="request" name="request" type="submit" class="col-12 col-md-6 col-lg-4 btn btn-outline-dark mt-2" disabled>Request Appointment</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
        <section id="appointment" class="page-container padding-medium p-3">
            <div class="row mb-3">
                <div class="col-2"></div>
                <div class="col-sm-12 col-md-8">
                    <form id="appointmentForm" action="" method="post" class="border border-dark-subtle shadow-sm rounded-2 p-3 mb-4 mb-md-0">
                        <div class="row">
                            <div class="col-12">
                                <label for="doctorSearch" class="form-label text-black-50 fw-bold fs-5">Select Doctor</label>
                                <div class="d-flex flex-row flex-wrap justify-content-start mb-3">
                                    <input type="text" id="doctorSearch" class="form-control bg-light border border-dark" placeholder="Search" aria-label="Doctor search" value="">
                                    <ul id="doctorDropdown" class="docDropDown list-group position-absolute d-none w-50" style="max-height: 200px; overflow-y: auto; z-index: 100; margin-top: 2.3rem;"></ul>
                                    <input type="hidden" id="doctor_id" name="doctor_id" value="">
                                </div>
                                <div class="row align-items-center border p-3 mx-2 rounded bg-light">
                                    <div class="d-flex justify-content-center col-12 col-md-3 mb-3 mb-md-0">
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
                        </div>

                        <hr>

                        <!-- Name -->
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
                                <label for="gender" class="form-label text-black-50">Gender</label>
                                <select class="form-select bg-light border border-dark" id="gender" name="gender" required>
                                    <option value="Male" <?= (isset($_SESSION['gender']) && $_SESSION['gender'] == "Male") ? 'selected' : 'hidden' ?>>Male</option>
                                    <option value="Female" <?= (isset($_SESSION['gender']) && $_SESSION['gender'] == "Female") ? 'selected' : 'hidden' ?>>Female</option>
                                    <option value="Other" <?= (isset($_SESSION['gender']) && $_SESSION['gender'] == "Other") ? 'selected' : 'hidden' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label for="phoneNo" class="form-label text-black-50">Phone No.</label>
                            <input type="text" class="form-control bg-light border border-dark" id="phoneNo" name="Phone_No" value="<?= isset($_SESSION['contact']) ? $_SESSION['contact'] : "" ?>" pattern="\+63 \d{3} \d{3} \d{4}" required readonly />
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label text-black-50">Address</label>
                            <input type="text" class="form-control bg-light border border-dark" id="address" name="address" placeholder="Street, City, State, Postal Code" value="<?= isset($_SESSION['address']) ? $_SESSION['address'] : "" ?>" required readonly>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-black-50">Email</label>
                            <input type="email" class="form-control bg-light border border-dark" id="email" name="email" placeholder="example@example.com" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : "" ?>" required readonly>
                        </div>

                        <!-- Preferred Appointment Date -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="appointment_date" class="form-label text-black-50">Select Date</label>
                                <input type="date" id="appointment_date" name="appointment_date" data-startday="" data-endday="" min="<?php echo date('Y-m-d'); ?>" class="form-control fs-6 px-2 py-1 bg-light border border-dark" required>
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
                                <input type="time" id="appointment_time" name="appointment_time" step="1800" min="" max="" class="form-control fs-6 px-2 py-1 bg-light border border-dark" required>
                                <?php
                                if (isset($_POST['appointment_time']) && !validate_field($_POST['appointment_time'])) {
                                ?>
                                    <p class="text-dark m-0 ps-2">Select appointment time.</p>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label text-black-50">Reason for appointment?</label>
                            <textarea id="reason" name="reason" class="form-control bg-light border border-dark" rows="3" placeholder="Describe the reason for your appointment (e.g., symptoms, check-up, follow-up)" required></textarea>
                            <?php
                            if (isset($_POST['reason']) && !validate_field($_POST['reason'])) {
                            ?>
                                <p class="text-dark m-0 ps-2">Enter reason for appointment.</p>
                            <?php
                            }
                            ?>
                        </div>
                        <p class="text-black-50 m-0"><span>Note:</span> The scheduled appointment date and time may be adjusted upon the doctor's approval.</p>
                        <hr class="my-2">
                        <div class="w-100 d-flex justify-content-end">
                            <button id="request" name="request" type="submit" class="col-12 col-md-6 col-lg-4 btn btn-outline-dark mt-2" disabled>Request Appointment</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
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

                        if (autoClickElement) {
                            setTimeout(() => {
                                autoClickElement.click();

                                // Remove doctor_id from the URL after auto-click
                                const newUrl = window.location.pathname + window.location.search.replace(/([?&])doctor_id=[^&]+(&|$)/, '$1');
                                const cleanUrl = newUrl.endsWith('?') || newUrl.endsWith('&') ? newUrl.slice(0, -1) : newUrl;
                                window.history.replaceState(null, '', cleanUrl);

                                autoClickElement = null;
                                doctorSearch.blur();

                                // Hide the dropdown
                                doctorDropdown.classList.add('d-none');
                            }, 200); // Ensure UI updates
                        }

                        if (doctors.length > 0) {
                            doctorDropdown.classList.remove('d-none');
                        } else {
                            doctorDropdown.classList.add('d-none');
                        }
                    }
                })
                .catch(error => console.error('Error fetching doctors:', error));

            // Check if the selected day is within the allowed range
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
</body>

</html>