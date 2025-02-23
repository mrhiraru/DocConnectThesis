<?php 
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ../index.php');
}

include_once './helpers/data_masking.php';
require_once '../classes/account.class.php';
$account = new Account();
?>

<html lang="en">
<?php 
  $title = 'Admin | Doctors';
	include './includes/admin_head.php';
  function getCurrentPage() {
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

  <section id="doc-acc" class="page-container">
    <h1 class="text-start my-3">Doctors Accounts</h1>

    <div class="table-responsive overflow-hidden">
      <div class="search-keyword col-12 flex-lg-grow-0 d-flex justify-content-between justify-content-md-end mb-3 mb-md-0">

        <div class="form-group mx-0 mx-md-4">
          <select id="sort-by" class="form-select me-md-2">
            <option value="">Sort By</option>
            <option value="0">Name</option>
            <option value="1">Account ID</option>
          </select>
        </div>
        
        <div class="input-group w-auto d-flex align-items-center border border-1 rounded-1 me-0 me-md-4">
          <i class='bx bx-search-alt text-green ps-2'></i>
          <input type="text" name="keyword" id="keyword" placeholder="Search" class="form-control border-0">
        </div>

        <a href="./add_doctorAcc" class="input-group bg-success d-flex  justify-content-center align-items-center border border-1 rounded-1 p-1" style="width: 13%;">
          <i class='bx bx-plus text-white fs-4 ps-2 me-2'></i>
          <p class="m-0 text-white d-none d-lg-block">Add Doctor</p>
        </a>

      </div>
    </div>


      <table id="usersAcc_table" class="table table-striped" style="width:100%">
        <thead>
          <tr>
            <th scope="col" width="3%">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email Address</th>
            <th scope="col">Gender</th>
            <th scope="col">Phone No.</th>
            <th scope="col">Date of Birth</th>
            <th scope="col">Specialties</th>
            <th scope="col">Working Hours</th>
            <th scope="col">Number of Patients</th>

            <th scope="col" width="7%">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $counter = 1;
          $accountArray = $account->show_doc();
          foreach ($accountArray as $item) {
          
          ?>
            <tr>
              <td><?= $counter ?></td>
              <td> <?= (isset($item['middlename'])) ? ucwords(strtolower($item['firstname'] . ' ' . $item['middlename'] . ' ' . $item['lastname'])) : ucwords(strtolower($item['firstname'] . ' ' . $item['lastname'])) ?></td>
              <td><?= maskEmail($item['email']) ?></td>
              <td><?= $item['gender'] ?></td>
              <td><?= maskPhone($item['contact']) ?></td>
              <td><?= date('F j Y', strtotime($item['birthdate'])) ?></td>
              <td><?= "no data"//$item['specialties'] ?></td>
              <td><?= "no data"//$item['work-hour'] ?></td>
              <td><?= "no data"//$item['no.patients'] ?></td>
              
              <td class="text-center">
                <div class="d-flex align-items-center justify-content-center">
                  <a href="patient_details.php?code=<?= $item['account_id'] ?>" title="View Details">
                    <i class='bx bx-edit-alt mx-1'></i>
                  </a>
                  <button class="delete-btn bg-none" data-subject-id="<?= $item['account_id'] ?>">
                    <i class='bx bx-user-x mx-1 text-primary fs-5'></i>
                  </button>

                </div>
              </td>
            </tr>
          <?php
            $counter++;
          }
          ?>
        </tbody>
      </table>

      <!-- confirm delete modal markup -->
      <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Action</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to disable this account?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary text-light" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger text-light" id="confirmDeleteBtn">Disable</button>
            </div>
          </div>
        </div>
      </div>

  </section>

  <script src="./js/doctorsAcc-dataTables.js"></script>
  <script src="./js/modal-delete_comfirmation.js"></script>

</body>
</html>