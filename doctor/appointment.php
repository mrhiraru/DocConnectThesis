<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
  header('location: ../index.php');
}

require_once('../tools/functions.php');
require_once('../classes/appointment.class.php');

$appointment_class = new Appointment();

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
      <main class="col-md-9 ms-sm-auto col-lg-10 bg-light">
        <div class="card flex-fill my-4">
          <div class="card-body">
            <h2>Appointments</h2>

            <div class="table-responsive">
              <table class="table table-striped" id="eventsTable">
                <thead>
                  <tr>
                    <th></th>
                    <th>Date & Time</th>
                    <th>Patient</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $appointmentArray = $appointment_class->doctor_appointments($_SESSION['doctor_id']);
                  $counter = 1;
                  foreach ($appointmentArray as $item) {
                  ?>
                    <tr>
                      <td><?= $counter ?></td>
                      <td><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></td>
                      <td><?= $item['patient_name'] ?></td>
                      <td><?= $item['appointment_status'] ?></td>
                      <td class="text-center">
                        <a href="./update-appointment.php?appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-warning btn-sm"><i class='bx bxs-edit text-light'></i></a>
                        <button class="btn btn-danger btn-sm ms-2"><i class='bx bxs-trash text-light'></i></button>
                      </td>
                    </tr>
                  <?php
                    $counter++;
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card flex-fill my-4">
          <div class="card-body">
            <div id="calendar"></div>
            <button id="addAppointmentBtn" class="btn btn-primary mt-3 text-light" data-bs-toggle="modal" data-bs-target="#addEventModal">Add Appointment</button>
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