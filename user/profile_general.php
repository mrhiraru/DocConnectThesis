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

$account_class = new Account();
if (isset($_POST['saveAccount'])) {
  $account_class->account_id = $_SESSION['account_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'DocConnect | Profile';
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
          $general = 'active';
          $aGeneral = 'page';
          $cGeneral = 'text-dark';

          include 'profile_nav.php';
          ?>

          <div class="card bg-body-tertiary mb-4">
            <div class="card-body">
              <h4 class="text-green mb-3">Personal Information</h4>
              <div class="row row-cols-1 row-cols-md-2 d-flex align-items-stretch">
                <div class="col">
                  <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">Name:</p>
                    </div>
                    <div class="col-sm-8">
                      <p class="text-muted mb-0">
                        <?= $_SESSION['fullname'] ?>
                      </p>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">Email:</p>
                    </div>
                    <div class="col-sm-8">
                      <p class="text-muted mb-0">
                        <?= $_SESSION['email'] ?>
                      </p>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">Contact:</p>
                    </div>
                    <div class="col-sm-8">
                      <p class="text-muted mb-0">
                        <?= $_SESSION['contact'] ?>
                      </p>
                    </div>
                  </div>
                  <hr>
                </div>

                <div class="col">
                  <!-- PADAAGDAG ito sa display -->
                  <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">Campus:</p>
                    </div>
                    <div class="col-sm-8">
                      <p class="text-muted mb-0"><?= $_SESSION['campus_name'] ?></p>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">School Id:</p>
                    </div>
                    <div class="col-sm-8">
                      <p class="text-muted mb-0"><?= $_SESSION['schoold_id'] ?></p>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-4">
                      <p class="mb-0">Address:</p>
                    </div>
                    <div class="col-sm-8">
                      <p class="text-muted mb-0">
                        <?= $_SESSION['address'] ?>
                      </p>
                    </div>
                  </div>
                  <hr>
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-12">
              <div class="card bg-body-tertiary mb-4 mb-md-0">
                <div class="card-body">
                  <h5 class="text-green mb-3">Medications</h5>
                  <hr>
                  <?php
                  $usedDrugs_array = array(
                    array(
                      'brandName' => 'Amoxicillin',
                      'genericName' => 'Amoxicillin',
                      'strenght' => '250mg',
                      'pack' => '100',
                      'from' => 'Tab',
                      'manufacturer' => 'Apotex Industries',
                    ),
                  );
                  ?>

                  <table id="profileGeneral_table" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th scope="col" width="3%">#</th>
                        <th scope="col">Medicine Name</th>
                        <th scope="col">Dosage</th>
                        <th scope="col">Frequency</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $counter = 1;
                      foreach ($usedDrugs_array as $item) {
                      ?>
                        <tr>
                          <td><?= $counter ?></td>
                          <td><?= $item['brandName'] ?></td>
                          <td><?= $item['genericName'] ?></td>
                          <td><?= $item['strenght'] ?></td>
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
          </div><div class="row mb-2">
            <div class="col-md-12">
              <div class="card bg-body-tertiary mb-4 mb-md-0">
                <div class="card-body">
                  <h5 class="text-green mb-3">Allergies</h5>
                  <hr>
                  <?php
                  $usedDrugs_array = array(
                    array(
                      'brandName' => 'Amoxicillin',
                      'genericName' => 'Amoxicillin',
                      'strenght' => '250mg',
                      'pack' => '100',
                      'from' => 'Tab',
                      'manufacturer' => 'Apotex Industries',
                    ),
                  );
                  ?>

                  <table id="profileGeneral_table" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th scope="col" width="3%">#</th>
                        <th scope="col">Allergy Name</th>
                        <th scope="col">Description</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $counter = 1;
                      foreach ($usedDrugs_array as $item) {
                      ?>
                        <tr>
                          <td><?= $counter ?></td>
                          <td><?= $item['brandName'] ?></td>
                          <td><?= $item['genericName'] ?></td>
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
          </div><div class="row mb-2">
            <div class="col-md-12">
              <div class="card bg-body-tertiary mb-4 mb-md-0">
                <div class="card-body">
                  <h5 class="text-green mb-3">Immunization</h5>
                  <hr>
                  <?php
                  $usedDrugs_array = array(
                    array(
                      'brandName' => 'Amoxicillin',
                      'genericName' => 'Amoxicillin',
                      'strenght' => '250mg',
                      'pack' => '100',
                      'from' => 'Tab',
                      'manufacturer' => 'Apotex Industries',
                    ),
                  );
                  ?>

                  <table id="profileGeneral_table" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th scope="col" width="3%">#</th>
                        <th scope="col">Immunization Name</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $counter = 1;
                      foreach ($usedDrugs_array as $item) {
                      ?>
                        <tr>
                          <td><?= $counter ?></td>
                          <td><?= $item['brandName'] ?></td>
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
          <div class="row">
            <div class="col-md-12">
              <div class="card bg-body-tertiary mb-4 mb-md-0">
                <div class="card-body">
                  <h5 class="text-green mb-3">Medical History <span class="float-end fs-6"><a href="./add_allergy.php" class="btn btn-primary btn-sm text-light">Add Allergy</a></span></h5>
                  <hr>
                  <?php
                  $usedDrugs_array = array(
                    array(
                      'brandName' => 'Amoxicillin',
                      'genericName' => 'Amoxicillin',
                      'strenght' => '250mg',
                      'pack' => '100',
                      'from' => 'Tab',
                      'manufacturer' => 'Apotex Industries',
                    ),
                  );
                  ?>

                  <table id="profileGeneral_table" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th scope="col" width="3%">#</th>
                        <th scope="col">Condition</th>
                        <th scope="col">Date Diagnose</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $counter = 1;
                      foreach ($usedDrugs_array as $item) {
                      ?>
                        <tr>
                          <td><?= $counter ?></td>
                          <td><?= $item['brandName'] ?></td>
                          <td><?= $item['genericName'] ?></td>
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
      </div>
    </div>
  </section>

  <?php
  require_once('../includes/footer.php');
  ?>

</body>

</html>