<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header('location: ../index.php');
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
                <div class="card my-4 col-8">
                    <div class="card-body">
                        <h4>Manage Appointment</h4>
                        <form id="addEventForm">
                            <hr class="my-3 opacity-25">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-12">
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
                            </div>
                            <hr class="my-3 opacity-25">
                            <div class="row d-flex flex-wrap justify-content-center justify-content-md-start m-0">
                                <div class="col-6 p-0 pe-4 mb-3">
                                    <p class="fs-6 mb-0">Select Date</p>
                                    <input type="date" id="appointment_date" name="appointment_date" data-startday="" data-endday="" min="<?php echo date('Y-m-d'); ?>" class="form-control fs-6 px-2 py-1 bg-white border border-dark rounded-1 text-black-50 w-100" required>
                                </div>
                                <div class="col-6 p-0 mb-3">
                                    <p class="fs-6 mb-0">Select Time</p>
                                    <input type="time" id="appointment_time" name="appointment_time" step="1800" min="" max="" class="form-control fs-6 px-2 py-1 bg-white border border-dark rounded-1 text-black-50 w-100" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="eventUrl" class="form-label">Meeting URL</label>
                                <div class="row d-flex justify-content-between">
                                    <div class="col-auto flex-grow-1">
                                        <input type="url" class="form-control" id="eventUrl" name="eventUrl" placeholder="Generate link for meeting." readonly>
                                    </div>
                                    <div class="col-auto ps-0">
                                        <button class="btn btn-success text-light">Generate Link</button>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3 opacity-25">
                            <div class="m-0 p-0 text-end">
                                <button type="submit" class="btn btn-primary text-light">Confirm Appointment</button>
                            </div>
                        </form>


                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Modal for Adding Events -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEventForm">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Event Title</label>
                            <input type="text" class="form-control" id="eventTitle" name="eventTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Event Date</label>
                            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                        </div>
                        <div class="d-flex align-items-center w-100">
                            <div class="mb-3 w-100">
                                <label for="startTime" class="form-label">Time Start</label>
                                <input type="time" class="form-control" id="startTime" name="startTime" required>
                            </div>
                            <p class="m-0 mx-3 mt-3"> to </p>
                            <div class="mb-3 w-100">
                                <label for="endTime" class="form-label">Time End</label>
                                <input type="time" class="form-control" id="endTime" name="endTime">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meeting Type</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="meetingTypeSwitch" name="meetingType" value="online">
                                <label class="form-check-label" for="meetingTypeSwitch" id="meetingTypeLabel">Face-to-Face</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="eventUrl" class="form-label">Event URL (for Online Meetings)</label>
                            <input type="url" class="form-control" id="eventUrl" name="eventUrl" placeholder="http://example.com" disabled>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="isRepeating" name="isRepeating">
                            <label class="form-check-label" for="isRepeating">Is this a repeating event?</label>
                        </div>
                        <button type="submit" class="btn btn-primary text-light">Add Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/appointment_calendar.js"></script>
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?php echo json_encode($events); ?>, // Load events from PHP array
            eventClick: function(info) {
                if (info.event.url) {
                    window.open(info.event.url, "_blank");
                    info.jsEvent.preventDefault();
                } else {
                    alert("This is a Face-to-Face event and does not have an online link.");
                }
            }
        });
        calendar.render();

        // Switch between Face-to-Face and Online Meeting
        var meetingTypeSwitch = document.getElementById('meetingTypeSwitch');
        var eventUrlField = document.getElementById('eventUrl');
        var meetingTypeLabel = document.getElementById('meetingTypeLabel');

        meetingTypeSwitch.addEventListener('change', function() {
            if (meetingTypeSwitch.checked) {
                eventUrlField.disabled = false;
                eventUrlField.required = true;
                meetingTypeLabel.innerHTML = 'Online';
            } else {
                eventUrlField.disabled = true;
                eventUrlField.required = false;
                meetingTypeLabel.innerHTML = 'Face-to-Face';
            }
        });

        // Handle form submission
        document.getElementById('addEventForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var eventTitle = document.getElementById('eventTitle').value;
            var eventDate = document.getElementById('eventDate').value;
            var startTime = document.getElementById('startTime').value;
            var endTime = document.getElementById('endTime').value;
            var eventUrl = document.getElementById('eventUrl').value;
            var isRepeating = document.getElementById('isRepeating').checked;
            var meetingType = document.getElementById('meetingTypeSwitch').checked ? 'online' : 'face-to-face';

            var event = {
                title: eventTitle,
                start: eventDate + 'T' + startTime,
                end: endTime ? eventDate + 'T' + endTime : null,
                url: meetingType === 'online' ? eventUrl : null
            };

            // Handle repeating events (same day each month)
            if (isRepeating) {
                var groupId = 'group' + Math.floor(Math.random() * 1000);
                event.groupId = groupId;

                var date = new Date(eventDate);
                for (let i = 1; i <= 3; i++) { // Repeat for the next 3 months
                    let newDate = new Date(date);
                    newDate.setMonth(date.getMonth() + i);
                    let repeatingEvent = Object.assign({}, event);
                    repeatingEvent.start = newDate.toISOString().split('T')[0] + 'T' + startTime;
                    repeatingEvent.end = endTime ? newDate.toISOString().split('T')[0] + 'T' + endTime : null;
                    calendar.addEvent(repeatingEvent);
                }
            }

            calendar.addEvent(event);

            // Reset the form and close the modal
            document.getElementById('addEventForm').reset();
            var modal = bootstrap.Modal.getInstance(document.getElementById('addEventModal'));
            modal.hide();
        });
    });
</script>