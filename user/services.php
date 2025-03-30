<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/services.class.php');

$services = new Services();
$aboutContent = $services->getAboutContent();
$sections = $services->getAllSectionsWithServices();

?>

<!DOCTYPE html>
<html lang="en">
<?php 
$title = 'Services';
$services = 'active';
include '../includes/head.php';
?>
<body>
  <?php 
    require_once ('../includes/header.php');
  ?>
  <section class="services hero vh-50 d-flex justify-content-center align-items-center mt-5">
    <h1 class="text-center mb-4 text-light">Our Services</h1>
  </section>

  <div class="container mt-3">
    <div class="p-3 pb-md-4 mx-auto text-center">
      <h1 class="display-6 fw-normal"><?php echo htmlspecialchars($aboutContent['section_title'] ?? 'Telecommunication Health Services'); ?></h1>
      <p class="fs-5 text-muted mx-5">
        <?php echo htmlspecialchars($aboutContent['section_description'] ?? 'Welcome to DocConnect\'s Telecommunication Health Services! Our goal is to provide you with seamless access to quality healthcare and wellness resources from the comfort of your home or office.'); ?>
      </p>
    </div>

    <?php foreach ($sections as $section): ?>
      <div class="mb-5">
        <h2 class="text-primary"><?php echo htmlspecialchars($section['section_title']); ?></h2>
        <p><?php echo htmlspecialchars($section['section_description']); ?></p>
        <div class="row">
          <?php foreach ($section['services'] as $service): ?>
            <div class="col-md-4 mb-4">
              <div class="card service-card shadow-sm h-100">
                <?php if ($service['image']): ?>
                  <img src="../assets/images/services/<?php echo htmlspecialchars($service['image']); ?>" class="card-img-top service-img" alt="<?php echo htmlspecialchars($service['title']); ?>">
                <?php endif; ?>
                <div class="card-body">
                  <h5 class="card-title text-green"><?php echo htmlspecialchars($service['title']); ?></h5>
                  <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php 
    require_once ('../includes/footer.php');
  ?>
</body>
</html>