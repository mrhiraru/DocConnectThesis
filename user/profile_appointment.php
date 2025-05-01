<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
  header('location: ../index.php');
  exit();
}

$birthdate = isset($_SESSION['birthdate']) ? date('Y-m-d', strtotime($_SESSION['birthdate'])) : "";

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/appointment.class.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | Profile';
$appointment = 'active';
include '../includes/head.php';
?>

<body>
  <?php
  require_once('../includes/header.php');
  ?>

  <section id="profile" class="page-container">
    <div class="container py-5">

      <div class="row">
        <?php include 'profile_left.php'; ?>

        <div class="col-lg-9">
          <?php
          $appointment = 'active';
          $aAppointment = 'page';
          $cAppointment = 'text-dark';

          include 'profile_nav.php';
          ?>

          <div class="card bg-body-tertiary mb-4">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="card-title mb-0 text-green">Appointment List</h5>

                <div class="d-flex align-items-center gap-2">
                  <select id="statusFilter" class="form-select form-select-sm" style="width: 200px;">
                    <option value="All">All</option>
                    <option value="Pending">Pending</option>
                    <option value="Incoming">Incoming</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                  </select>

                  <a href="./appointment" class="btn btn-sm btn-primary text-light">
                    New Appointment
                  </a>
                </div>
              </div>

              <hr>
              <div class="table-responsive">
                <table class="table table-striped" id="eventsTable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Doctor</th>
                      <th>Date & Time</th>
                      <th>Status</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $appointment_class = new Appointment();
                    $appointmentArray = $appointment_class->get_patient_appointment_user($_SESSION['patient_id']);
                    $counter = 1;
                    if (empty(!$appointmentArray)) {
                      foreach ($appointmentArray as $item) {
                    ?>
                        <tr data-status="<?= strtolower($item['appointment_status']) ?>">
                          <td><?= $counter ?></td>
                          <td><?= $item['doctor_name'] ?></td>
                          <td><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></td>
                          <td><?= $item['appointment_status'] ?></td>
                          <td class="text-center">
                            <?php
                            if ($item['appointment_status'] == 'Incoming') {
                            ?>
                              <a href="./appointment-view.php?account_id=<?= $_SESSION['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>

                            <?php
                            } else if ($item['appointment_status'] == 'Ongoing') {
                            ?>
                              <a href="./appointment-view.php?account_id=<?= $_SESSION['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-success btn-sm text-light"><i class='bx bx-play-circle me-1'></i>View</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Pending') {
                            ?>
                              <button type="button" class="btn btn-sm btn-danger text-light" id="cancel" onclick="cancel_request(<?= $item['appointment_id'] ?>)">Cancel</button>
                            <?php
                            } else if ($item['appointment_status'] == 'Completed') {
                            ?>
                              <a href="./appointment-view.php?account_id=<?= $_SESSION['account_id'] ?>&appointment_id=<?= $item['appointment_id'] ?>" class="btn btn-info btn-sm text-light"><i class='bx bx-file-blank me-1'></i>Result</a>
                            <?php
                            } else if ($item['appointment_status'] == 'Cancelled') {
                            ?>

                            <?php
                            }
                            ?>
                          </td>
                        </tr>
                      <?php
                        $counter++;
                      }
                    } else {
                      ?>
                      <tr>
                        <td colspan="5" class="text-center">No Appointments</td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelModalLabel">Are you sure you want to cancel the appointment?</h5>
        </div>
        <div class="modal-body">
          <div class="row d-flex">
            <div class="col-12 text-center">
              <button type="button" class="btn btn-secondary text-light" data-bs-dismiss="modal" id="cancel-no" aria-label="Close">No</button>
              <button type="button" class="btn btn-primary text-light" data-bs-dismiss="modal" id="cancel-yes" aria-label="Close">Yes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="cancelledModal" tabindex="-1" aria-labelledby="cancelledModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelledModalLabel">The appointment has been cancelled.</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row d-flex">
            <div class="col-12 text-center">
              <a href="./profile_appointment" class="text-decoration-none text-dark">
                <p class="m-0 text-primary fw-bold">Click to Continue.</p>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  require_once('../includes/footer.php');
  ?>
  <script>
    function cancel_request(appointment_id) {
      const updated = document.getElementById('cancelModal');
      if (updated) {
        var myModal = new bootstrap.Modal(updated, {});
        myModal.show();

        document.getElementById("cancel-yes").addEventListener("click", function() {
          const formData = {
            appointment_id: appointment_id,
            cancel_request: true,
          };

          $.ajax({
            url: '../handlers/doctor.update_appointment.php',
            type: 'POST',
            data: formData,
            success: function(response) {
              if (response.trim() === 'success') {
                window.location.href = './profile_appointment.php';
              } else {
                console.error('Error:', response);
              }
            },
            error: function(xhr, status, error) {
              console.error('Error sending message:', error);
            }
          });

          myModal.hide();
        });

        document.getElementById("cancel-no").addEventListener("click", function() {

          myModal.hide();
        });
      }
    }

    document.getElementById("statusFilter").addEventListener("change", function() {
      const selectedStatus = this.value.toLowerCase();
      const rows = document.querySelectorAll("#eventsTable tbody tr");

      rows.forEach(row => {
        const rowStatus = row.getAttribute("data-status");

        if (selectedStatus === "all" || rowStatus === selectedStatus) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  </script>

</body>



</html>