<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 3) {
  header('location: ./index.php');
}

require_once '../classes/account.class.php';
$account = new Account(); // Create an instance of the class
$appointment_array = $account->get_appointments_with_doctors_and_patients();

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

  <section id="appointment" class="page-container">
    <h1 class="text-center mb-3">Appointment Management</h1>

    <div class="table-responsive overflow-hidden">
      <div class="search-keyword col-12 flex-lg-grow-0 d-flex justify-content-between justify-content-md-end mb-3 mb-md-0">

        <div class="form-group mx-0 mx-md-4">
          <select id="sort-by" class="form-select me-md-2">
            <option value="">Sort By</option>
            <option value="0">Code</option>
            <option value="1">Type</option>
            <option value="2">Patient Name</option>
            <option value="3">Doctor Name</option>
            <option value="4">Appointment Date</option>
          </select>
        </div>

        <div class="input-group w-auto d-flex align-items-center border border-1 rounded-1 me-0 me-md-4">
          <i class='bx bx-search-alt text-green ps-2'></i>
          <input type="text" name="keyword" id="keyword" placeholder="Search" class="form-control border-0">
        </div>

      </div>
    </div>

    <?php
    function getStatusClass($appointment_status)
    {
      switch ($appointment_status) {
        case 'Completed':
          return 'bg-success';
        case 'Ongoing':
          return 'bg-info';
        case 'Cancelled':
          return 'bg-danger';
        case 'Pending':
          return 'bg-warning';
        default:
          return 'bg-secondary';
      }
    }
    ?>

    <table id="appointment_table" class="table table_striped" style="width: 100%;">
      <thead>
        <tr>
          <th scope="col" width="3%">#</th>
          <th scope="col">Patient Name</th>
          <th scope="col">Doctor Name</th>
          <th scope="col">Appointment Date</th>
          <th scope="col">Status</th>
          <th scope="col" width="5%">View</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $counter = 1;
        foreach ($appointment_array as $item) {
          $statusClass = getStatusClass($item['appointment_status']);
        ?>

          <tr>
            <td><?= $counter ?></td>
            <td><?= $item['patient_name'] ?></td>
            <td><?= $item['doctor_name'] ?></td>
            <td><?= date("l, M d, Y", strtotime($item['appointment_date'])) . " " . date("g:i A", strtotime($item['appointment_time'])) ?></td>
            <td class="<?= $statusClass ?> text-light text-center"><?= $item['appointment_status'] ?></td>
            <td class="text-center">
              <a href="./viewAppointment?i=<?= $counter - 1 ?>" title="View Details">
                <i class='bx bx-show'></i>
              </a>
            </td>
          </tr>
        <?php
          $counter++;
        }
        ?>
      </tbody>
    </table>
  </section>

  <script src="./js/appointment-dataTable.js"></script>

</body>

</html>