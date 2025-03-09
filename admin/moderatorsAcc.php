<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ./index.php');
}

include_once './helpers/data_masking.php';
require_once '../classes/account.class.php';
$account = new Account();
?>


<html lang="en">
<?php
$title = 'Admin | Staff';
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
    <h1 class="text-start mb-3">Moderators</h1>

    <div class="table-responsive overflow-hidden">
      <div class="search-keyword col-12 flex-lg-grow-0 d-flex justify-content-between justify-content-md-end mb-3 mb-md-0">

        <div class="input-group w-25 d-flex align-items-center border border-1 rounded-1 me-0 me-md-4">
          <i class='bx bx-search-alt text-green ps-2'></i>
          <input type="text" name="keyword" id="keyword" placeholder="Search" class="form-control border-0">
        </div>

        <a href="./add_moderatorAcc" class="input-group bg-success d-flex  justify-content-center align-items-center border border-1 rounded-1 p-1" style="width: 13%;">
          <i class='bx bx-plus text-white fs-4 ps-2 me-2'></i>
          <p class="m-0 text-white d-none d-lg-block">Add Moderator</p>
        </a>

      </div>
    </div>

    <table id="staff_table" class="table table-striped" style="width:100%">
      <thead>
        <tr>
          <th scope="col" width="3%">#</th>
          <th scope="col">Campus</th>
          <th scope="col">Username</th>
          <th scope="col" width="5%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $counter = 1;
        $accountArray = $account->show_mod();
        foreach ($accountArray as $item) {
        ?>
          <tr>
            <td><?= $counter ?></td>
            <td><?= $item['campus_name'] ?></td>
            <td><?= $item['email'] ?></td>
            <td class="d-flex justify-content-around align-items-center text-center">
              <a href="./add_staffAcc?= $item['acc-id'] ?>" title="View Details">
                <i class='bx bx-edit-alt'></i>
              </a>
              <button class="delete-btn bg-none" data-subject-id="<?= $item['account_id'] ?>">
                <i class='bx bx-user-x bg-none text-primary fs-5'></i>
              </button>
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
            Are you sure you want to disable (NAME) account?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary text-light" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger text-light" id="confirmDeleteBtn">Disable</button>
          </div>
        </div>
      </div>
    </div>

  </section>

  <script src="./js/staff-dataTables.js"></script>
  <script src="./js/modal-delete_comfirmation.js"></script>

</body>

</html>