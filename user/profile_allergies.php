<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php 
  $title = 'DocConnect | Profile';
	include '../includes/head.php';
?>
<body>
  <?php 
    require_once ('../includes/header.php');
  ?>

  <section id="profile" class="page-container">
    <div class="container py-5">

      <div class="row">
        <?php include 'profile_left.php'; ?>
        
        <div class="col-lg-9">
          <?php include 'profile_nav.php';?>

          <div class="card bg-body-tertiary mb-4">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Edit Allergies</h4>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAllergy">
                  <i class='bx bx-plus text-light'> add allergies</i>
                </button>
              </div>
              <hr>
              <div class="table-responsive">
                <?php
                  $allergies = array(
                    array(
                      'type' => 'Penicillin',
                      'level' => 'High',
                    ),
                    array(
                      'type' => 'Dust',
                      'level' => 'Medium',
                    ),
                    array(
                      'type' => 'Pollen',
                      'level' => 'Low',
                    ),
                    array(
                      'type' => 'Cat Fur',
                      'level' => 'Medium',
                    ),
                  );
                ?>
                <table class="table table-striped" id="eventsTable">
                  <thead>
                    <tr>
                      <th scope="col" width="3%">#</th>
                      <th scope="col">Type</th>
                      <th scope="col">Level</th>
                      <th class="text-end"><p class="me-3 mb-0">Action</p></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $counter = 1;
                    foreach ($allergies as $item) {
                    ?>
                      <tr>
                        <td><?= $counter ?></td>
                        <td><?= $item['type'] ?></td>
                        <td><?= $item['level'] ?></td>
                        <td class="text-end">
                          <button 
                            class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editAllergy"
                            data-type="<?= $item['type'] ?>"
                            data-level="<?= $item['level'] ?>"
                          >
                            <i class='bx bx-edit-alt text-light'></i>
                          </button>
                          <button 
                            class="btn btn-danger btn-sm ms-2" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteAllergy">
                            <i class='bx bxs-trash text-light'></i>
                          </button>

                        </td>
                      </tr>
                    <?php
                      $counter++;
                    }
                    ?>
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bootstrap Modal for EDITING ALLERGIES Table -->
  <div class="modal fade" id="editAllergy" tabindex="-1" aria-labelledby="edit_allergy" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header justify-content-center">
          <h3>Edit Allergy</h3>
          <button type="button" class="btn-close position-absolute end-0 top-0 me-3 mt-3" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editAllergyForm">
            <div class="row mb-3">
              <div class="col-6">
                <label for="allergyType" class="form-label">Type</label>
                <input type="text" class="form-control" id="allergyType" name="allergy_type">
              </div>
              <div class="col-6">
                <label for="allergyLevel" class="form-label">Level</label>
                <select class="form-control" id="allergyLevel" name="allergy_level">
                  <option value="High">High</option>
                  <option value="Medium">Medium</option>
                  <option value="Low">Low</option>
                </select>
              </div>
            </div>
            <div class="text-end">
              <input type="submit" class="btn btn-primary text-light" name="save" value="Save Changes">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Modal for ADDING ALLERGIES Table -->
  <div class="modal fade" id="addAllergy" tabindex="-1" aria-labelledby="add_allergy" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header justify-content-center">
          <h3>Add Allergy</h3>
          <button type="button" class="btn-close position-absolute end-0 top-0 me-3 mt-3" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addAllergy">
            <div class="row mb-3">
              <div class="col-6">
                <label for="allergyType" class="form-label">Type</label>
                <input type="text" class="form-control" id="allergyType" name="allergy_type">
              </div>
              <div class="col-6">
                <label for="allergyLevel" class="form-label">Level</label>
                <select class="form-control" id="allergyLevel" name="allergy_level">
                  <option value="Low">Low</option>
                  <option value="Medium">Medium</option>
                  <option value="High">High</option>
                </select>
              </div>
            </div>
            <div class="text-end">
              <input type="submit" class="btn btn-primary text-light" name="save" value="Add">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Modal for DELETING ALLERGIES Table -->
  <div class="modal fade" id="deleteAllergy" tabindex="-1" aria-labelledby="delete_allergy" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteConfirmationLabel">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this allergy? This action cannot be undone.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary text-light" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger text-light" id="confirmDeleteButton">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/user/profile_allergies.js"></script>

  <?php 
    require_once ('../includes/footer.php');
  ?>

</body>