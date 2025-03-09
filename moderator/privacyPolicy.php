<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
  header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
  header('location: ../index.php');
}

require_once('../classes/privacyPolicy.class.php');

$privacyPolicy = new PrivacyPolicy();
$currentPolicy = $privacyPolicy->getPrivacyPolicy();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $content = $_POST['privacy_policy'];
  if ($privacyPolicy->updatePrivacyPolicy($content)) {
    $_SESSION['message'] = 'Privacy Policy updated successfully!';
    header('location: privacyPolicy.php');
    exit();
  } else {
    $_SESSION['error'] = 'Failed to update Privacy Policy.';
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
    $privacyPolicy = 'active';
    $aPrivacyPolicy = 'page';
    $cPrivacyPolicy = 'text-dark';

    include './includes/adminSettings_nav.php';
    ?>

    <h1 class="text-start">Privacy Policy</h1>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['message'];
                                        unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="privacyPolicy.php">
    <textarea id="editor" name="privacy_policy"><?php echo htmlspecialchars($currentPolicy['content'] ?? ''); ?></textarea>

      <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary text-light">Save</button>
        <button type="button" class="btn btn-secondary text-light" onclick="location.reload();">Cancel</button>
      </div>
    </form>
  </section>

  <script src="./js/summerNote.js"></script>
</body>

</html>