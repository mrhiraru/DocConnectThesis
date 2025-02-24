<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ./index.php');
}

require_once '../classes/campus.class.php';
$campus = new Campus();

?>


<html lang="en">
<?php
$title = 'Admin | Campuses';
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
    <div class="d-flex justify-content-center position-relative my-3">
      <h1 class="text-center m-0">Campuses</h1>
      <a href="./add_campus" class="btn btn-success text-white position-absolute end-0">Add Campus</a>
    </div>
    <div class="row row-cols-1 row-cols-md-2 mx-1 mx-md-5">
      <?php
      $campusArray = $campus->show_campus();
      foreach ($campusArray as $item) {
      ?>

        <div class="col p-0 mb-3">
          <a href="./view_campus.php?campus_id=<?= $item['campus_id'] ?>" class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between shadow-sm">
              <div class="align-content-center text-center">
                <img src="<?php if (isset($item['campus_profile'])) {
                            echo "../assets/images/" . $item['campus_profile'];
                          } else {
                            echo "../assets/images/bg-1.png";
                          } ?>" alt="" class="w-100 rounded-2">
                <div class="details mx-3">
                  <div class="d-flex mt-2 align-items-center">
                    <i class='bx bxs-school fs-3'></i>
                    <p class="m-0 ms-3 fs-5"><?= $item['campus_name'] ?></p>
                  </div>
                  <div class="d-flex mt-2 align-items-center">
                    <i class='bx bxs-map fs-3'></i>
                    <p class="m-0 ms-3 fs-5 text-start"><?= $item['campus_address'] ?></p>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>

      <?php
      }
      ?>
    </div>
  </section>

</body>

</html>