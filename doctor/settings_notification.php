<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Settings | Notification';
$setting = 'active';
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
      

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">Account Settings</h1>
        </div>

        <?php 
          require_once('../includes/doctorSetting_Nav.php')
        ?>

        <div class="card bg-body-tertiary mb-4">
          <div class="card-body">
            <form>
              <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-group mb-2">
                    <label for="notification-type">Notification Types</label>
                    <div class="d-flex flex-wrap">
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="appointment_reminders" checked>
                        <label class="form-check-label" for="appointment_reminders">
                          Appointment Reminders
                        </label>
                      </div>
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="updates">
                        <label class="form-check-label" for="updates">
                          Updates
                        </label>
                      </div>
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="newsletters">
                        <label class="form-check-label" for="newsletters">
                          Newsletters
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-group mb-2">
                    <label for="sms-notification">SMS Notifications</label>
                    <div class="d-flex flex-wrap">
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="new_appointments" checked>
                        <label class="form-check-label" for="new_appointments">
                          New Appointments
                        </label>
                      </div>
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="appointment_updates">
                        <label class="form-check-label" for="appointment_updates">
                          Appointment Updates
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-12 mb-3">
                  <div class="form-group mb-2">
                    <label for="system_alerts">System Alerts</label>
                    <div class="d-flex flex-wrap">
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="new_patient_registered" checked>
                        <label class="form-check-label" for="new_patient_registered">
                          New Patient Registered
                        </label>
                      </div>
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="appointment_cancellations">
                        <label class="form-check-label" for="appointment_cancellations">
                          Appointment Cancellations
                        </label>
                      </div>
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="missed_appointments">
                        <label class="form-check-label" for="missed_appointments">
                          Missed Appointments
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Save Button -->
              <button type="submit" class="btn btn-primary text-light">Save Changes</button>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>