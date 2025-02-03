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

$appointment_class = new Appointment();
if (isset($_POST['request'])) {
  $appointment_class->patient_id = $_SESSION['patient_id'];
  $appointment_class->doctor_id = htmlentities($_POST['doctor_id']);
  $appointment_class->appointment_date = htmlentities($_POST['appointment_date']);
  $appointment_class->appointment_time = htmlentities($_POST['appointment_time']);
  $appointment_class->appointment_status = "Pending";

  if (
    validate_field($appointment_class->patient_id) &&
    validate_field($appointment_class->doctor_id) &&
    validate_field($appointment_class->appointment_date) &&
    validate_field($appointment_class->appointment_time) &&
    validate_field($appointment_class->appointment_status)
  ) {
    if ($appointment_class->add_appointment()) {
      $success = 'success';
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

  <section id="appointment" class="page-container padding-medium p-3">
    <div class="row mb-3">

      <div class="col-2"></div>

      <div class="col-sm-12 col-md-8">
        <form action="submit_appointment.php" method="post" class="border border-dark-subtle shadow-sm rounded-2 p-3 mb-4 mb-md-0">
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
                  <p id="doctor_name" class="fs-6 fw-semibold text-dark mb-1"><span class="text-black-50">Name:</span> Not Selected</p>
                  <p id="specialty" class="fs-6 text-muted mb-1"><span class="fw-semibold">Specialty:</span> N/A</p>
                  <p id="contact" class="fs-6 text-muted mb-1"><span class="fw-semibold">Contact:</span> N/A</p>
                  <p id="email" class="fs-6 text-muted mb-1"><span class="fw-semibold">Email:</span> N/A</p>
                  <p id="working_day" class="fs-6 text-muted mb-1"><span class="fw-semibold">Working Days:</span> N/A</p>
                  <p id="working_time" class="fs-6 text-muted mb-1"><span class="fw-semibold">Working Time:</span> N/A</p>
                </div>
              </div>
            </div>
          </div>

          <hr>

          <!-- Name -->
          <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
              <label for="firstName" class="form-label text-black-50">First Name</label>
              <input type="text" class="form-control bg-light border border-dark" id="firstName" name="first_name" required>
            </div>
            <div class="col-md-6">
              <label for="lastName" class="form-label text-black-50">Last Name</label>
              <input type="text" class="form-control bg-light border border-dark" id="lastName" name="last_name" required>
            </div>
          </div>

          <!-- Date of Birth -->
          <div class="mb-3">
            <label class="form-label text-black-50">Date of Birth</label>
            <div class="row">
              <div class="col-md-4 mb-3 mb-md-0">
                <select class="form-select bg-light border border-dark" name="dob_day" required>
                  <option value="" disabled selected>Day</option>
                  <?php for ($i = 1; $i <= 31; $i++) echo "<option value='$i'>$i</option>"; ?>
                </select>
              </div>
              <div class="col-md-4 mb-3 mb-md-0">
                <select class="form-select bg-light border border-dark" name="dob_month" required>
                  <option value="" disabled selected>Month</option>
                  <?php 
                  $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                  foreach ($months as $index => $month) echo "<option value='".($index+1)."'>$month</option>";
                  ?>
                </select>
              </div>
              <div class="col-md-4">
                <select class="form-select bg-light border border-dark" name="dob_year" required>
                  <option value="" disabled selected>Year</option>
                  <?php for ($i = date('Y') - 30; $i <= date('Y'); $i++) echo "<option value='$i'>$i</option>"; ?>
                </select>
              </div>
            </div>
          </div>

          <!-- Gender -->
          <div class="mb-3">
            <label for="gender" class="form-label text-black-50">Gender</label>
            <select class="form-select bg-light border border-dark" id="gender" name="gender" required>
              <option value="" disabled selected>Please Select</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
          </div>

          <!-- Phone Number -->
          <div class="mb-3">
          <label for="phoneNo" class="form-label text-black-50">Phone No.</label>
          <input type="text" class="form-control bg-light border border-dark" id="phoneNo" name="Phone_No" value="+63 " pattern="\+63 \d{3} \d{3} \d{4}" required/>
          </div>

          <!-- Address -->
          <!-- <div class="mb-3">
            <label for="address" class="form-label text-black-50">Address</label>
            <input type="text" class="form-control bg-light border border-dark" id="address" name="address" placeholder="Street, City, State, Postal Code" required>
          </div> -->

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label text-black-50">Email</label>
            <input type="email" class="form-control bg-light border border-dark" id="email" name="email" placeholder="example@example.com" required>
          </div>

          <!-- <div class="row mb-3">
            Facility Question
            <div class="col-md-6">
              <label class="form-label text-black-50">Have you ever applied to our facility before?</label>
              <div class="form-check">
                <input class="form-check-input border border-dark" type="radio" name="facility_applied" id="yes" value="Yes" required>
                <label class="form-check-label" for="yes">Yes</label>
              </div>
              <div class="form-check">
                <input class="form-check-input border border-dark" type="radio" name="facility_applied" id="no" value="No" required>
                <label class="form-check-label" for="no">No</label>
              </div>
            </div>
  
            Procedure
            <div class="col-md-6">
              <label for="procedure" class="form-label text-black-50">Which procedure do you want to make an appointment for?</label>
              <select class="form-select bg-light border border-dark" id="procedure" name="procedure" required>
                <option value="" disabled selected>Please Select</option>
                <option value="General Consultation">General Consultation</option>
                <option value="Dental Check-up">Dental Check-up</option>
              </select>
            </div>
          </div> -->

          <!-- Preferred Appointment Date -->
          <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
              <label for="appointment_date" class="form-label text-black-50">Select Date</label>
              <input type="date" id="appointment_date" name="appointment_date" data-startday="" data-endday="" min="<?php echo date('Y-m-d'); ?>" class="form-control fs-6 px-2 py-1 bg-light border border-dark" required>
            </div>
            <div class="col-md-6">
              <label for="appointment_time" class="form-label text-black-50">Select Time</label>
              <input  type="time" id="appointment_time" name="appointment_time" step="1800" min="" max="" class="form-control fs-6 px-2 py-1 bg-light border border-dark" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="reasonForAppointment" class="form-label text-black-50">Reason for appointment?</label>
            <textarea id="reasonForAppointment" name="reason_for_appointment" class="form-control bg-light border border-dark" rows="3" placeholder="Describe the reason for your appointment (e.g., symptoms, check-up, follow-up)"></textarea>
          </div>

          <hr>

          <div class="w-100 d-flex justify-content-end">
            <button id="request" type="submit" class="w-50 w-md-25 btn btn-outline-dark mt-2">Request Appointment</button>
          </div>
        </form>
        
      </div>

      <div class="col-2"></div>

      <!-- <div class="col-sm-12 col-md-4 h-100">
        <div class="d-flex flex-column justify-content-between bg-green p-3 rounded-2 h-100 text-white">
          <div>
            <h4>Appointment Schedule</h4>
            <div class="overflow-y-scroll min-vh-100">
              <?php
              $appointmentArray = $appointment_class->user_appointments($_SESSION['patient_id']);
              foreach ($appointmentArray as $item) {
              ?>
                <div class="col-12 border border-2 border-white rounded-2 p-2 mb-1">
                  <div class="m-0 mb-1 p-0 px-2">
                    <p class="mb-2 fs-5"><?= $item['doctor_name'] ?></p>
                  </div>
                  <hr class="text-white my-1 mx-0">
                  <div class="row d-flex justify-content-between m-0 mb-1">
                    <p class="col-4 mb-2"><?= $item['appointment_status'] ?></p>
                    <p class="col-8 mb-2 text-end"><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time']))  ?></p>
                    <div class="col-12">
                      <a href="" class="btn btn-sm btn-light">Chat Doctor</a>
                      <a href="https://meet.google.com/por-udiy-etd" class="btn btn-sm btn-light <?= date("M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) == date("l, M d, Y g:i A") ? '' : 'disabled' ?>">Join Meeting</a>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>
            </div>
          </div>
          <div>
            <div>
              <button type="button" class="w-100 btn btn-lg btn-outline-light mt-2">Enter Meeting</button>
            </div>
          </div>
        </div>
      </div> -->
    </div>
  </section>



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

  <script src="../js/user_appointment.js"></script>

</body>

</html>