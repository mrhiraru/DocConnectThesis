<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ../index.php');
}

require_once('../classes/termsOfService.class.php');

$termsOfService = new TermsOfService();
$currentTerms = $termsOfService->getTermsOfService();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $content = $_POST['terms_of_service'];
  if ($termsOfService->updateTermsOfService($content)) {
    $_SESSION['message'] = 'Terms of Service updated successfully!';
    header('location: termsOfServices.php');
    exit();
  } else {
    $_SESSION['error'] = 'Failed to update Terms of Service.';
  }
}

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

    include './includes/adminSettings_nav.php';
    ?>

    <h1 class="text-start">Terms Of Services</h1>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['message'];
                                        unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="termsOfServices.php">
      <textarea id="editor" name="terms_of_service"><?php echo htmlspecialchars($currentTerms['content'] ?? ''); ?></textarea>

      <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary text-light">Save</button>
        <button type="button" class="btn btn-secondary text-light" onclick="location.reload();">Cancel</button>
      </div>
    </form>
  </section>

  <script src="./js/summerNote.js"></script>
</body>

</html>