<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Settings | Interaction';
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
                    <label for="notification-type">Communication Preferences</label>
                    <div class="d-flex flex-wrap">
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="email" checked>
                        <label class="form-check-label" for="email">
                          Email
                        </label>
                      </div>
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="phone">
                        <label class="form-check-label" for="phone">
                          Phone
                        </label>
                      </div>
                      <div class="form-check py-3 pe-4 ps-5 border border-2 rounded-2 me-3">
                        <input class="form-check-input" type="checkbox" value="" id="in-app_chat" checked>
                        <label class="form-check-label" for="in-app_chat">
                          In-app chat
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <!-- hnd ko alam kung kailangan pa to -->
              <!-- <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label for="patient_notes" class="form-label">Patient Notes</label>
                    <textarea class="form-control" id="bio" rows="3"></textarea>
                  </div>
                </div>
              </div> -->

              <!-- hnd ko pa to paano to -->
              <div class="row">
                <div class="col-6 mb-3">
                  <div class="form-group mb-2">
                    <label for="follow-up_schedule">Follow-up Schedule</label>
                    <input type="datetime-local" class="form-control" id="follow-up_schedule" name="follow-up_schedule" placeholder="">
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