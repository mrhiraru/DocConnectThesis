<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ./index.php');
}

require_once '../classes/campus.class.php';
$campus = new Campus();
$record = $campus->view_campus($_GET['campus_id']);

?>

<html lang="en">
<?php
$title = 'Campuses | Campus A';
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

  <section id="campus" class="page-container">
    <div class="d-flex justify-content-between align-items-center">
      <a href="./campus" class="btn btn-secondary text-white"><i class='bx bx-chevron-left'></i></a>
      <h1 class="text-center m-0"><?= $record['campus_name'] ?></h1>
      <a href="edit_campus?campus_id=<?= $_GET['campus_id'] ?>" class="btn btn-primary text-white">Edit</a>
    </div>

    <div class="d-flex justify-content-center w-100 h-50 mt-3 mb-5">
      <img src="<?php if (isset($record['campus_profile'])) {
                  echo "../assets/images/" . $record['campus_profile'];
                } else {
                  echo "../assets/images/bg-1.png";
                } ?>" alt="" class="w-75 rounded-2 shadow-lg">
    </div>

    <div class="campus_name text-center mt-2 mb-3 d-none">
      <h2>Campus A</h2>
    </div>

    <div class="row mx-1 mx-md-4 justify-content-center align-items-center">
      <div class="col-md-6 d-flex flex-column align-items-center justify-content-center">
        <div class="d-flex justify-content-center w-100 h-100 shadow-lg">
          <iframe src="<?= $record['campus_map_url'] ?>" width="100%" height="450" style="border:0;" allowfullscreen="true" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>

      <div class="col-md-6">
        <div class="d-flex flex-column align-items-center justify-content-center border-bottom border-2">
          <i class='bx bx-map fs-3 text-danger'></i> <!-- Address Icon -->
          <p class="fs-5 mb-2"><?= $record['campus_address'] ?></p>
        </div>
        <div class="d-flex flex-column align-items-center justify-content-center border-bottom border-2">
          <i class='bx bx-phone fs-3 text-danger'></i> <!-- Contact Number Icon -->
          <p class="fs-5 mb-2"><?= $record['campus_contact'] ?></p>
        </div>
        <div class="d-flex flex-column align-items-center justify-content-center border-bottom border-2">
          <i class='bx bx-envelope fs-3 text-danger'></i> <!-- Email Icon -->
          <p class="fs-5 mb-2"><?= $record['campus_email'] ?></p>
        </div>
        <div class="d-flex flex-column align-items-center justify-content-center border-bottom border-2">
          <i class='bx bx-user fs-3 text-danger'></i> <!-- Moderator Icon -->
          <p class="fs-5 mb-2"><?= $campus->get_moderator_name($record['moderator_id']) ?></p>
        </div>
      </div>
    </div>

  </section>

</body>

</html>