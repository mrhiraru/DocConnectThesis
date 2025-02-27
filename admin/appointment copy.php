<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ./index.php');
}

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
    require_once ('./includes/admin_header.php');
  ?>
  <?php 
    require_once ('./includes/admin_sidepanel.php');
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
      $appointment_array = array(
        array(
          'Code' => '0001',
          'Type' => 'F-2-F',
          'Patient Name' => 'Wally West',
          'Doctor Name' => 'Dr. Olive Oil',
          'Appointment date' => 'Monday, 9:00 - 10:00 am',
          'Status' => 'Completed',
        ),
        array(
          'Code' => '0002',
          'Type' => 'Online',
          'Patient Name' => 'Jon Kent',
          'Doctor Name' => 'Dr. James Jamison',
          'Appointment date' => 'Tuesday, 11:00 - 12:00 am',
          'Status' => 'In Progress',
        ),
        array(
          'Code' => '0003',
          'Type' => 'Online',
          'Patient Name' => 'Allen Barry',
          'Doctor Name' => 'Dr. Knot Rildoktor',
          'Appointment date' => 'Wednesday, 01:30 - 02:00 pm',
          'Status' => 'Canceled',
        ),
        array(
          'Code' => '0004',
          'Type' => 'F-2-F',
          'Patient Name' => 'Jason Todd',
          'Doctor Name' => 'Dr. Thomas Wayne',
          'Appointment date' => 'Friday, 08:30 - 10:00 am',
          'Status' => 'Waiting',
        ),
      );
      
      function getStatusClass($status) {
        switch ($status) {
          case 'Completed':
            return 'bg-success';
          case 'In Progress':
            return 'bg-info';
          case 'Canceled':
            return 'bg-danger';
          case 'Waiting':
            return 'bg-warning';
          default:
            return 'bg-secondary';
        }
      }
      ?>
      
      <table id="appointment_table" class="table table-striped" style="width:100%">
        <thead>
          <tr>
            <th scope="col" width="3%">#</th>
            <th scope="col">Code</th>
            <th scope="col">Type</th>
            <th scope="col">Patient Name</th>
            <th scope="col">Doctor Name</th>
            <th scope="col">Appointment date</th>
            <th scope="col">Status</th>
            <th scope="col" width="5%">View</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $counter = 1;
          foreach ($appointment_array as $item) {
            $statusClass = getStatusClass($item['Status']);
          ?>
            <tr>
              <td><?= $counter ?></td>
              <td><?= $item['Code'] ?></td>
              <td><?= $item['Type'] ?></td>
              <td><?= $item['Patient Name'] ?></td>
              <td><?= $item['Doctor Name'] ?></td>
              <td><?= $item['Appointment date'] ?></td>
              <td class="<?= $statusClass ?> text-light text-center"><?= $item['Status'] ?></td>
              <td class="text-center">
                <a href="./viewAppointment?= $item['Code'] ?>" title="View Details">
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