<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
  header('location: ./index.php');
}

include_once './helpers/data_masking.php';
require_once '../classes/account.class.php';
$account = new Account();
$appointment_array = $account->get_appointments_with_doctors_and_patients();

$index = isset($_GET['i']) ? intval($_GET['i']) : 0;

if (!isset($appointment_array[$index])) {
  die("Invalid appointment ID.");
}

$appointment = $appointment_array[$index];
?>

<html lang="en">
<?php
$title = 'Campuses | View User';
include './includes/admin_head.php';
function getCurrentPage()
{
  return basename($_SERVER['PHP_SELF']);
}

?>

<body>
  <?php
  require_once('./includes/admin_header.php');
  ?>
  <?php
  require_once('./includes/admin_sidepanel.php');
  ?>

  <section id="add_campus" class="page-container">
    <div class="row">

      <div class="col-2"></div>

      <div class="col-12 col-lg-8">
        <form>
          <div class="border shadow p-4 mb-5 bg-body rounded">
            <div class="d-flex align-items-center">
              <button class="btn p-0 me-auto" onclick="event.preventDefault(); window.history.back();">
                <i class='bx bx-chevron-left-circle fs-3 link'></i>
              </button>
              <?php
              $appointment_status = strtolower($appointment['appointment_status']);
              $statusClass = "";

              switch ($appointment_status) {
                case "completed":
                  $statusClass = "text-success";
                  break;
                case "ongoing":
                  $statusClass = "text-info";
                  break;
                case "cancelled":
                  $statusClass = "text-danger";
                  break;
                case "pending":
                  $statusClass = "text-warning";
                  break;
                default:
                  $statusClass = "text-secondary";
                  break;
              }
              ?>
              <h5 class="text-center w-100 mb-0">Status: <span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($appointment['appointment_status']); ?></span></h5>
            </div>

            <hr class="mx-3 my-4">

            <div class="row row-cols-1 row-cols-md-2">
              <!-- <div class="col d-flex ">
                <strong class="me-2">Appointment ID:</strong>
                <p><?php echo htmlspecialchars($appointment['appointment_id']); ?></p>
              </div> -->

              <div class="col d-flex ">
                <strong class="me-2">Time:</strong>
                <p><?php echo date("h:i A", strtotime($appointment['appointment_time'])); ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Date:</strong>
                <p><?php echo date("l, F j, Y", strtotime($appointment['appointment_date'])); ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Campus:</strong>
                <p><?php echo htmlspecialchars($appointment['patient_campus_name']); ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Address:</strong>
                <p><?php echo htmlspecialchars($appointment['patient_address']); ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Patient:</strong>
                <p><?php echo htmlspecialchars($appointment['patient_name']); ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Email:</strong>
                <p><?= maskEmail(($appointment['patient_email'])) ?></p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Patient Contact:</strong>
                <p><?= maskPhone(($appointment['patient_contact'])) ?></p>
              </div>
            </div>

            <div class="d-flex flex-column justify-content-end mb-3">
              <label for="reasons" class="form-label"><strong>Reason of Visit/Appointment:</strong></label>
              <textarea class="form-control" id="reason" rows="3" disabled readonly><?php echo htmlspecialchars($appointment['reason']); ?></textarea>
            </div>

            <div class="col d-flex ">
              <strong class="me-2">Doctor:</strong>
              <p><?php echo htmlspecialchars($appointment['doctor_name']); ?></p>
            </div>

            <div class="d-flex flex-column justify-content-end">
              <label for="notes" class="form-label"><strong>Notes:</strong></label>
              <textarea class="form-control" id="notes" rows="3" disabled readonly><?php echo htmlspecialchars($appointment['result']); ?></textarea>
            </div>

          </div>
        </form>

      </div>

      <div class="col-2"></div>

    </div>
  </section>
</body>

</html>