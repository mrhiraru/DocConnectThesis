<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 2) {
  header('location: ../index.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

?>
<html lang="en">
<?php
$title = 'Admin | Settings';
include './includes/admin_head.php';
function getCurrentPage()
{
  return basename($_SERVER['PHP_SELF']);
}
?>

<link rel="stylesheet" href="./css/OnOffToggle.css">

<body>
  <?php
  require_once('./includes/admin_header.php');
  ?>
  <?php
  require_once('./includes/admin_sidepanel.php');
  ?>

  <section id="dashboard" class="page-container">

    <?php
    $userManagement = 'active';
    $aUserManagement = 'page';
    $cUserManagement = 'text-dark';

    include './includes/adminSettings_nav.php';
    ?>

    <h1 class="text-start">User Group Management</h1>

    <div class="row flex-md-nowrap row-cols-1 row-cols-md-2 d-none d-md-flex">
      <div class="col">
      </div>

      <div class="col">
        <div class="row flex-md-nowrap row-cols-1 row-cols-md-3">
          <p class="m-0 text-muted text-center">Students</p>
          <p class="m-0 text-muted text-center">Staff</p>
          <p class="m-0 text-muted text-center">Faculty</p>
        </div>
      </div>
    </div>

    <hr class="my-2">

    <div class="row flex-md-nowrap row-cols-1 row-cols-md-2 align-items-center">
      <div class="col">
        <h4 class="m-0">Appointment reminders</h4>
      </div>

      <div class="col">
        <div class="row flex-md-nowrap row-cols-1 row-cols-md-3">
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Students</p>
            <label class="switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Staff</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Faculty</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <hr class="my-2">

    <div class="row flex-md-nowrap row-cols-1 row-cols-md-2 align-items-center">
      <div class="col">
        <h4 class="m-0">Checkup results</h4>
      </div>

      <div class="col">
        <div class="row flex-md-nowrap row-cols-1 row-cols-md-3">
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Students</p>
            <label class="switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Staff</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Faculty</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <hr class="my-2">

    <div class="row flex-md-nowrap row-cols-1 row-cols-md-2 align-items-center">
      <div class="col">
        <h4 class="m-0">General announcements</h4>
      </div>

      <div class="col">
        <div class="row flex-md-nowrap row-cols-1 row-cols-md-3">
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Students</p>
            <label class="switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Staff</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Faculty</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <hr class="my-2">

    <div class="row flex-md-nowrap row-cols-1 row-cols-md-2 align-items-center">
      <div class="col">
        <h4 class="m-0">Health and Wellness Tips</h4>
      </div>

      <div class="col">
        <div class="row flex-md-nowrap row-cols-1 row-cols-md-3">
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Students</p>
            <label class="switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Staff</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Faculty</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <hr class="my-2">

    <div class="row flex-md-nowrap row-cols-1 row-cols-md-2 align-items-center">
      <div class="col">
        <h4 class="m-0">Policy Updates</h4>
      </div>

      <div class="col">
        <div class="row flex-md-nowrap row-cols-1 row-cols-md-3">
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Students</p>
            <label class="switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Staff</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Faculty</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <hr class="my-2">

    <div class="row flex-md-nowrap row-cols-1 row-cols-md-2 align-items-center">
      <div class="col">
        <h4 class="m-0">Special Programs or Initiatives</h4>
      </div>

      <div class="col">
        <div class="row flex-md-nowrap row-cols-1 row-cols-md-3">
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Students</p>
            <label class="switch">
              <input type="checkbox" checked>
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Staff</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
          <div class="col d-flex align-items-center justify-content-between justify-content-md-center">
            <p class="m-0 text-muted d-block d-md-none me-2">Faculty</p>
            <label class="switch">
              <input type="checkbox">
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <hr class="my-2">
  </section>

</body>

</html>