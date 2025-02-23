<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
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
    $termsOfServices = 'active';
    $aTermsOfServices = 'page';
    $cTermsOfServices = 'text-dark';

    include 'adminSettings_nav.php';
    ?>

    <h1 class="text-start">Terms Of Services</h1>

    <form method="POST" action="save_privacy_policy.php">
      <textarea id="editor" name="privacy_policy"></textarea>

      <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary text-light">Save</button>
        <button type="button" class="btn btn-secondary text-light" onclick="location.reload();">Cancel</button>
      </div>
    </form>
  </section>

  <script src="./js/summerNote.js"></script>
</body>

</html>