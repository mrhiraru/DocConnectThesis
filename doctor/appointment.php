<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
  header('location: ./index.php');
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

<body onload="load_appointments(<?= $_SESSION['doctor_id'] ?>, 'Pending')">
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
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="Pending-tab" data-bs-toggle="tab" data-bs-target="#Pending" type="button" role="tab" aria-controls="Pending" aria-selected="true" onclick="load_appointments(<?= $_SESSION['doctor_id'] ?>, 'Pending')">Pending</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="Incoming-tab" data-bs-toggle="tab" data-bs-target="#Incoming" type="button" role="tab" aria-controls="Incoming" aria-selected="false" onclick="load_appointments(<?= $_SESSION['doctor_id'] ?>, 'Incoming')">Incoming</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="Ongoing-tab" data-bs-toggle="tab" data-bs-target="#Ongoing" type="button" role="tab" aria-controls="Ongoing" aria-selected="false" onclick="load_appointments(<?= $_SESSION['doctor_id'] ?>, 'Ongoing')">Ongoing</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="Compeleted-tab" data-bs-toggle="tab" data-bs-target="#Completed" type="button" role="tab" aria-controls="Completed" aria-selected="false" onclick="load_appointments(<?= $_SESSION['doctor_id'] ?>, 'Completed')">Completed</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="Cancelled-tab" data-bs-toggle="tab" data-bs-target="#Cancelled" type="button" role="tab" aria-controls="Cancelled" aria-selected="false" onclick="load_appointments(<?= $_SESSION['doctor_id'] ?>, 'Cancelled')">Cancelled</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="Pending" role="tabpanel" aria-labelledby="Pending" tabindex="0">

              </div>
              <div class="tab-pane fade" id="Incoming" role="tabpanel" aria-labelledby="Incoming" tabindex="0">

              </div>
              <div class="tab-pane fade" id="Ongoing" role="tabpanel" aria-labelledby="Ongoing" tabindex="0">

              </div>
              <div class="tab-pane fade" id="Completed" role="tabpanel" aria-labelledby="Completed" tabindex="0">

              </div>
              <div class="tab-pane fade" id="Cancelled" role="tabpanel" aria-labelledby="Cancelled" tabindex="0">

              </div>
            </div>
          </div>
        </div>

        <!-- <div class="card flex-fill my-4">
          <div class="card-body">
            <div id="calendar"></div>
            <button id="addAppointmentBtn" class="btn btn-primary mt-3 text-light" data-bs-toggle="modal" data-bs-target="#addEventModal">Add Appointment</button>
          </div>
        </div> -->
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

</body>

</html>

<script>
  function load_appointments(doctor_id, status) {
    $.ajax({
      url: "../handlers/doctor.load_appointments.php",
      type: "GET",
      data: {
        doctor_id: doctor_id,
        status: status,
      },
      success: function(response) {
        $("#" + status).html(response);
      },
      error: function(xhr, status, error) {
        console.error("Error loading messages:", error);
      },
    });
  }
</script>